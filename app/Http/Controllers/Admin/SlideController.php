<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slide;
use Illuminate\Support\Facades\File;
use App\Http\Requests\SlideRequest;
use Storage;
use Carbon\Carbon;

class SlideController extends Controller
{
    public function getSlide(Request $request) {
        $slide = Slide::where('status', $request->status)->orderBy('id', 'DESC')->paginate(10);
        if ($slide) {
            $slide->getCollection()->transform(function ($value) {

                return $params = [
                    'slide_id' => $value->id,
                    'slide_status' => $value->status,
                    'slide_image' => env('APP_IMAGE') . 'slide/' . $value->image,
                    'created_at' => $value->created_at,
                    'updated_at' => $value->updated_at
                ];
            });
        }
        return $this->responseSuccess($slide);
    }
    public function deleteSlide(Request $request) {
        Storage::disk('s3')->delete('slide/' . Slide::find($request->id)->image);
        Slide::find($request->id)->delete();
        return $this->responseSuccess();
    }
    public function createSlide(SlideRequest $request) {
        $validated = $request->validated();
        if($request->hasFile('image')){
            $filenameWithExt = $request->file('image')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            Storage::disk('s3')->put('slide/' . $fileNameToStore, file_get_contents($request->file('image')), 'public');

            $slide = new Slide;
            $slide->image = $fileNameToStore;
            $slide->status = $request->status === 'true' ? 1 : 0;
            $slide->save();
            return $this->responseSuccess();
        }  else {
            return $this->responseError('Có lỗi xảy ra');
        }
    }
    public function updateSlide(SlideRequest $request) {
        $validated = $request->validated();
        $slide_id = $request->slide_id;
        $imageOld = Slide::find($slide_id)->image;

        if($request->hasFile('image')){
            $filenameWithExt = $request->file('image')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            Storage::disk('s3')->put('slide/' . $fileNameToStore, file_get_contents($request->file('image')), 'public');
            Storage::disk('s3')->delete('slide/' . $imageOld);;

            Slide::where('id',$slide_id)->update([
                'image'=> $fileNameToStore,
                'status'=>$request->status === 'true' ? 1 : 0 ,
            ]);
            return $this->responseSuccess();

        }   
        else {
            Slide::where('id',$slide_id)->update([
                'status'=>$request->status === 'true' ? 1 : 0 ,
            ]);
            return $this->responseSuccess();
        }
    }
}
