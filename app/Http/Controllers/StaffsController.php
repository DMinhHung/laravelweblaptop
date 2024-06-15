<?php

namespace App\Http\Controllers;

use App\Models\ShoppingCart;
use App\Models\Staffs;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StaffsController extends Controller
{
    public function index()
    {
        $staffs = Staffs::get();

        return response()->json($staffs);
    }
}
