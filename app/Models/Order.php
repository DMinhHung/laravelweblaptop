<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'order';

    // Các trường dữ liệu có thể được gán
    protected $fillable = [
        'code', 'product_id', 'checkout_id', 'quantity', 'total_price'
    ];

    public function checkout()
    {
        return $this->belongsTo(Checkout::class, 'checkout_id', 'id');
    }

    public function product()
    {
        return $this->hasMany(Product::class);
    }
}
