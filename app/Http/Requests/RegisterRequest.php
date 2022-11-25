<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
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
        return [
                'name' => 'required',
                'email' => 'required|unique:users,email',
                'password'=>'required||min:8|max:32',
                'retype_password' => 'required|same:password',
        ];
    }

    public function messages()
    {
        return [
            'name.required'  => 'Bạn chưa nhập tài khoản',
            'password.required' => 'Bạn chưa nhập mật khẩu',
            'password.max' => 'Mật khẩu phải có ít hơn 32 kí tự',
            'password.min' => 'Mật khẩu phải có nhiều hơn 8 kí tự',
            'email.required' => 'Vui lòng nhập email',
            'email.unique' => 'Email đã tồn tại',
            'retype_password.same' => 'Mật khẩu nhập lại chưa khớp'
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
