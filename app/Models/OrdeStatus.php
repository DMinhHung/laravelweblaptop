<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdeStatus extends Model
{
    use HasFactory;

    protected $table = 'order_status';

    protected $fillable = [
        'orderstatus_id', 'value'
    ];
}
