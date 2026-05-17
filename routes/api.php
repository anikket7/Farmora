<?php

use App\Http\Controllers\Api\ProductApiController;
use Illuminate\Support\Facades\Route;

Route::get('/products', [ProductApiController::class, 'index']);
Route::get('/products/{product}', [ProductApiController::class, 'show']);
Route::get('/categories', [ProductApiController::class, 'categories']);
Route::get('/bids/{bidSession}', [ProductApiController::class, 'bidStatus']);
