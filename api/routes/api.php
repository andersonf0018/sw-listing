<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\PeopleController;
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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/people', [PeopleController::class, 'index']);
Route::get('/people/{id}', [PeopleController::class, 'show']);

Route::get('/movies', [MoviesController::class, 'index']);
Route::get('/movies/{id}', [MoviesController::class, 'show']);

Route::prefix('statistics')->group(function () {
    Route::get('/', [StatisticsController::class, 'index']);
    Route::get('/searches', [StatisticsController::class, 'searches']);
    Route::get('/detail-views', [StatisticsController::class, 'detailViews']);
    Route::get('/performance', [StatisticsController::class, 'performance']);
    Route::get('/traffic', [StatisticsController::class, 'traffic']);
});

