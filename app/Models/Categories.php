<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;

    protected $table = 'categories';

    // Các trường dữ liệu có thể được gán
    protected $fillable = [
        'categories_id', 'value'
    ];
}
