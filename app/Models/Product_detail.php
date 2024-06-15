<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_detail extends Model
{
    use HasFactory;

    protected $table = 'product_details';

    protected $fillable = [
        'product_id', 'CPU', 'RAM', 'HARDWARE', 'CARD', 'MONITOR', 'PIN', 'WEIGHT', 'MATERRIAL', 'LENGHT'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
