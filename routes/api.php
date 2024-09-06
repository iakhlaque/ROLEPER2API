<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\ProductController;


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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::put('update/{id}', [AuthController::class, 'update']);
Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('reset-password', [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    // User routes
    Route::get('/fetch-all-users', [UserController::class, 'index']);
    Route::post('/save-user', [UserController::class, 'store']);
    Route::get('/show-user/{user}', [UserController::class, 'show']);
    Route::put('update-user/{user}', [UserController::class, 'update']);
    Route::delete('/delete-user/{user}', [UserController::class, 'destroy']);

    // Role routes
    Route::get('fetch-all-roles', [RoleController::class, 'index']);

    // Product routes
    Route::get('fetch-all-products', [ProductController::class, 'index']);
    Route::post('save-product', [ProductController::class, 'store']);
    Route::get('show-product/{id}', [ProductController::class, 'show']);
    Route::put('update-product/{id}', [ProductController::class, 'update']);
    Route::delete('delete-product/{id}', [ProductController::class, 'destroy']);
});