<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pizza extends Model
{
    use HasFactory;
    protected $fillable = [
        'pizza_id',
        'pizza_name',
        'image',
        'price',
        'publish_status',
        'category_id',
        'discount_price',
        'buyOne_getOne_status',
        'waiting_time',
        'description',
    ];
}
