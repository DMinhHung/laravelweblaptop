<?php

namespace App\Http\Controllers;

use App\Models\Ratings;
use App\Models\ShoppingCart;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RatingController extends Controller
{
    public function index(Request $request, $id)
    {
        Log::info($id);
        $ratings = Ratings::with('user')->where('product_id', $id)->get();
        Log::info($ratings);
        return response()->json($ratings, 200);
    }
    
    public function ratingReview(Request $request)
    {
        $request->validate([
            'userId' => 'required|exists:users,id',
            'productId' => 'required|exists:products,id',
        ]);

        $userId = $request->input('userId');
        $productId = $request->input('productId');

        $rating = Ratings::create([
            'user_id' => $userId,
            'product_id' => $productId,
            'rating' => $request->input('rating'),
            'review' => $request->input('review'),
        ]);
        $rating->save();

        return response()->json(['message' => 'Rating and review added successfully'], 200);
    }
}
