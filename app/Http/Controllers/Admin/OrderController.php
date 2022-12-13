<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Order_detail;
use App\Models\Product;
use App\Models\Voucher;
use App\Models\Profile;
use Storage;

class OrderController extends Controller
{
    public function getOrder(Request $request) {
        $start_date = $request->date_start;
        $end_date = $request->date_end;
        $key_search = $request->key_search;
        $province_id = $request->p_code;
        $district_id = $request->d_code;
        $ward_id = $request->v_code;
        $status = '';
        if ($request->status === 'order_create') {
            $status = 1;
        } else if ($request->status === 'order_receive') {
            $status = 2;
        } else if ($request->status === 'order_delivery') {
            $status = 3;
        } else if ($request->status === 'order_finish') {
            $status = 4;
        } else if ($request->status === 'order_cancel') {
            $status = 5;
        }
        $order = DB::table('order')
        ->join('profile', 'profile.id', '=', 'order.id')
        ->when($start_date, function($query, $start_date) {
            $query->whereBetween('order.order_time', [$start_date, $end_date]);
        })
        ->where(function ($query) use($key_search) {
            $query->where('order.code', 'like' , "%$key_search%")
            ->orWhere('profile.phone', 'like' , "%$key_search%")
            ->orWhere('profile.name', 'like' , "%$key_search%");
        })
        ->where('profile.province_id', 'like', "%$province_id%")
        ->where('profile.district_id', 'like', "%$district_id%")
        ->where('profile.ward_id', 'like', "%$ward_id%")
        ->where('order.action', 'like', $status)
        ->paginate(10,['*'],'page', $request->page);
        return $this->responseSuccess($order);   
    }
    public function detailOrder(Request $request) {
        $order = Order::find($request->id);
        $voucher = Voucher::where('id', $order->voucher_id)->first();
        $profile = Profile::where('order_id', $order->id)->first();
        $orderDetail = Order_detail::where('order_detail.order_id', $request->id)
        ->join('product', 'product.id', '=', 'order_detail.product_id')->get();

        $sum_price = 0;
        foreach($orderDetail as $od) {
            $od->image = config('app.linkImage'). '/uploads/product/' . $od->image;
            if(isset($od->discount)) {
                $sum_price += ($od->price - (($od->discount /100) * $od->price)) * $od->quantity;
            } else {
                $sum_price += $od->discount;
            }
        }

        $discount_voucher = 0;
        if ($voucher) {
            if (($sum_price * ($voucher->percentage / 100)) > $voucher->discount_amount) {
                $discount_voucher = $voucher->discount_amount;
            } else {
                $discount_voucher = $sum_price * ($voucher->percentage / 100);
            }
        }
        return $this->responseSuccess(['order' => $order, 'voucher' => $voucher, 'orderDetail' => $orderDetail, 'profile' => $profile, 'sum_price'=>$sum_price, 'discount_price' => $discount_voucher]);
    }
    public function changeAction(Request $request) {
        $order = Order::find($request->id);
        $action = '';
        $status = '';
        if ($request->action === 1) {
            $action = 2;
            $status = 'order_receive';
        } else if ($request->action === 2) {
            $action = 3;
            $status = 'order_delivery';
        } else if ($request->action === 3) {
            $action = 4;
            $status = 'order_finish';
        }
        $order->action = $action;
        $order->save();
        return $this->responseSuccess(['status' => $status]);
    }
    public function cancelOrder(Request $request) {
        $order = Order::find($request->id);
        $order->action = 5;
        $order->save();
        return $this->responseSuccess();
    }
}
