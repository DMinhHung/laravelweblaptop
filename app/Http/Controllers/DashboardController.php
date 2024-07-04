<?php

namespace App\Http\Controllers;

use App\Models\Checkout;
use App\Models\Product;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function sanpham()
    {
        $totalProducts = Product::count();
        return response()->json(['total_products' => $totalProducts]);
    }

    public function donhang()
    {
        $totalCheckout = Checkout::count();
        return response()->json(['total_checkout' => $totalCheckout]);
    }

    public function tien()
    {
        $totalPrice = Checkout::sum('total_price');
        $today = Carbon::today();
        $totalPriceToday = Checkout::whereDate('created_at', $today)
            ->sum('total_price');
        return response()->json([
            'total_price' => $totalPrice,
            'total_price_today' => $totalPriceToday,
        ]);
    }
}
