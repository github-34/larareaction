<?php

use Illuminate\Support\Facades\Route;
use Insomnicles\Laraexpress\ExpressionController;

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

Route::middleware(['auth'])->group(function () {
    Route::get('expressions',                 [ExpressionController::class, 'index'])->name('api.expressions.index');
    Route::get('expressions/{expression}',    [ExpressionController::class, 'show'])->name('api.expressions.show');
    Route::post('expressions',                [ExpressionController::class, 'storeOrUpdate'])->name('api.expressions.store');
    Route::patch('expressions/{expression}',  [ExpressionController::class, 'update'])->name('api.expressions.update');
    Route::delete('expressions/{expression}', [ExpressionController::class, 'destroy'])->name('api.expressions.destroy');
});
Route::post('expressions/stats',              [ExpressionController::class, 'stats'])->name('api.expressions.stats');
