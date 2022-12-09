<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\PasswordReset;
use App\Notifications\ResetPasswordRequest;
use App\Http\Requests\ResetPassword;

class ResetPasswordController extends Controller
{
    public function sendMail(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return $this->responseError('Email không tồn tại');
        }
        $passwordReset = PasswordReset::updateOrCreate([
            'email' => $user->email,
        ], [
            'token' => Str::random(60),
        ]);
        if ($passwordReset) {
            $user->notify(new ResetPasswordRequest($passwordReset->token));
        }
        
        return $this->responseSuccess();
    }

    public function reset(ResetPassword $request)
    {
        $passwordReset = PasswordReset::where('token', '=', $request->token)->first();
        if (!$passwordReset) {
            return $this->responseError('Link lấy lại mật khẩu không tồn tại');
        }
        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();

            return $this->responseError('Link lấy lại mật khẩu đã hết hạn');
        }
        $validated = $request->validated();
        $user = User::where('email', $passwordReset->email)->first();
        $user->update(['password'=>bcrypt($request->password)]);
        $passwordReset->delete();

        return $this->responseSuccess();
    }
}
