<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'family',
        'type',
        'price',
        'quantity',
        'quantity_sold',
        'customer_name',
        'status',
        'description',
    ];
}