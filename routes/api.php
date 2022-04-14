<?php

use App\Models\Image;
use App\Models\User;

use App\Express\Models\Expression;
use App\Express\ExpressionService;
use App\Express\Xpress;
use App\Express\Facades\Express;

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
// Route::middleware('')->get('user', function (Request $request) {
//     return $request->user();
// });

// Route::get('imageexpress', function (Request $request) {
//     Auth::login(User::find(1));
//     $image = Image::all()->first();
//     Express::express($image, Xpress::FIVESTAR, Xpress::FIVESTARS);
//     Express::express($image, Xpress::MICHELINSTAR, Xpress::TWOMICHELINSTARS);
//     Express::express($image, Xpress::EMOTIVE, Xpress::HAPPY);

//     return Expression::where('expressable_id', $image->id)->where('expressable_type','App\Models\Image')->where('user_id',1)->get();
// });

// Route::get('imageexpressioninfo', function (Request $request, ExpressionService $service) {
//     Auth::login(User::find(1));
//     $images = Image::all();
//     return $service->obtainExpressableInfo($images);
// });


Route::middleware(['auth'])->group(function () {
    Route::get('expressions',                 [App\Express\ExpressionController::class, 'index'])->name('api.expressions.index');
    Route::get('expressions/{expression}',    [App\Express\ExpressionController::class, 'show'])->name('api.expressions.show');
    Route::post('expressions',                [App\Express\ExpressionController::class, 'storeOrUpdate'])->name('api.expressions.store');
    Route::patch('expressions/{expression}',  [App\Express\ExpressionController::class, 'update'])->name('api.expressions.update');
    Route::delete('expressions/{expression}',               [App\Express\ExpressionController::class, 'destroy'])->name('api.expressions.destroy');
});
Route::post('expressions/stats',              [App\Express\ExpressionController::class, 'stats'])->name('api.expressions.stats');
