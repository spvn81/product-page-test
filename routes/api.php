<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/create-product', [ProductsController::class, 'createProduct']);
    Route::post('/create-product-discount', [ProductsController::class, 'createProductDiscount']);
    Route::post('/create-product-price', [ProductsController::class, 'createProductPrice']);
    Route::post('/upload-product-images', [ProductsController::class, 'uploadProductImage']);
    Route::get('/product-list',[ProductsController::class,'productList']);

    Route::get('/product/{product_id}',[ProductsController::class,'product']);



});

