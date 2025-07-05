<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'family',
        'type',
        'price',
        'quantity',
        'customer_name',
        'status',
        'description',
    ];
}
