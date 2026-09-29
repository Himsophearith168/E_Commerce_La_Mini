<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\categoryController;
use App\Http\Controllers\productController;
use App\Http\Controllers\product_imageController;
use Illuminate\Support\Facades\Route;

Route::get('/user', [AuthController::class, 'getProfile'])->middleware('auth:api');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::match(['put', 'patch'], '/user', [AuthController::class, 'updateProfile']);
    Route::apiResource('categories', categoryController::class);
    Route::apiResource('products', productController::class);
    Route::post('product-images', [product_imageController::class, 'store']);
    Route::get('product-images/{id}', [product_imageController::class, 'show']);
    Route::match(['put', 'patch'], 'product-images/{id}', [product_imageController::class, 'update']);
    Route::delete('product-images/{id}', [product_imageController::class, 'destroy']);
});
