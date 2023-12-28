<?php

use App\Http\Controllers\API\FolderController;
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

/**Route for login API */
Route::post('/register', [UserController::class, 'register']);
/**Route for register API */
Route::post('/login', [UserController::class, 'login']);


Route::middleware(['jwt.auth','auth:api'])->group(function () {

    /**Route for View Profile */
    Route::get('/profile', [UserController::class, "show"]);
    
    /**Route for refresh-token */
    Route::get('/refresh-token', [UserController::class, 'refreshToken']);
    /**Route for logout API */
    Route::post('/logout', [UserController::class, 'logout']);
    
    Route::post('/folders/store', [FolderController::class, 'store'])->name('folders.store');
    Route::get('/folders/index', [FolderController::class, 'index'])->name('folders.index');
});

