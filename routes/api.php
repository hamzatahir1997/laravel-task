<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

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

Route::post('login',[AuthController::class,'login'])->middleware('cors');
Route::get('user/{id}',[UserController::class,'viewUser'])->middleware('cors');
Route::get('users',[UserController::class,'viewAllUsers'])->middleware('cors');

Route::group(['middleware' => ['auth:sanctum','cors']], function () {
    Route::post('user',[UserController::class,'registerUser']);
    Route::put('user/{id}',[UserController::class,'updateUser']);
    Route::delete('user/{id}',[UserController::class,'deleteUser']);
});

