<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Warehouse;

class ImportController extends Controller
{
    public function getProductImport(Request $request) {
        $product = Product::where('name', 'like', "%$request->search%")->get();
        foreach ($product as $pr) {
            $pr->image = config('app.linkImage'). '/uploads/product/' . $pr->image;
        }
        return $this->responseSuccess($product);
    }
    public function importWarehouse(Request $request) {
        try {
            $product = $request->all();
            foreach ($product as $pr) {
                $warehouse = Warehouse::where('product_id', $pr['id'])->first();
                $warehouse->quantity += $pr['quantity'];
                $warehouse->save(); 
            }
            return $this->responseSuccess();
        } catch (\Throwable $th) {
            Log::info($th);
            return $this->responseError($th);
        }
    }
}
