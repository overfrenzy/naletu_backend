<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SliderController;
use App\Http\Controllers\Api\QuantityTypeController;
use App\Http\Controllers\Api\PromoController;
use App\Http\Controllers\Api\FeaturedProductsController;

// Публичные api (категории, продукты, слайдеры, тип продукта, промокоды)

Route::apiResource('categories', CategoryController::class)->except('show');
Route::get('/categories/{slug}', [CategoryController::class, 'show']);

Route::apiResource('products', ProductController::class);

Route::apiResource('sliders', SliderController::class)->except('show');
Route::get('/sliders/{slug}', [SliderController::class, 'show']);

Route::apiResource('quantity-types', QuantityTypeController::class);

Route::apiResource('promos', PromoController::class);
Route::post('/promos/validate', [PromoController::class, 'validatePromo']);

Route::get('/featured-products', [FeaturedProductsController::class, 'index']);