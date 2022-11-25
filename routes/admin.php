<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashBoardController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SlideController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VoucherController;


Route::middleware(['middleware' => 'jwt.auth'])->group(function () {
    Route::post('dashboard', [DashBoardController::class, 'dashboard'])->name('admin.dashboard');
    Route::group(['prefix' => 'slide'], function(){
        Route::post('list', [SlideController::class, 'getSlide'])->name('admin.getSlide');
        Route::post('delete', [SlideController::class, 'deleteSlide'])->name('admin.deleteSlide');
        Route::post('create', [SlideController::class, 'createSlide'])->name('admin.createSlide');
        Route::post('update', [SlideController::class, 'updateSlide'])->name('admin.updateSlide');
    });
    Route::group(['prefix' => 'category'], function(){
        Route::post('list', [CategoryController::class, 'getCategory'])->name('admin.getCategory');
        Route::post('delete', [CategoryController::class, 'deleteCategory'])->name('admin.deleteCategory');
        Route::post('create', [CategoryController::class, 'createCategory'])->name('admin.createCategory');
        Route::post('update', [CategoryController::class, 'updateCategory'])->name('admin.updateCategory');
    });
    Route::group(['prefix' => 'brand'], function(){
        Route::post('list', [BrandController::class, 'getBrand'])->name('admin.getBrand');
        Route::post('delete', [BrandController::class, 'deleteBrand'])->name('admin.deleteBrand');
        Route::post('create', [BrandController::class, 'createBrand'])->name('admin.createBrand');
        Route::post('update', [BrandController::class, 'updateBrand'])->name('admin.updateBrand');
    });
    Route::group(['prefix' => 'voucher'], function(){
        Route::post('list', [VoucherController::class, 'getVoucher'])->name('admin.getVoucher');
        Route::post('detail', [VoucherController::class, 'detailVoucher'])->name('admin.detailVoucher');
        Route::post('delete', [VoucherController::class, 'deleteVoucher'])->name('admin.deleteVoucher');
        Route::post('create', [VoucherController::class, 'createVoucher'])->name('admin.createVoucher');
        Route::post('update', [VoucherController::class, 'updateVoucher'])->name('admin.updateVoucher');
    });
    Route::group(['prefix' => 'product'], function(){
        Route::post('list', [ProductController::class, 'getProduct'])->name('admin.getProduct');
        Route::post('detail', [ProductController::class, 'detailProduct'])->name('admin.detailProduct');
        Route::post('delete', [ProductController::class, 'deleteProduct'])->name('admin.deleteProduct');
        Route::post('create', [ProductController::class, 'createProduct'])->name('admin.createProduct');
        Route::post('update', [ProductController::class, 'updateProduct'])->name('admin.updateProduct');
        Route::post('delete-image', [ProductController::class, 'deleteImage'])->name('admin.deleteImage');
    });
    Route::group(['prefix' => 'order'], function(){
        Route::post('list', [OrderController::class, 'getOrder'])->name('admin.getOrder');
        Route::post('detail', [OrderController::class, 'detailOrder'])->name('admin.detailOrder');
        Route::post('change-action', [OrderController::class, 'changeAction'])->name('admin.changeAction');
        Route::post('cancel-order', [OrderController::class, 'cancelOrder'])->name('admin.cancelOrder');
    });
    Route::group(['prefix' => 'import'], function(){
        Route::post('get-product', [ImportController::class, 'getProductImport'])->name('admin.getProductImport');
        Route::post('import-warehouse', [ImportController::class, 'importWarehouse'])->name('admin.importWarehouse');
    });
    Route::group(['prefix' => 'user'], function(){
        Route::post('list', [UserController::class, 'listUser'])->name('admin.listUser');
        Route::post('update-role', [UserController::class, 'updateRole'])->name('admin.updateRole');
        Route::post('delete', [UserController::class, 'deleteUser'])->name('admin.deleteUser');
        Route::post('create', [UserController::class, 'createUser'])->name('admin.createUser');
    });
});

?>
