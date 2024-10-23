<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuantityType extends Model
{
    protected $fillable = ['name'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
