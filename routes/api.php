<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\ResetPasswordController;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('home-slide', [HomeController::class, 'homeSlide'])->name('api.home.slide');
Route::get('home-product', [HomeController::class, 'homeProduct'])->name('api.home.product');
Route::get('product-discount', [HomeController::class, 'productDiscount'])->name('api.home.discount');
Route::get('product-selling', [HomeController::class, 'productSelling'])->name('api.home.selling');
Route::post('product-detail', [HomeController::class, 'productDetail'])->name('api.home.productdetail');
Route::get('category', [HomeController::class, 'category'])->name('api.home.category');
Route::get('brand', [HomeController::class, 'brand'])->name('api.home.brand');
// Route::post('add-to-cart', [HomeController::class, 'addToCart'])->name('api.home.addtocart');
Route::post('payment', [HomeController::class, 'payment'])->name('api.home.payment');
Route::get('return-vnpay', [HomeController::class, 'returnVnpay'])->name('api.home.returnvnpay');
Route::get('return-momo', [HomeController::class, 'returnMomo'])->name('api.home.returnMomo');
Route::get('list-voucher', [HomeController::class, 'listVoucher'])->name('api.home.listvoucher');
Route::post('check-voucher', [HomeController::class, 'checkVoucher'])->name('api.home.checkvoucher');
Route::post('category-product', [HomeController::class, 'categoryProduct'])->name('api.home.categoryproduct');
Route::post('rating', [HomeController::class, 'rating'])->name('api.home.rating');
Route::post('comment', [HomeController::class, 'comment'])->name('api.comment');
Route::post('info-order', [HomeController::class, 'infoOrder'])->name('api.infoOrder');

Route::post('reset-password', [ResetPasswordController::class, 'sendMail']);
Route::post('reset', [ResetPasswordController::class, 'reset']);


//Login
Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/register', [AuthController::class, 'register']);
Route::group(['middleware' => 'jwt.auth'], function(){
  Route::post('auth/logout', [AuthController::class, 'logout']);
  Route::get('auth/user', [AuthController::class, 'user']);
  Route::post('auth/update-user', [AuthController::class, 'updateUser']);
  Route::post('add-to-cart', [HomeController::class, 'addToCart'])->name('api.home.addtocart');
  Route::post('get-cart', [HomeController::class, 'getCart'])->name('api.getcart');
  Route::post('user-rate', [HomeController::class, 'userRate'])->name('api.userRate');
});
Route::group(['middleware' => 'jwt.refresh'], function(){
  Route::get('auth/refresh', [AuthController::class, 'refresh']);
});
