<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use Illuminate\Support\Facades\File;
use App\Http\Requests\BrandRequest;
use Storage;
use Carbon\Carbon;

class BrandController extends Controller
{
    public function getBrand(Request $request) {
        $search = $request->search;
        $brand = Brand::where('name', 'like', "%$search%")->orderBy('id', 'DESC')->paginate(10);
        if ($brand) {
            $brand->getCollection()->transform(function ($value) {

                return $params = [
                    'id' => $value->id,
                    'name' => $value->name,
                    'image' => config('app.linkImage') . '/uploads/brand/' . $value->image,
                ];
            });
        }
        return $this->responseSuccess($brand);
    }
    public function deleteBrand(Request $request) {
        File::delete(public_path("uploads/brand/".Brand::find($request->id)->image));
        Brand::find($request->id)->delete();
        return $this->responseSuccess();
    }
    public function createBrand(BrandRequest $request) {
        $validated = $request->validated();
        if($request->hasFile('image')){
            $filenameWithExt = $request->file('image')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            $path = $request->file('image')->move('uploads/brand/', $fileNameToStore);

            $brand = new Brand;
            $brand->image = $fileNameToStore;
            $brand->name = $request->name;
            $brand->save();
            return $this->responseSuccess();
        }  else {
            return $this->responseError('Có lỗi xảy ra');
        }
    }
    public function updateBrand(BrandRequest $request) {
        $validated = $request->validated();

        $brand_id = $request->id;
        $imageOld = Brand::find($brand_id)->image;

        if($request->hasFile('image')){
            $filenameWithExt = $request->file('image')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            $path = $request->file('image')->move('uploads/brand/', $fileNameToStore);
            File::delete(public_path("uploads/brand/".$imageOld));

            Brand::where('id',$brand_id)->update([
                'image'=> $fileNameToStore,
                'name'=>$request->name,
            ]);
            return $this->responseSuccess();

        }   
        else {
            Brand::where('id',$brand_id)->update([
                'name'=>$request->name,
            ]);
            return $this->responseSuccess();
        }
    }
}
