<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Http\Requests\UserAdminRequest;
use Illuminate\Support\Facades\File;
use Storage;
use JWTAuth;

class UserController extends Controller
{
    function __construct() {
        $user = JWTAuth::toUser(JWTAuth::getToken());
        if (!($user->role_admin & 16)) {
            $this->responseError();
        }
    }

    public function listUser(Request $request) {
        $key_search = $request->search;
        $user = DB::table('users')
        ->where(function ($query) use($key_search) {
            $query->where('users.email', 'like' , "%$key_search%");
        })
        ->where('users.role', 'like', 2)
        ->paginate(10,['*'],'page', $request->page);

        return $this->responseSuccess($user);
    } 

    public function updateRole(Request $request) {
        $user = User::find($request->id);
        $user->role_admin = $request->role;
        $user->save();
        return $this->responseSuccess();
    } 

    public function deleteUser(Request $request) {
        $user = User::find($request->id);
        File::delete(public_path("uploads/user/".$user->image));
        $user->delete();
        return $this->responseSuccess();
    }

    public function createUser(UserAdminRequest $request) {
        $validated = $request->validated();
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->role_admin = $request->role;
        $user->role = 2;
        $user->image = '';
        $user->phone = '';
        $user->save();
        return $this->responseSuccess();
    }
}
