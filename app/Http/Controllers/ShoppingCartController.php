<?php

namespace App\Http\Controllers;

use App\Models\Checkout;
use App\Models\Order;
use App\Models\ShoppingCart;
use App\Models\Product; // Import model Product
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ShoppingCartController extends Controller
{
    public function index()
    {
        $cartItems = ShoppingCart::with('product', 'user')->get();

        $numberOfItemsInCart = $cartItems->count();

        return response()->json($cartItems);
    }
    public function addToCart(Request $request)
    {
        $request->validate([
            'productId' => 'required|exists:products,id',
            'userId' => 'required|exists:users,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $productId = $request->input('productId');
        $userId = $request->input('userId');
        Log::info($userId);
        $existingCartItem = ShoppingCart::where('user_id', $userId)
            ->where('products_id', $productId)
            ->first();

        if ($existingCartItem) {
            return response()->json(['message' => 'Product already in cart'], 400);
        }

        try {
            $quantity = $request->input('quantity');
            $price = $request->input('price');
            $totalPrice = $price * $quantity;

            ShoppingCart::create([
                'user_id' => $userId,
                'products_id' => $productId,
                'quantity' => $quantity,
                'total_price' => $totalPrice,
            ]);

            return response()->json(['message' => 'Product added to cart successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to add product to cart', 'error' => $e->getMessage()], 500);
        }
    }
    public function updateCart(Request $request)
    {
        try {
            $cartItems = $request->input('cartItems');

            foreach ($cartItems as $cartItem) {
                $id = $cartItem['id'];
                $quantity = $cartItem['quantity'];
                $price = $cartItem['price'];
                $shoppingCart = ShoppingCart::findOrFail($id);
                $shoppingCart->quantity = $quantity;
                $shoppingCart->total_price = $price;
                $shoppingCart->save();
            }
            return response()->json(['message' => 'Cart updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update cart', 'error' => $e->getMessage()], 500);
        }
    }
    public function deleteFromCart($id)
    {
        $cartItem = ShoppingCart::findOrFail($id);

        $cartItem->delete();

        return response()->json(['message' => 'Product removed from cart successfully'], 200);
    }

    public function checkout(Request $request)
    {
        Log::info($request->all());
        $userId = $request->input('user_id');

        if (!$userId || !User::find($userId)) {
            return response()->json(['message' => 'Invalid user ID'], 400);
        }
        $cartItems = ShoppingCart::where('user_id', $userId)->get();
        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }
        $totalPrice = $cartItems->sum('total_price');
        $orderCode = Str::lower(Str::random(9));
        $checkoutphone = $request->input('phone');
        $checkoutaddress = $request->input('address');
        Log::info($checkoutphone);
        Log::info($checkoutaddress);
        // dd();
        $order = Checkout::create([
            'user_id' => $userId,
            'total_price' => $totalPrice,
            'orderstatus_id' => 1,
            'phone' => $checkoutphone,
            'address' => $checkoutaddress,
        ]);
 

        foreach ($cartItems as $cartItem) {
            Order::create([
                'code' => $orderCode,
                'checkout_id' => $order->id,
                'product_id' => $cartItem->products_id,
                'quantity' => $cartItem->quantity,
                'total_price' => $cartItem->total_price,
            ]);
        }


        ShoppingCart::where('user_id', $userId)->delete();
        
        return response()->json(['message' => 'Order placed successfully', 'order_id' => $order->id], 200);
    }
}
