<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware(['auth'])->group(function () {
    Route::get(     'reactions/types',              [ App\Http\Controllers\ReactionController::class, 'types' ])->name('api.reactions.types');
    Route::get(     'reactions',                    [ App\Http\Controllers\ReactionController::class, 'index' ])->name('api.reactions.index');
    Route::get(     'reactions/{reaction}',         [ App\Http\Controllers\ReactionController::class, 'show' ])->name('api.reactions.show');
    Route::post(    'reactions',                    [ App\Http\Controllers\ReactionController::class, 'store' ])->name('api.reactions.store');
    Route::patch(   'reactions/{reaction}',         [ App\Http\Controllers\ReactionController::class, 'update' ])->name('api.reactions.update');
    Route::delete(        'reactions/{reaction}',                 [ App\Http\Controllers\ReactionController::class, 'destroy' ])->name('api.reactions.destroy');
});
Route::post(    'reactions/stats',              [ App\Http\Controllers\ReactionController::class, 'stats' ])->name('api.reactions.stats');
