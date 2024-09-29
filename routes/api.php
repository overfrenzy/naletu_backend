<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SliderController;

// Публичные api (категории, продукты, слайдеры, тип продукта)

Route::apiResource('categories', CategoryController::class);
Route::apiResource('products', ProductController::class);
Route::apiResource('sliders', SliderController::class);
Route::apiResource('quantity-types', QuantityTypeController::class);