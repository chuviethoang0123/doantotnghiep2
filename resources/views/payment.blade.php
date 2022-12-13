<!DOCTYPE html>
<html dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../../assets/images/favicon.png">
    <title>Đơn hàng</title>
</head>

<body>
    <div  style="margin: 0 auto; max-width: 250px; margin-top: 200px">
        <div >
            <div>
                <h3 class="db border-bottom">Thông tin đơn hàng</h3>
                <div style="color: #000000; text-align: left">Tên khách hàng: {{ json_decode($payment['vnp_OrderInfo'])->name }}</div>
                <div style="color: #000000; text-align: left">Số điện thoại: {{ json_decode($payment['vnp_OrderInfo'])->phone }}</div>
                <div style="color: #000000; text-align: left">Email: {{ json_decode($payment['vnp_OrderInfo'])->email }}</div>
                <div style="color: #000000; text-align: left">Phí vận chuyển: {{ json_decode($payment['vnp_OrderInfo'])->pay_ship }} đ</div>
                <div style="color: #000000; text-align: left">Thanh toán: {{ json_decode($payment['vnp_OrderInfo'])->total }} đ</div>
                <div style="color: #000000; text-align: left">Đã thanh toán qua cổng: {{ json_decode($payment['vnp_OrderInfo'])->type }}</div>
            </div>
            <div style="margin-top: 20px;">
                <button><a href="http://webthucpham-dev.vn:2222/#/">Về trang chủ</a></button>
            </div>
        </div>
    </div>

</body>

</html>