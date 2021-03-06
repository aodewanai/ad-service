<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::group(['middleware' => 'auth:users'], function () {
    Route::get('/posts', [PostController::class, 'index']);
    Route::post('/posts/create', [PostController::class, 'create'])->middleware(['throttle:limit']);
    Route::put('/posts/edit/{id}', [PostController::class, 'edit']);
});

Route::group(['middleware' => 'auth:admins'], function () {
    Route::get('/admin/users', [AdminController::class, 'users']);
    Route::post('/admin/ban/{email}', [AdminController::class, 'ban']);
    Route::post('/admin/unban/{email}', [AdminController::class, 'unban']);
});

//Route::post("/login", [UserController::class, 'login']);
