<?php

namespace App\Http\Controllers;

use App\Models\Checkout;
use App\Models\Order;
use App\Models\OrdeStatus;
use App\Models\ShoppingCart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index()
    {
        $cartItems = Checkout::with(['order', 'user', 'status'])->get();

        return response()->json($cartItems);
    }
    public function status()
    {
        $status = OrdeStatus::all();
        return response()->json($status);
    }
    public function updateOrderStatus(Request $request, $checkoutId)
    {
        $checkout = Checkout::find($checkoutId);
        if (!$checkout) {
            return response()->json(['message' => 'Checkout not found'], 404);
        }

        // Update the order status
        $checkout->orderstatus_id = $request->input('status_id');
        $checkout->save();

        return response()->json(['message' => 'Order status updated successfully']);
    }
    public function billsdetail($checkoutId)
    {
        $order = Order::with('checkout')
            ->join('products', 'order.product_id', '=', 'products.id')
            ->join('checkout', 'order.checkout_id', '=', 'checkout.id')
            ->join('order_status', 'checkout.orderstatus_id', '=', 'order_status.id')
            ->select('order.*', 'products.name', 'products.price', 'products.quantity as product_quantity', 'products.img', 'order_status.value')
            ->where('order.checkout_id', $checkoutId)
            ->get();

        return response()->json($order);
    }

    public function updateStatus(Request $request, $id)
    {
        $checkout = Checkout::find($id);
        $statusValue = $request->input('status');

        Log::info($statusValue);
        $checkout->orderstatus_id = $statusValue;
        $checkout->save();
        return response()->json(['message' => 'Checkout status updated successfully']);
    }

    public function search(Request $request)
    {
        $term = $request->query('search');

        $orders = Order::where('code', 'like', '%' . $term . '%')->get();

        return response()->json($orders);
    }
}
