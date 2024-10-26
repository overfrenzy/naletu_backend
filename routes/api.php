<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SliderController;
use App\Http\Controllers\Api\QuantityTypeController;
use App\Http\Controllers\Api\PromoController;

// Публичные api (категории, продукты, слайдеры, тип продукта, промокоды)

Route::apiResource('categories', CategoryController::class);
Route::apiResource('products', ProductController::class);
Route::apiResource('sliders', SliderController::class);
Route::apiResource('quantity-types', QuantityTypeController::class);
Route::apiResource('promos', PromoController::class);
Route::post('/promos/validate', [PromoController::class, 'validatePromo']);