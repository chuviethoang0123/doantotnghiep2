<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Voucher;
use Illuminate\Support\Facades\File;
use App\Http\Requests\VoucherRequest;
use Storage;
use Carbon\Carbon;

class VoucherController extends Controller
{
    public function getVoucher(Request $request) {
        $search = $request->search;
        $start = $request->starts_at ? CarBon::parse($request->starts_at)->format('Y-m-d')." 00:00:00" : '';
        $end = $request->expires_at ? CarBon::parse($request->expires_at)->format('Y-m-d')." 23:59:59" : '';
        $voucher = Voucher::where('code', 'like', "%$search%")
        ->where('starts_at', 'like', "%$start%")
        ->where('expires_at', 'like', "%$end%")
        ->orderBy('id', 'DESC')->paginate(10);
        if ($voucher) {
            $voucher->getCollection()->transform(function ($value) {
                $value->image = env('APP_IMAGE') . 'voucher/' . $value->image;
                return $value;
            });
        }
        return $this->responseSuccess($voucher);
    }
    public function deleteVoucher(Request $request) {
        Storage::disk('s3')->delete('voucher/' . Voucher::find($request->id)->image);
        Voucher::find($request->id)->delete();
        return $this->responseSuccess();
    }
    public function createVoucher(VoucherRequest $request) {
        $validated = $request->validated();
        if($request->hasFile('image')){
            $filenameWithExt = $request->file('image')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            Storage::disk('s3')->put('voucher/' . $fileNameToStore, file_get_contents($request->file('image')), 'public');

            $voucher = new Voucher;
            $voucher->image = $fileNameToStore;
            $voucher->name = $request->name;
            $voucher->code = $request->code;
            $voucher->description = $request->description;
            $voucher->uses = 0;
            $voucher->max_uses = $request->uses;
            $voucher->max_uses_user = $request->user;
            $voucher->minimum_order = $request->order;
            $voucher->discount_amount = $request->discount;
            $voucher->percentage = $request->precent;
            $voucher->starts_at = CarBon::parse($request->start);
            $voucher->expires_at = CarBon::parse($request->end);
            $voucher->save();
            $this->responseSuccess();
        }  else {
            $this->responseError('Có lỗi xảy ra');
        }
    }
    public function updateVoucher(VoucherRequest $request) {
        $validated = $request->validated();

        $id = $request->id;
        $imageOld = Voucher::find($id)->image;
        $voucher = Voucher::find($id);
        if($request->hasFile('image')){
            $filenameWithExt = $request->file('image')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            Storage::disk('s3')->put('voucher/' . $fileNameToStore, file_get_contents($request->file('image')), 'public');
            Storage::disk('s3')->delete('voucher/' . $imageOld);

            $voucher->image = $fileNameToStore;
            $voucher->name = $request->name;
            $voucher->code = $request->code;
            $voucher->description = $request->description;
            $voucher->max_uses = $request->uses;
            $voucher->max_uses_user = $request->user;
            $voucher->minimum_order = $request->order;
            $voucher->discount_amount = $request->discount;
            $voucher->percentage = $request->precent;
            $voucher->starts_at = CarBon::parse($request->start);
            $voucher->expires_at = CarBon::parse($request->end);
            $voucher->save();
            $this->responseSuccess();
        }  else {
            $voucher->name = $request->name;
            $voucher->code = $request->code;
            $voucher->description = $request->description;
            $voucher->max_uses = $request->uses;
            $voucher->max_uses_user = $request->user;
            $voucher->minimum_order = $request->order;
            $voucher->discount_amount = $request->discount;
            $voucher->percentage = $request->precent;
            $voucher->starts_at = CarBon::parse($request->start);
            $voucher->expires_at = CarBon::parse($request->end);
            $voucher->save();
            $this->responseSuccess();
        }
    }
    public function detailVoucher(Request $request) {
        try {
            $voucher = Voucher::find($request->id);
            $params = [
                'code' => $voucher->code,
                'name' => $voucher->name,
                'description' => $voucher->description,
                'uses' => $voucher->max_uses,
                'user' => $voucher->max_uses_user,
                'order' => $voucher->minimum_order,
                'discount' => $voucher->discount_amount,
                'precent' => $voucher->percentage,
                'start' => $voucher->starts_at,
                'end' => $voucher->expires_at,
            ];
            $image = env('APP_IMAGE'). 'voucher/' . $voucher->image;
            return $this->responseSuccess(['info' => $params,'image' => $image ]);
        } catch(\Throwable $th) {
            Log::info($th);
            $this->responseError($th);
        }
    }
}
