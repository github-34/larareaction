<?php

use Illuminate\Support\Facades\Route;
use Insomnicles\Laraexpress\ExpressionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth'])->group(function () {
    Route::get('expressions/{expression}', [ExpressionController::class, 'show'])->name('web.expressions.show');
    Route::post('expressions', [ExpressionController::class, 'storeOrUpdate'])->name('web.expressions.store');
    Route::patch('expressions/{expression}', [ExpressionController::class, 'update'])->name('web.expressions.update');
    Route::delete('expressions/{expression}', [ExpressionController::class, 'destroy'])->name('web.expressions.destroy');
});
