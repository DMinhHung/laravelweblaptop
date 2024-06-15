<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthAdmin extends Model
{
    use HasFactory;

    protected $table = 'admin';

    // Các trường dữ liệu có thể được gán
    protected $fillable = [
        'name', 'email', 'role', 'password'
    ];
}
