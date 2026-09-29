<?php

use App\Http\Controllers\categoryController;
use App\Http\Controllers\productController;
use App\Http\Controllers\product_imageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('categories',categoryController::class);
Route::apiResource('products',productController::class);
Route::post('product-images', [product_imageController::class, 'store']);
Route::get('product-images/{id}', [product_imageController::class, 'show']);
Route::match(['put', 'patch'], 'product-images/{id}', [product_imageController::class, 'update']);
Route::delete('product-images/{id}', [product_imageController::class, 'destroy']);
