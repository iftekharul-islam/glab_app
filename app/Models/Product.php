<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'seller',
        'in_stock',
        'price',
        'discount_price',
        'img_url',
        'quantity',
    ];

    // appending column rating, estimatedDelivery to the model
    protected $appends = ['rating', 'estimatedDelivery'];

    // add the value of rating to the model
    public function getRatingAttribute()
    {
        return 4;
    }

    // add the value of estimatedDelivery to the model with format  '18th Nov 2021'
    public function getEstimatedDeliveryAttribute()
    {
        return now()->addDays(7)->format('jS M Y');
    }


}
