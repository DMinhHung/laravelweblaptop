<?php

namespace App\Http\Controllers;

use App\Models\ShoppingCart;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlistItems = Wishlist::with('product', 'user')->get();

        return response()->json($wishlistItems);
    }
    public function addToWishlist(Request $request)
    {
        Log::info($request->all());
        $request->validate([
            'productId' => 'required|exists:products,id',
            'userId' => 'required|exists:users,id',
        ]);

        $productId = $request->input('productId');
        $userId = $request->input('userId');

        // Kiểm tra xem sản phẩm đã tồn tại trong wishlist của người dùng hay chưa
        $existingWishlistItem = Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($existingWishlistItem) {
            return response()->json(['message' => 'Product already in wishlist'], 400);
        }

        try {
            // Tạo một sản phẩm mới trong wishlist
            Wishlist::create([
                'user_id' => $userId,
                'product_id' => $productId,
            ]);

            return response()->json(['message' => 'Product added to wishlist successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to add product to wishlist', 'error' => $e->getMessage()], 500);
        }
    }


    public function deleteFromWishlist($id)
    {
        $wishlistItem = Wishlist::findOrFail($id);

        $wishlistItem->delete();

        return response()->json(['message' => 'Product removed from wishlist successfully'], 200);
    }
}
