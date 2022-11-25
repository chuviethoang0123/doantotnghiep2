<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        \Log::info(request());
        if (request()->has('image')) {
            $image = 'required|mimes:jpeg,jpg,png';
        } else {
            $image = 'nullable|mimes:jpeg,jpg,png';
        }
        return [
            'image' => $image,
            'content' => 'required',
            'name' => 'required',
            'description' => 'required',
            'brand' => 'required',
            'category' => 'required',
            'width' => 'required',
            'height' => 'required',
            'length' => 'required',
            'weight' => 'required',
            'price' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'image.required' => 'Vui lòng chọn một ảnh!',
            'image.mimes' => 'Định dạng file phải là JPEG, JPG, PNG !',
            'name.required' => 'Vui lòng nhập tên sản phẩm',
            'content.required' => 'Vui lòng nhập nội dung',
            'description.required' => 'Vui lòng nhập mô tả ngắn',
            'brand.required' => 'Vui lòng chọn thương hiệu',
            'category.required' => 'Vui lòng chọn danh mục',
            'width.required' => 'Vui lòng nhập độ dài',
            'height.required' => 'Vui lòng nhập chiều cao',
            'length.required' => 'Vui lòng nhập chiều dài',
            'weight.required' => 'Vui lòng nhập khối lượng',
            'price.required' => 'Vui lòng nhập giá bán',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $error = $validator->errors()->first();
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
            'message' => $error,
            'status' => false,
        ], 200));
    }
}
