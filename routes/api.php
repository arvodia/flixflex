<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MoviesController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TvController;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('login', [AuthController::class, 'authenticate']);
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/account', [AuthController::class, 'currentUserInfo'])->name('account');
    Route::get('/favories', [MoviesController::class, 'favories']);
    Route::get('/favorie/{id}/add', [MoviesController::class, 'favorie_add']);
    Route::get('/favorie/{id}/remove', [MoviesController::class, 'favorie_remove']);
    Route::get('/favorie_tv/{id}/add', [TvController::class, 'favorie_add']);
    Route::get('/favorie_tv/{id}/remove', [TvController::class, 'favorie_remove']);
});
Route::get('/movies', [MoviesController::class, 'movies']);
Route::get('/top_movies', [MoviesController::class, 'top_movies']);
Route::get('/movie/{id}', [MoviesController::class, 'movie']);

Route::get('/serie/{id}', [TvController::class, 'serie']);
Route::get('/series', [TvController::class, 'series']);
Route::get('/top_tv', [TvController::class, 'top_tv']);

Route::get('/search-movies', [SearchController::class, 'search_movies']);
Route::get('/search-tv', [SearchController::class, 'search_tv']);