<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\AuthController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::post('login', [AuthController::class, 'signin']);
// Route::post('register', [AuthController::class, 'signup']);

Route::middleware('auth:sanctum')->group(function () {

    // Route::get('fetch-all-users', [UserController::class, 'index']);
    // Route::post('save-user', [UserController::class, 'store']);
    // Route::get('show-user/{id}', [UserController::class, 'show']);
    // Route::put('update-user/{id}', [UserController::class, 'update']);
    // Route::delete('delete-user/{id}', [UserController::class, 'destroy']);

    // Route::get('roles-fetch-all-posts', [RoleController::class, 'index']);
    // Route::post('save-post', [RoleController::class, 'store']);
    // Route::get('show-post/{id}', [RoleController::class, 'show']);
    // Route::put('update-post/{id}', [RoleController::class, 'update']);
    // Route::delete('delete-post/{id}', [RoleController::class, 'destroy']);

    // Route::get('fetch-all-products', [ProductController::class, 'index']);
    // Route::post('save-product', [ProductController::class, 'store']);
    // Route::get('show-product/{id}', [ProductController::class, 'show']);
    // Route::put('update-product/{id}', [ProductController::class, 'update']);
    // Route::delete('delete-product/{id}', [ProductController::class, 'destroy']);

});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('fetch-all-products', [ProductController::class, 'index']);
Route::post('save-product', [ProductController::class, 'store']);
Route::get('show-product/{id}', [ProductController::class, 'show']);
Route::put('update-product/{id}', [ProductController::class, 'update']);
Route::delete('delete-product/{id}', [ProductController::class, 'destroy']);

// Route::get('fetch-all-users', [UserController::class, 'index']);
// Route::post('save-user', [UserController::class, 'store']);
// Route::get('show-user/{id}', [UserController::class, 'show']);
// Route::put('update-user/{id}', [UserController::class, 'update']);
// Route::delete('delete-user/{id}', [UserController::class, 'destroy']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/fetch-all-users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
});


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
// Route::put('update/{id}', [AuthController::class, 'update']);
Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('reset-password', [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
});