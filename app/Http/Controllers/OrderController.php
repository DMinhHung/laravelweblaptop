<?php

namespace App\Http\Controllers;

use App\Models\Checkout;
use App\Models\Order;
use App\Models\ShoppingCart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index()
    {
        $cartItems = Checkout::with(['order', 'user'])->get();

        return response()->json($cartItems);
    }
    public function billsdetail($checkoutId)
    {
        $order = Order::with('checkout')
            ->join('products', 'order.product_id', '=', 'products.id')
            ->select('order.*', 'products.name', 'products.price', 'products.quantity as product_quantity' , 'products.img')
            ->where('order.checkout_id', $checkoutId)
            ->get();

        return response()->json($order);
    }
}
