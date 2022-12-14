<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\OrderRequest;
use App\Models\Slide;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Productimage;
use App\Models\Rate;
use App\Models\Wishlist;
use Carbon\Carbon;
use App\Models\Order_detail;
use App\Models\Order;
use App\Models\Voucher;
use App\Models\UserVoucher;
use App\Models\Profile;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\ProductComment;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use JWTAuth;

class HomeController extends Controller
{
    public function homeSlide() {
        $slide = Slide::where(['status'=> 1])->orderBy('id', 'DESC')->limit(3)->get();
        $banner = Slide::where(['status'=> 0])->orderBy('id', 'DESC')->limit(4)->get();

        foreach($slide as $sl) {
            $sl->image = config('app.linkImage'). '/uploads/slide/' . $sl->image;
        }
        foreach($banner as $sl) {
            $sl->image = config('app.linkImage'). '/uploads/slide/' . $sl->image;
        }
        return $this->responseSuccess(['slide' => $slide, 'banner' => $banner]);
    }

    public function homeBrand() {
        $brand = Brand::all();
        foreach($brand as $br) {
            $br->image = config('app.linkImage'). '/uploads/brand/' . $br->image;
        }
        return $this->responseSuccess($brand);
    }

    public function homeProduct() {
        $product = Product::orderBy('id', 'DESC')->limit(8)->get();
        foreach($product as $pr) {
            $pr->image = config('app.linkImage'). '/uploads/product/' . $pr->image;
        }

        return $this->responseSuccess(['product' => $product]);
    }

    public function productDiscount() {
        $productDiscount = Product::whereNotNull('discount')->orderBy('id', 'DESC')->limit(8)->get();
        foreach($productDiscount as $pr) {
            $pr->image = config('app.linkImage'). '/uploads/product/' . $pr->image;
        }

        return $this->responseSuccess(['productDiscount' => $productDiscount]);
    }

    public function productSelling() {
        $productSelling = Product::orderBy('selling', 'DESC')->limit(8)->get();
        foreach($productSelling as $pr) {
            $pr->image = config('app.linkImage'). '/uploads/product/' . $pr->image;
        }

        return $this->responseSuccess(['productSelling' => $productSelling]);
    }

    public function category() {
        $category = Category::all();

        return $this->responseSuccess($category);
    }

    public function brand() {
        $brand = Brand::all();
        foreach($brand as $br) {
            $br->image =  config('app.linkImage'). '/uploads/brand/' . $br->image;
        }
        return $this->responseSuccess($brand);
    }

    public function productDetail(Request $request) {
        $product = Product::findOrFail($request->id);
        $product->image = config('app.linkImage'). '/uploads/product/' . $product->image;

        $brand = Brand::where('id', $product->brand_id)->first();
        $image = Productimage::where('product_id', $request->id)->get();
        $category = Category::where('id', $product->category_id)->first();
        $rateSum = Rate::where('product_id', $product->id)->avg('rate_scores');
        $rateCount = Rate::where('product_id', $product->id)->count();
        $checkWarehouse = true;
        $warehouse = Warehouse::where('product_id', $product->id)->first();
        if ($warehouse->quantity === 0) {
            $checkWarehouse = false;
        }
        $rate = [
            'sum' => $rateSum,
            'count' => $rateCount
        ];
        
        $arr_img = [];
        array_push($arr_img, $product->image);
        foreach($image as $img) {
            $img->product_image_name = config('app.linkImage'). '/uploads/product_image/'.$img->product_image_name;
            array_push($arr_img, $img->product_image_name);
        }

        $params = [
            'brand' => $brand->name,
            'product' => $product,
            'product_image' => $arr_img,
            'category' => ['name' => $category->name, 'id' => $category->id],
            'rate' => $rate,
            'warehouse' => $checkWarehouse
        ];

        return $this->responseSuccess($params);
    }


    // th??m v??o gi??? h??ng
    public function addToCart(Request $request) {
        if ($request->product_id === null && $request->quantity === null) {
            $getCart = $this->getCartOrder($request->user_id);
            return $this->responseSuccess(['carts' => $getCart['wishlists'], 'sum_quantity' => $getCart['sum_quantity'], 'sum_price' => $getCart['sum_price']]);
        } else {
            if ($request->type === 'delete') {
                $order = Order::where('user_id', $request->user_id)->whereNull('action')->first();
                $wishlist = Wishlist::where('product_id', $request->product_id)->where('user_id', $request->user_id)->first();
                $orderDetail = Order_Detail::where('product_id', $request->product_id)->where('order_id', $order->id)->first();

                $wishlist->delete();
                $orderDetail->delete();


                $getCart = $this->getCartOrder($request->user_id);
                return $this->responseSuccess(['carts' => $getCart['wishlists'], 'sum_quantity' => $getCart['sum_quantity'], 'sum_price' => $getCart['sum_price']]);

            } else {
                $product = Product::find($request->product_id);

                if (!$product) {
                    return json_encode([
                        'status' => false,
                        'msg' => 'S???n ph???m kh??ng t???n t???i.',
                    ]);
                }

                $currentWishlist = Wishlist::where('user_id', $request->user_id)->first();
                if(!$currentWishlist) {
                    //Tr?????ng h???p ch??a c?? wishlist th?? t???o wishlist m???i
                    try{
                        $wishlistOrder = [
                            'user_id' => $request->user_id,
                            'product_id' => $request->product_id,
                            'quantity' => $request->quantity,
                        ];
                        $wishlistOrder = Wishlist::create($wishlistOrder);
                    } catch(\Throwable $th) {
                        Log::info('th??m th???t b???i');
                        Log::info($th);
                    }
                } else {
                    //Tr?????ng h???p wishlist ???? t???n t???i
                    $currentWishlistOrder = Wishlist::where('product_id', $request->product_id)->where('user_id', $request->user_id)->first();

                    try{
                        if(!$currentWishlistOrder) {
                            //tr?????ng h???p wishlist ???? t???n t???i nh??ng product ch??a t???n t???i
                            $wishlist = Wishlist::where('user_id', $request->user_id)->first();
                            $order_detail = [
                                'user_id' => $request->user_id,
                                'product_id' => $request->product_id,
                                'quantity' => $request->quantity,
                            ];

                            $wishlistOrder = Wishlist::create($order_detail);

                        } else {
                            // tr?????ng h???p wishlist v?? product ???? t???n t???i
                            if ($request->type === 'update') {
                                $currentWishlistOrder->quantity = $request->quantity;
                                $currentWishlistOrder->save();
                            } else {
                                $currentWishlistOrder->quantity += $request->quantity;
                                $currentWishlistOrder->save();
                            }
                        }
                    } catch(\Throwable $th) {
                        Log::info('l???i');
                        Log::info($th);
                    }
                }

                // t???o order v?? order_deltail m???i
                $orderData1 = [
                    'user_id' => $request->user_id,
                ];


                $order = Order::where('user_id', $request->user_id)->whereNull('action')->first();
                $wishlist = Wishlist::where('product_id', $request->product_id)->first();
                if(!$order) {
                    try {
                        $order1 = Order::create($orderData1);
                        $productOrderDetail = [
                            'order_id' => $order1->id,
                            'product_id' => $request->product_id,
                            'quantity' => $wishlist->quantity,
                            'detail_amount' => !is_null($product->discount) ? ($product->price - (($product->discount /100) * $product->price)) * $wishlist->quantity : $product->price * $wishlist->quantity
                        ];
                        $orderDetail = Order_detail::create($productOrderDetail);
                    } catch (\Throwable $th) {
                        Log::info('l???i');
                        Log::info($th);
                    }
                } else {
                    //tr?????ng h???p t???n t???i order v?? product
                    $order1 = Order::where('user_id', $request->user_id)->whereNull('action')->first();
                    $orderDetail = Order_detail::where('product_id', $request->product_id)->where('order_id', $order1->id)->first();
                    $wishlist = Wishlist::where('product_id', $request->product_id)->first();

                    try{
                        if(!$orderDetail) {
                            $productOrderDetail = [
                                'order_id' => $order->id,
                                'product_id' => $product->id,
                                'quantity' => $wishlist->quantity,
                                'detail_amount' => !is_null($product->discount) ? $product->price - (($product->discount /100) * $product->price) * $wishlist->quantity : $product->price * $wishlist->quantity,
                            ];
                            $orderDetail = Order_detail::create($productOrderDetail);
                        } else {
                            if ($request->type === 'update') {
                                $orderDetail->quantity = $request->quantity;
                            } else {
                                $orderDetail->quantity += $request->quantity;
                            }
                            $orderDetail->detail_amount = !is_null($product->discount) ? ($product->price - (($product->discount /100) * $product->price)) * $wishlist->quantity : $product->price * $wishlist->quantity;
                            $orderDetail->save();
                        }
                    } catch(\Throwable $th) {
                        Log::info('l???i');
                        Log::info($th);
                    }
                }


                $getCart = $this->getCartOrder($request->user_id);
                return $this->responseSuccess(['carts' => $getCart['wishlists'], 'sum_quantity' => $getCart['sum_quantity'], 'sum_price' => $getCart['sum_price']]);
            }
        }
    }

    public function payment(OrderRequest $request) {
        $validated = $request->validated();

        $order = Order::where('user_id', $request->user_id)->where('code', null)->first();
        $orderDetail = Order_detail::where('order_id', $order->id)->get();
        //check s??? l?????ng t???n kho
        foreach($orderDetail as $od) {
            $product = Product::where('id', $od->product_id)->first();
            $warehouse = Warehouse::where('product_id', $od->product_id)->first();
            if ($od->quantity > $warehouse->quantity) {
                return $this->responseError('S???n ph???m '. $product->name . ' hi???n t???i ???? h???t h??ng ho???c kh??ng ????? s??? l?????ng, vui l??ng ch???n s???n ph???m t????ng t??? kh??c');
            }
        }
        if ($request->type === 'vnpay') {
            // session(['url_prev' => url()->previous()]);
            $vnp_TmnCode = "2W0TX27O"; //M?? website t???i VNPAY
            $vnp_HashSecret = "OVCTODOGEIHQBJVOYXXDCZIVPPEWBVSG"; //Chu???i b?? m???t
            $vnp_Url = "http://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
            $vnp_Returnurl = env('APP_URL'). "/api/return-vnpay";
            $vnp_TxnRef = date("YmdHis"); //M?? ????n h??ng. Trong th???c t??? Merchant c???n insert ????n h??ng v??o DB v?? g???i m?? n??y sang VNPAY
            $vnp_OrderInfo = "Thanh to??n h??a ????n ph?? dich v???";
            $vnp_OrderType = 'billpayment';
            $vnp_Amount = $request->total * 100;
            $vnp_Locale = 'vn';
            $vnp_IpAddr = request()->ip();

            $inputData = array(
                "vnp_Version" => "2.0.0",
                "vnp_TmnCode" => $vnp_TmnCode,
                "vnp_Amount" => $vnp_Amount,
                "vnp_Command" => "pay",
                "vnp_CreateDate" => date('YmdHis'),
                "vnp_CurrCode" => "VND",
                "vnp_IpAddr" => $vnp_IpAddr,
                "vnp_Locale" => $vnp_Locale,
                "vnp_OrderInfo" => json_encode($request->all()),
                "vnp_OrderType" => $vnp_OrderType,
                "vnp_ReturnUrl" => $vnp_Returnurl,
                "vnp_TxnRef" => $vnp_TxnRef,
            );
            if (isset($vnp_BankCode) && $vnp_BankCode != "") {
                $inputData['vnp_BankCode'] = $vnp_BankCode;
            }
            ksort($inputData);
            $query = "";
            $i = 0;
            $hashdata = "";
            foreach ($inputData as $key => $value) {
                if ($i == 1) {
                    $hashdata .= '&' . $key . "=" . $value;
                } else {
                    $hashdata .= $key . "=" . $value;
                    $i = 1;
                }
                $query .= urlencode($key) . "=" . urlencode($value) . '&';
            }

            $vnp_Url = $vnp_Url . "?" . $query;
            if (isset($vnp_HashSecret)) {
                $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret);//
                $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
            }
            return $this->responseSuccess($vnp_Url);
        }

        if ($request->type === 'shipcode') {
            $this->saveOrder($request->all(), '');

            return $this->responseSuccess(['success' => '?????t h??ng th??nh c??ng']);
        }

        if ($request->type === 'momo') {
            $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
            $partnerCode = 'MOMOBKUN20180529';
            $accessKey = 'klm05TvNBzhg7h7j';
            $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
            $orderInfo = "Thanh to??n qua MoMo";
            $amount = $request->total;
            $orderId = time() ."";
            $redirectUrl = env('APP_URL'). "/api/return-momo";
            $ipnUrl = env('APP_URL'). "/api/return-momo";
            $extraData = serialize(json_encode($request->all()));
            
            $requestId = time() . "";
            $requestType = "payWithATM";
            //before sign HMAC SHA256 signature
            $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
            $signature = hash_hmac("sha256", $rawHash, $secretKey);
            $data = array('partnerCode' => $partnerCode,
                'partnerName' => "Notro.vn",
                "storeId" => "Noitro.vn",
                'requestId' => $requestId,
                'amount' => $amount,
                'orderId' => $orderId,
                'orderInfo' => $orderInfo,
                'redirectUrl' => $redirectUrl,
                'ipnUrl' => $ipnUrl,
                'lang' => 'vi',
                'extraData' => $extraData,
                'requestType' => $requestType,
                'signature' => $signature);
            $result = $this->execPostRequest($endpoint, json_encode($data));
            $jsonResult = json_decode($result, true);  // decode json
            return $this->responseSuccess($jsonResult['payUrl']);
        }
    }

    public function returnVnpay(Request $request)
    {   
        if($request->vnp_ResponseCode == "00") {
            $this->saveOrder($request->vnp_OrderInfo, 'gate');
            return view('payment', ['payment' => $request->all()]);
        }
        // return redirect($url)->with('errors' ,'L???i trong qu?? tr??nh thanh to??n ph?? d???ch v???');
    }

    public function returnMomo(Request $request)
    {
        if($request) {
            $request->extraData = unserialize($request->extraData);
            $this->saveOrder($request->extraData, 'gate');
            return view('momo', ['payment' => $request->extraData]);
        }
    }

    public function listVoucher() {
        $voucher = Voucher::orderBy('id', 'desc')->take(4)->get();
        Log::info($voucher);
        $params = [];
        $status = true;
        foreach($voucher as $vc) {
            if(Carbon::now() <= $vc->expires_at) {
                $status = true;
            } else {
                $status = false;
            }
            $newData = [
                "id" => $vc->id,
                "code" => $vc->code,
                "name" => $vc->name,
                "image" => config('app.linkImage'). '/uploads/voucher/' . $vc->image,
                "start_date" => $vc->starts_at,
                "end_date" => $vc->expires_at,
                "minimum_order" => $vc->minimum_order,
                "description" => $vc->description,
                "quantity" => $vc->max_uses_user,
                "discount_amount" => $vc->discount_amount,
                "status" => $status
            ];
            array_push($params, $newData);
        }
        return $this->responseSuccess($params);
    }

    public function checkVoucher(Request $request) {
        if ($request->code_voucher == '') {
            $params = 'B???n ch??a ch???n voucher';
            return $this->responseError($params);
        } else {
            $voucher = Voucher::where('code', $request->code_voucher)->first();
            if (!$voucher) {
                $params = 'Voucher b???n nh???p kh??ng t???n t???i';
                return $this->responseError($params);
            } else {
                $userVoucher = UserVoucher::where('user_id', $request->user_id)->where('voucher_id', $voucher->id)->get();
                if (count($userVoucher) >= $voucher->max_uses_user) {
                    $params = 'Voucher b???n nh???p ???? s??? d???ng qu?? l???n s??? d???ng';
                    return $this->responseError($params);
                } else {
                    if(Carbon::now() >= $voucher->expires_at) {
                        $params = 'Voucher b???n nh???p qu?? h???n s??? d???ng';
                        return $this->responseError($params);
                    } else {
                        if(Carbon::now() <= $voucher->starts_at) {
                            $params = 'Voucher b???n nh???p ch??a ?????n ng??y s??? d???ng';
                            return $this->responseError($params);
                        } else {
                            if ($voucher->uses === 0) {
                                $params = 'Voucher ???? h???t l?????t s??? d???ng';
                                return $this->responseError($params);
                            }
                            else {
                                if ($request->price < $voucher->minimum_order) {
                                    $params = '????n h??ng c???a b???n ch??a ?????t gi?? tr??? t???i thi???u';
                                    return $this->responseError($params);
                                } else {
                                    $totalDiscount = $request->price * ($voucher->percentage * 0.01);
                                    if ($totalDiscount > $voucher->discount_amount) {
                                        $params = [
                                            'discount_price' => $voucher->discount_amount,
                                            'voucher_id' => $voucher->id
                                        ];
                                        return $this->responseSuccess($params);
                                    } else {
                                        $params = [
                                            'discount_price' => $totalDiscount,
                                            'voucher_id' => $voucher->id
                                        ];
                                        return $this->responseSuccess($params);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        // $params = 'test';
        // return $this->responseError($params);
    }

    public function categoryproduct(Request $request) {
        $orderby = '';
        $valueOrder = '';
        if($request->arrange[0] === 'az') {
            $orderby = 'name';
            $valueOrder = 'asc';
        } else if ($request->arrange[0] === 'za') {
            $orderby = 'name';
            $valueOrder = 'desc';
        } else if ($request->arrange[0] === 'plus') {
            $orderby = 'price';
            $valueOrder = 'asc';
        }else if ($request->arrange[0] === 'reduction') {
            $orderby = 'price';
            $valueOrder = 'desc';
        }
        $search = $request->search;
        $category = $request->category_id;
        $product = DB::table('product')
                    ->when(isset($search), function ($query) use ($search) {
                        return $query->where('name', 'like', "%$search%");
                    })
                    ->when(isset($category), function ($query) use ($category) {
                        return $query->where('category_id', $category);
                    })
                    ->whereBetween('price', [$request->total[0],$request->total[1]])
                    ->whereIn('brand_id', $request->brand)
                    ->orderBy($orderby, $valueOrder)
                    ->limit(8)
                    ->get();
        foreach($product as $pr) {
            $pr->image = config('app.linkImage'). '/uploads/product/' . $pr->image;
        }
        $dataCategory='';
        if($category) {
            $dataCategory = Category::find($category);
        }

        return $this->responseSuccess(['product' => $product, 'category' => $dataCategory]);
    }

    public function saveOrder($request, $gate) {
        if ($gate) {
            $request = json_decode($request, true);
        }
        $order = Order::where('user_id', $request['user_id'])->where('action', null)->first();
        // tr??? s??? l?????ng ???? mua trong kho
        $orderDetail = Order_detail::where('order_id', $order->id)->get();
        foreach($orderDetail as $od) {
            $warehouse = Warehouse::where('product_id', $od->product_id)->first();
            $warehouse->quantity -= $od->quantity;
            $warehouse->save();
        }
        // l??u order
        $paramsOrder = [
            'order_time' => Carbon::now('Asia/Ho_Chi_Minh'),
            'order_total_money' => $request['total'],
            'pay_ship' => $request['pay_ship'],
            'action' => 1,
            'voucher_id' => $request['voucher_id'],
            'is_payment' => $gate ? 0 : 1,
            'code' => time() . '_' . Str::random(4)
        ];
        $updateOrder = Order::where('user_id', $order->user_id)->where('action', null)->update($paramsOrder);

        //l??u th??ng tin v???n chuy???n
        $paramsInfo = [
            'order_id' => $order->id,
            'name' => $request['name'],
            'phone' => $request['phone'],
            'email' => $request['email'],
            'province_id' => $request['province'],
            'district_id' => $request['district'],
            'ward_id' => $request['ward'],
            'note' => isset($request['note']) ? $request['note'] : ''
        ];
        $info = Profile::create($paramsInfo);

        if ($request['voucher_id']) {

            //l??u ng?????i s??? d???ng voucher
            $paramsUserVoucher = [
                'user_id' => $request['user_id'],
                'voucher_id' => $request['voucher_id']
            ];
            $userVoucher = UserVoucher::create($paramsUserVoucher);

            //c???p nh???t l???i s??? l?????t s??? d???ng voucher

            $voucher = Voucher::find($request['voucher_id']);
            $voucher->uses = $voucher->uses - 1;
            $voucher->save();
        }

        // x??a wishlist
        $wishlist = Wishlist::where('user_id', $request['user_id'])->delete();
    }

    public function getCart(Request $request) {
        $status = $request->status;
        $order = Order::where('user_id', $request->user_id)
                        ->when(isset($status), function ($query) use ($status) {
                            return $query->where('action', 'like', "%$status%");
                        })
                        ->orderBy('id', 'desc')
                        ->get();
        $array = [
            "order" => '',
            "detail_order" => ''
        ];
        $arrayTotal = [];
        if ($order) {
            $voucherData = [];
            foreach ($order as $key => $or) {
                $voucherData = Voucher::where('id', $or->voucher_id)->first();
                $arr = [];
                $sum = 0;
                $detail = Order_detail::where('order_id', $or->id)->get();
                foreach($detail as $dt) {
                    $userRate = false;
                    $product = Product::where('id',$dt->product_id)->first();
                    $rate = Rate::where('user_id', $request->user_id)->where('product_id', $product->id)->first();
                    if ($rate === null) {
                        $userRate = true;
                    }
                    $sum = $sum + $dt->detail_amount;
                    $params = [
                        'order_detail_id' => $dt->id,
                        'quantity' => $dt->quantity,
                        'detail_amount' => $dt->detail_amount,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'product_image' => config('app.linkImage'). '/uploads/product/' . $product->image,
                        'rate' => $userRate
                    ];
                    array_push($arr, $params);
                }

                $voucher = 0;
                if ($voucherData) {
                    if ($sum > $voucherData->discount_amount) {
                        $voucher = $voucherData->discount_amount;
                    } else {
                        $voucher = $totalDiscount;
                    }
                }

                $action = '';
                if ($or->action === 1) {
                    $action = 'Ch??? x??c nh???n';
                } 
                if ($or->action === 2) {
                    $action = 'Ch??? l???y h??ng';
                } 
                if ($or->action === 3) {
                    $action = '??ang giao h??ng';
                } 
                if ($or->action === 4) {
                    $action = 'Giao th??nh c??ng';
                } 
                if ($or->action === 5) {
                    $action = '???? h???y';
                }

                $dataOrder = [
                    'code' => $or->code,
                    'pay_ship' => $or->pay_ship,
                    'order_total_money' => $or->order_total_money,
                    'order_time' => $or->order_time,
                    'voucher' => $voucher,
                    'action' => $action
                ];

                array_push($arrayTotal, [$array['order'] = $dataOrder, $array['detail_order'] = $arr]);
            }
            return $this->responseSuccess( $arrayTotal);
        }
    }

    public function getCartOrder($request) {
        $wishlists = Product::select('product.*', 'wishlist.quantity as quantity')
            ->join('wishlist','wishlist.product_id','=','product.id')
            ->where('wishlist.user_id', $request)->orderBy('wishlist.id','asc')->get();

        foreach($wishlists as $pr) {
            $pr->image = config('app.linkImage'). '/uploads/product/' . $pr->image;
        }

        $sum_quantity = 0;
        foreach($wishlists as $key=>$value){
            if(isset($value->quantity)) {
                $sum_quantity += $value->quantity;
            }
        }
        $sum_price = 0;
        foreach($wishlists as $key=>$value){
            if(isset($value->discount)) {
                $sum_price += ($value->price - (($value->discount /100) * $value->price)) * $value->quantity;
            } else {
                $sum_price += $value->discount;
            }
        }

        $params = [
            'wishlists' => $wishlists,
            'sum_quantity' => $sum_quantity,
            'sum_price' => $sum_price
        ];

        return $params;
    }

    public function rating(Request $request) {
        $user = $request->user_id;
        $rate = DB::table('rate')
            ->where('rate.product_id', $request->product_id)
            ->orderByRaw("CASE WHEN rate.user_id = '$user' then 1 END DESC")
            ->paginate(5);
       if ($rate['data']) {
           $rate->getCollection()->transform(function ($value) {
               $user = User::where('id', $value->user_id)->first();

               return $params = [
                   'rate_id' => $value->id,
                   'user_id' => $value->user_id,
                   'rate_scores' => $value->rate_scores,
                   'rate_comment' => $value->rate_comment,
                   'date' => $value->created_at,
                   'name' => $user->name,
                   'image' => config('app.linkImage') . '/uploads/user/' . $user->image
               ];
           });
       }
       return $this->responseSuccess($rate);
    }

    public function  comment(Request $request) {
        $user = $request->user_id;
        if ($request->value && $user) {
            $params = [
                "user_id" => $request->user_id,
                "product_id" => $request->product_id,
                "content" => $request->value,
            ];

            $createComment = ProductComment::create($params);
        }
        $comment = ProductComment::where('product_id', $request->product_id)->orderBy('id', 'DESC')->paginate(2);
        $comment->getCollection()->transform(function ($value) {
            $user = User::where('id', $value->user_id)->first();

            return $params = [
                "author" => $user->name,
                "avatar" => config('app.linkImage'). '/uploads/user/' . $user->image,
                "content" => $value->content,
                "datetime" => $value->created_at
            ];
        });
        return $this->responseSuccess($comment);
    }

    public function infoOrder(Request $request) {
        $order = Order::where('code', $request->search)->first();
        if ($order) {
            $info = $order->profile;
            $action = '';
            if ($order->action === 1) {
                $action = 'Ch??? x??c nh???n';
            } 
            if ($order->action === 2) {
                $action = 'Ch??? l???y h??ng';
            } 
            if ($order->action === 3) {
                $action = '??ang giao h??ng';
            } 
            if ($order->action === 4) {
                $action = 'Giao th??nh c??ng';
            } 
            if ($order->action === 5) {
                $action = '???? h???y';
            } 
            $params = [
                'code' => $order->code,
                'name' => $info->name,
                'phone' => $info->phone,
                'date' => $order->order_time,
                'money' => $order->order_total_money,
                'isPayment' => $order->is_payment === 1 ? 'Ch??a thanh to??n' : '???? thanh to??n',
                'action' => $action
            ];
            return $this->responseSuccess($params);
        } else {
            return $this->responseSuccess(['message' => 'Kh??ng t??m th???y th??ng tin ????n h??ng']);
        }
    }

    public function userRate(Request $request) {
        $rate = new Rate;
        $rate->product_id = $request->product_id;
        $rate->rate_scores = $request->rate;
        $rate->rate_comment = $request->comment;
        $rate->user_id = $request->user_id;
        $rate->save();
        return $this->responseSuccess();
    }

    function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data))
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return $result;
    }
}
