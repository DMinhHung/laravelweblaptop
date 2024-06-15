<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'name', 'price', 'img', 'quantity', 'description', 'color', 'categories_id'
    ];

    public function details()
    {
        return $this->hasOne(Product_detail::class, 'product_id', 'id');
    }
}
