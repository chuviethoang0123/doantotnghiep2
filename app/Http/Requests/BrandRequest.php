<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BrandRequest extends FormRequest
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
        if (request()->has('image')) {
            $image = 'required|mimes:jpeg,jpg,png';
        } else {
            $image = 'nullable|mimes:jpeg,jpg,png';
        }
        return [
            'image' => $image,
            'name' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'image.required' => 'Vui lòng chọn một ảnh!',
            'image.mimes' => 'Định dạng file phải là JPEG, JPG, PNG !',
            'name.required' => 'Vui lòng nhập tên thương hiệu'
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
