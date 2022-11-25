<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class VoucherRequest extends FormRequest
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
            'code' => 'required',
            'name' => 'required',
            'description' => 'required',
            'uses' => 'required',
            'user' => 'required',
            'order' => 'required',
            'discount' => 'required',
            'precent' => 'required',
            'start' => 'required',
            'end' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'image.required' => 'Vui lòng chọn một ảnh!',
            'image.mimes' => 'Định dạng file phải là JPEG, JPG, PNG !',
            'name.required' => 'Vui lòng nhập tên voucher',
            'code.required' => 'Vui lòng nhập mã code',
            'description.required' => 'Vui lòng nhập mô tả ngắn',
            'uses.required' => 'Vui lòng nhập số lượng',
            'user.required' => 'Vui lòng nhập số lượng',
            'order.required' => 'Vui lòng nhập đơn hàng tối thiểu',
            'discount.required' => 'Vui lòng nhập số tiền chiết khấu tối đa',
            'precent.required' => 'Vui lòng nhập phần trăm giảm giá',
            'start.required' => 'Vui lòng nhập ngày bắt đầu',
            'end.required' => 'Vui lòng nhập ngày kết thúc',
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
