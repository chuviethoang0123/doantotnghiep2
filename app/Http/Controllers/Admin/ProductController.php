<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Productimage;
use Illuminate\Support\Facades\File;
use App\Http\Requests\ProductRequest;
use Carbon\Carbon;
use App\Models\Warehouse;
use Storage;

class ProductController extends Controller
{
    public function getProduct(Request $request) {
        $search = $request->search;
        $brand = $request->brand;
        $category = $request->category;
        $product = Product::select('product.*', 'warehouse.quantity as quantity')
        ->join('warehouse','warehouse.product_id','=','product.id')
        ->where('name', 'like', "%$search%")
        ->where('brand_id', 'like', "%$brand%")
        ->where('category_id', 'like', "%$category%")
        ->orderBy('id', 'DESC')->paginate(10);
        if ($product) {
            $product->getCollection()->transform(function ($value) {
                return $params = [
                    'id' => $value->id,
                    'name' => $value->name,
                    'image' => config('app.linkImage') . '/uploads/product/' . $value->image,
                    'price' => $value->price,
                    'discount' => $value->discount,
                    'selling' => $value->selling,
                    'inventory' => $value->quantity
                ];
            });
        }
        return $this->responseSuccess($product);
    }
    public function deleteProduct(Request $request) {
        $product_image = Productimage::where('product_id', Product::find($request->id)->id)->get();
        if ($product_image) {
            foreach ($product_image as $image) {
                File::delete(public_path("uploads/product_image/".$image->product_image_name));
                $image->delete();
            }
        }
        File::delete(public_path("uploads/product/".Product::find($request->id)->image));
        Product::find($request->id)->delete();
        return $this->responseSuccess();
    }
    public function createProduct(ProductRequest $request) {
        $validated = $request->validated();
        if($request->hasFile('image')){
            $filenameWithExt = $request->file('image')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            $path = $request->file('image')->move('uploads/product/', $fileNameToStore);

            $product = new Product;
            $product->image = $fileNameToStore;
            $product->name = $request->name;
            $product->content = $request->content;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->width = $request->width;
            $product->height = $request->height;
            $product->length = $request->length;
            $product->discount = $request->discount;
            $product->weight = $request->weight;
            $product->brand_id = $request->brand;
            $product->category_id = $request->category;
            $product->selling = 0;
            $product->save();

            //tạo kho sản phẩm
            $warehouse = new Warehouse;
            $warehouse->product_id = $product->id;
            $warehouse->quantity = 0;
            $warehouse->save();

            if ($request->hasFile('fileList')) {
                $files = $request->file('fileList');
                foreach($files as $image) {
                    $filenameWithExt = $image->getClientOriginalName();
                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension = $image->getClientOriginalExtension();
                    $fileNameToStore= $filename.'_'.time().'.'.$extension;
                    $path = $image->move('uploads/product_image/', $fileNameToStore);

                    $product_image = new Productimage;
                    $product_image->product_id = $product->id;
                    $product_image->product_image_name = $fileNameToStore;
                    $product_image->save();
                }
            }
            return $this->responseSuccess();
        }  else {
            return $this->responseError('Có lỗi xảy ra');
        }
    }
    public function updateProduct(ProductRequest $request) {
        $validated = $request->validated();

        $id = $request->id;
        $imageOld = Product::find($id)->image;
        $product = Product::find($id);
        if($request->hasFile('image')){
            $filenameWithExt = $request->file('image')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            $path = $request->file('image')->move('uploads/product/', $fileNameToStore);
            File::delete(public_path("uploads/product/".$imageOld));


            $product->image = $fileNameToStore;
            $product->name = $request->name;
            $product->content = $request->content;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->width = $request->width;
            $product->height = $request->height;
            $product->length = $request->length;
            $product->discount = $request->discount;
            $product->weight = $request->weight;
            $product->brand_id = $request->brand;
            $product->category_id = $request->category;
            $product->selling = 0;
            $product->save();

            if ($request->hasFile('fileList')) {
                $files = $request->file('fileList');
                foreach($files as $image) {
                    $filenameWithExt = $image->getClientOriginalName();
                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension = $image->getClientOriginalExtension();
                    $fileNameToStore= $filename.'_'.time().'.'.$extension;
                    $path = $image->move('uploads/product_image/', $fileNameToStore);

                    $product_image = new Productimage;
                    $product_image->product_id = $product->id;
                    $product_image->product_image_name = $fileNameToStore;
                    $product_image->save();
                }
            }
            return $this->responseSuccess();
        }  else {
            $product->name = $request->name;
            $product->content = $request->content;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->width = $request->width;
            $product->height = $request->height;
            $product->length = $request->length;
            $product->discount = $request->discount;
            $product->weight = $request->weight;
            $product->brand_id = $request->brand;
            $product->category_id = $request->category;
            $product->selling = 0;
            $product->save();

            if ($request->hasFile('fileList')) {
                $files = $request->file('fileList');
                foreach($files as $image) {
                    $filenameWithExt = $image->getClientOriginalName();
                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension = $image->getClientOriginalExtension();
                    $fileNameToStore= $filename.'_'.time().'.'.$extension;
                    $path = $image->move('uploads/product_image/', $fileNameToStore);

                    $product_image = new Productimage;
                    $product_image->product_id = $product->id;
                    $product_image->product_image_name = $fileNameToStore;
                    $product_image->save();
                }
            }
            return $this->responseSuccess();
        }
    }
    public function detailProduct(Request $request) {
        try {
            $product = Product::find($request->id);
            $product_image = Productimage::where('product_id', $request->id)->get();
            $listImage = [];
            foreach($product_image as $key => $image) {
                $param = [
                    'url' => config('app.linkImage'). '/uploads/product_image/' . $image->product_image_name,
                    'uid' => '-'.$key,
                    'id' => $image->id,
                    'status' => 'done'
                ];
                array_push($listImage, $param);
            }
            $params = [
                'content' => $product->content,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'width' => $product->width,
                'height' => $product->height,
                'length' => $product->length,
                'weight' => $product->weight,
                'discount' => $product->discount,
                'brand' => $product->brand_id,
                'category' => $product->category_id,
                'image' => config('app.linkImage'). '/uploads/product/' . $product->image,
                'fileList' => $listImage
            ];
            return $this->responseSuccess($params);
        } catch(\Throwable $th) {
            Log::info($th);
            return $this->responseError($th);
        }
    }
    public function deleteImage(Request $request) {
        $image = Productimage::find($request->id);
        File::delete(public_path("uploads/product_image/".$image->product_image_name));
        $image->delete();
        return $this->responseSuccess();

    }
}
