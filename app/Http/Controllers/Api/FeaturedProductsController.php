<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FeaturedProduct;

class FeaturedProductsController extends Controller
{
    public function index()
    {
        $featuredProducts = FeaturedProduct::with('product.category', 'product.quantityType')
            ->get()
            ->pluck('product');

        return response()->json($featuredProducts);
    }
}
