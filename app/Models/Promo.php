<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $fillable = ['name', 'discount', 'product_id', 'cart_total'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
