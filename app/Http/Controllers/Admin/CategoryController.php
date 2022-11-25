<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\CategoryRequest;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function getCategory(Request $request) {
        $search = $request->search;
        $category = Category::where('name', 'like', "%$search%")->orderBy('id', 'DESC')->paginate(10);
        if ($category) {
            $category->getCollection()->transform(function ($value) {

                return $params = [
                    'id' => $value->id,
                    'name' => $value->name,
                ];
            });
        }
        return $this->responseSuccess($category);
    }
    public function deleteCategory(Request $request) {
        Category::find($request->id)->delete();
        return $this->responseSuccess();
    }
    public function createCategory(CategoryRequest $request) {
        $validated = $request->validated();
        $category = new Category;
        $category->name = $request->name;
        $category->save();
        return $this->responseSuccess();
    }
    public function updateCategory(CategoryRequest $request) {
        $validated = $request->validated();
        try {
            Category::where('id',$request->id)->update([
                'name'=>$request->name,
            ]);
            return $this->responseSuccess();
        } catch(\Exception $e) {
            Log::info($e);
            return $this->responseError('Có lỗi xảy ra');
        }
    }
}
