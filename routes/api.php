<?php

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


//product routes
Route::get('products', [ProductController::class, 'index']);
Route::get('product/{id}', [ProductController::class, 'show']);
Route::post('product', [ProductController::class, 'store']);
Route::put('product/{id}', [ProductController::class, 'update']);
Route::delete('product/{id}', [ProductController::class, 'destroy']);

//get all products
Route::get('all-products', [ProductController::class, 'productsAll']);
