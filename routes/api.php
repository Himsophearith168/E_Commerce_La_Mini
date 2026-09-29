<?php

use App\Http\Controllers\categoryController;
use App\Http\Controllers\productController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('categories',categoryController::class);
Route::apiResource('products',productController::class);
