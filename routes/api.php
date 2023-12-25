<?php

use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::group(['middleware'=>'auth:api'],function($routes){
    Route::post('/register',[UserController::class,'register']);
    Route::post('/login',[UserController::class,'login']);
    Route::post('/profile',[UserController::class,'profile']);
    Route::post('/refresh',[UserController::class,'refresh']);
    Route::post('/logout',[UserController::class,'logout']);

    

});
Route::post('/store-product', [ProductController::class, 'storeProduct'])->name('store.product');
Route::get('/images/{filename}',[ProductController::class, 'show'])->name('show.product');