<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Storage;
use Socialite;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $validated = $request->validated();
        $credentials = $request->only('email', 'password');
        if ( !$token = JWTAuth::attempt($credentials)) {
            $params = 'Tài khoản hoặc mật khẩu không chính xác';
            return $this->responseError($params);
        }
        if ($request->remember === true) {
            JWTAuth::factory()->setTTL(60*60*7);
        }
        $user = User::find(Auth::user()->id);
        $params = [
            'token' => $token,
            'role' => $user->role,
            'user_id' => $user->id
        ];
        return $this->responseSuccess($params)->header('Authorization', $token);
    }

    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        $user = new User;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->name = $request->name;
        $user->password = bcrypt($request->password);
        $user->role = 1;
        $user->image = 'image';
        $user->save();
        return $this->responseSuccess();
    }

    public function logout(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        try {
            JWTAuth::invalidate($request->token);
            return $this->responseSuccess();
        } catch (JWTException $exception) {
            $params = 'Bạn chưa đăng nhập';
            return $this->responseError($params);
        }
    }

    public function user(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $user->image = env('APP_IMAGE'). 'user/' . $user->image; 
        return $this->responseSuccess($user);
    }

    public function updateUser(UserRequest $request)
    {   
        $validated = $request->validated();
        $user = User::find($request->id);
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        if($request->hasFile('image')){
            \Log::info($request->file('image'));
            $filenameWithExt = $request->file('image')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            Storage::disk('s3')->put('user/' . $fileNameToStore, file_get_contents($request->file('image')), 'public');
            Storage::disk('s3')->delete('user/' . $user->image);

            $user->image = $fileNameToStore;
        }
        $user->save();
        return $this->responseSuccess();
    }

    public function refresh()
    {
        return response([
            'status' => 'success'
        ]);
    }

    public function loginFacebook() {
        $facebook = Socialite::driver('facebook')->redirect()->getTargetUrl();
        return $this->responseSuccess($facebook);
    }

    public function callback()
    {
        $getInfo = Socialite::driver('facebook')->stateless()->user();
        $user = User::where('facebook_id', $getInfo->id)->first();
        $token = '';
        if (!$user) {
            $createUser = User::create([
                'name' => $getInfo->name,
                'email' => $getInfo->email ? $getInfo->email : '',
                'image' => $getInfo->avatar,
                'phone' => '',
                'facebook_id' => $getInfo->id,
                'role' => 1,
                'role_admin' => null,
                'password' => Hash::make(rand(1,10000)),
            ]);
            $user = $createUser;
            $token = $this->checkLoginFacebook($createUser);
        } else {
            $token = $this->checkLoginFacebook($user);
        }
        return redirect()->away(env('FRONTEND_URL') . 'login-facebook?token=' . $token . '&role=' . $user->role . '&user_id=' . $user->user_id);
    }

    public function checkLoginFacebook($user) {
        $token = JWTAuth::fromUser($user);
        return $token;
    }
}
