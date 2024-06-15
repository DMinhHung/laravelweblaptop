<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingCart extends Model
{
    use HasFactory;
    
    protected $table = 'shopping_cart';

    // Các trường dữ liệu có thể được gán
    protected $fillable = [
        'products_id', 'user_id', 'quantity', 'total_price',
    ];

    public function product() {
        return $this->belongsTo(Product::class, 'products_id', 'id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
