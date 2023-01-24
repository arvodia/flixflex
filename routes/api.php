<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MoviesController;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('login', [AuthController::class, 'authenticate']);
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/account', [AuthController::class, 'currentUserInfo'])->name('account');
});
Route::get('/movies', [MoviesController::class, 'movies']);
Route::get('/movie/{id}', [MoviesController::class, 'movie']);

//Route::get('/series', [MoviesController::class, 'series']);
//Route::get('/serie/{id}', [MoviesController::class, 'serie']);
