<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FilmsController;
use App\Http\Controllers\Api\PlanetsController;
use App\Http\Controllers\Api\StarshipsController;

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

Route::get('films', [FilmsController::class, 'index'])->name('api.films.index');
Route::get('films/{id}', [FilmsController::class, 'show'])->name('api.films.show');
Route::get('planets', [PlanetsController::class, 'index'])->name('api.planets.index');
Route::get('starships', [StarshipsController::class, 'index'])->name('api.starships.index');

Route::fallback(function(){
    return response([
        'message' => "This is not a path you're looking for. Carry on!"
    ], 404);
});
