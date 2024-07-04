<?php

namespace App\Http\Controllers;

use App\Models\Checkout;
use App\Models\Order;
use App\Models\ShoppingCart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function vn_pay(Request $request)
    {
        Log::info($request->all());
        $userId = $request->input("user_id");
        $phone = $request->input("phone");
        $address = $request->input("address");
        $totalPrice = $request->input("totalPrice");
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = route('vnpay.return', ['user_id' => $userId, 'phone' => $phone, 'address' => $address]);
        $vnp_TmnCode = "PIEV8J2S";
        $vnp_HashSecret = "O3AEPIM3G0LHTBLP558JNEA5LHGF2UBT";

        // Mã đơn hàng là totalPrice
        $vnp_TxnRef = $totalPrice;

        // Các thông tin đơn hàng khác
        $vnp_OrderInfo = "Thanh toán hóa đơn";
        $vnp_OrderType = "Website";
        $vnp_Amount = $totalPrice * 100;

        // Các thông tin còn lại
        $vnp_Locale = "VND";
        $vnp_BankCode = "NCB";
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

        $inputData = array(
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
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
            $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        }

        //var_dump($inputData);
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //  
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        $returnData = array(
            'code' => '00', 'message' => 'success', 'data' => $vnp_Url
        );

        if (isset($_POST['redirect'])) {

            header('Location: ' . $vnp_Url);
            die();
        } else {
            echo json_encode($returnData);
        }
    }
    public function vnpayReturn(Request $request)
    {
        Log::info('Callback data: ' . json_encode($request->all()));

        $vnp_ResponseCode = $request->input('vnp_ResponseCode');
        if ($vnp_ResponseCode == '00') {
            $userId = $request->input("user_id");
            $phone = $request->input("phone");
            $address = $request->input("address");

            $cartItems = ShoppingCart::where('user_id', $userId)->get();
            $Checkout = Checkout::where('user_id', $userId)->first();
            Log::info('Cart items: ' . json_encode($cartItems));
            Log::info('Checkout items: ' . json_encode($Checkout));
            if ($cartItems->isEmpty()) {
                return response()->json(['message' => 'Cart is empty'], 400);
            }

            $totalPrice = $cartItems->sum('total_price');

            Log::info($Checkout);
            Log::info($Checkout);
            $orderCode = Str::lower(Str::random(9));
            $checkout = Checkout::create([
                'user_id' => $userId,
                'total_price' => $totalPrice,
                'orderstatus_id' => 1,
                'phone' => $phone,
                'address' => $address,
            ]);


            foreach ($cartItems as $cartItem) {
                Order::create([
                    'code' => $orderCode,
                    'checkout_id' => $checkout->id,
                    'product_id' => $cartItem->products_id,
                    'quantity' => $cartItem->quantity,
                    'total_price' => $cartItem->total_price,
                ]);
            }
            ShoppingCart::where('user_id', $userId)->delete();
            return redirect()->to('http://localhost:3000/');
        } else {
            return redirect()->route('payment.failure');
        }
    }
}
