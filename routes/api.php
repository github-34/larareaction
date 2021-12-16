<?php

use App\Http\Controllers\ReactionController;
use App\Models\Image;
use App\Models\Reaction;
use App\Models\User;
use App\Services\Facades\React;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

// auth:sanctum
Route::middleware('')->get('user', function (Request $request) {
    return $request->user();
});

Route::get('imagereact', function (Request $request) {
    $image = Image::all()->first();
    Auth::login(User::find(1));
    $re = React::react($image, 0);
    $reaction = Reaction::where('reactable_id', $image->id)->where('reactable_type','App\Models\Image')->first();
    return $reaction;
});
Route::middleware(['auth'])->group(function () {
    Route::get('reactions/types',              [ReactionController::class, 'types'])->name('api.reactions.types');
    Route::get('reactions',                    [ReactionController::class, 'index'])->name('api.reactions.index');
    Route::get('reactions/{reaction}',         [ReactionController::class, 'show'])->name('api.reactions.show');
    Route::post('reactions',                    [ReactionController::class, 'storeOrUpdate'])->name('api.reactions.store');
    Route::patch('reactions/{reaction}',         [ReactionController::class, 'update'])->name('api.reactions.update');
    Route::delete('reactions/{reaction}',                 [ReactionController::class, 'destroy'])->name('api.reactions.destroy');
});
Route::post('reactions/stats',              [ReactionController::class, 'stats'])->name('api.reactions.stats');
