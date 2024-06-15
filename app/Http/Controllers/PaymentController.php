<?php

namespace App\Http\Controllers;

use App\Models\Checkout;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function vn_pay(Request $request)
    {
        // Lấy giá trị totalPrice từ request
        $totalPrice = $request->input('totalPrice');

        // Các thông tin cần thiết cho thanh toán VNPAY
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = "http://localhost:3000/";
        $vnp_TmnCode = "PIEV8J2S"; // Mã website tại VNPAY 
        $vnp_HashSecret = "O3AEPIM3G0LHTBLP558JNEA5LHGF2UBT"; // Chuỗi bí mật

        // Mã đơn hàng là totalPrice
        $vnp_TxnRef = $totalPrice;

        // Các thông tin đơn hàng khác
        $vnp_OrderInfo = "Thanh toán hóa đơn";
        $vnp_OrderType = "Website";
        $vnp_Amount = $totalPrice * 100; // Số tiền cần thanh toán (phải nhân cho 100 vì VNPAY yêu cầu số tiền phải là số nguyên)

        // Các thông tin còn lại
        $vnp_Locale = "VN";
        $vnp_BankCode = "NCB";
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

        // Dữ liệu gửi đi
        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        ];

        // Bổ sung mã ngân hàng nếu có
        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        // Sắp xếp dữ liệu và tạo chuỗi hash
        ksort($inputData);
        $query = "";
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            $hashdata .= urlencode($key) . "=" . urlencode($value) . "&";
            $query .= urlencode($key) . "=" . urlencode($value) . "&";
        }

        // Tạo URL thanh toán và thêm chuỗi hash bảo mật
        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        // Lưu dữ liệu vào bảng Checkout
        $checkout = Checkout::create([
            'user_id' => $request->input('user_id'),
            'total_price' => $totalPrice,
        ]);

        // Lưu dữ liệu vào bảng Order
        $orderCode = Str::lower(Str::random(9));
        foreach ($request->input('cartItems') as $cartItem) {
            Order::create([
                'code' => $orderCode,
                'checkout_id' => $checkout->id,
                'product_id' => $cartItem['id'],
                'quantity' => $cartItem['quantity'],
                'total_price' => $cartItem['price'],
            ]);
        }

        // Dữ liệu trả về cho frontend
        $returnData = [
            'code' => '00',
            'message' => 'success',
            'data' => $vnp_Url
        ];

        // Nếu có yêu cầu redirect từ form frontend
        if ($request->has('redirect')) {
            header('Location: ' . $vnp_Url);
            die();
        } else {
            echo json_encode($returnData);
        }
    }
}
