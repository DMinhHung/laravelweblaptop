<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImgThumnail extends Model
{
    use HasFactory;

    protected $table = 'thumnail_index';

    protected $fillable = [
        'imgThumnail', 'imgReviews'
    ];
}
