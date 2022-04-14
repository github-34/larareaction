<?php


use App\Models\Image;

use App\Express\ExpressionService;

use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function (ExpressionService $service) {
//     return view('dashboard', [
//             'images' => $service->obtainExpressableInfo(Image::all())
//         ]);
// })->middleware(['auth'])->name('dashboard');


Route::middleware(['auth'])->group(function () {
    Route::get('expressions/{expression}',         [App\Express\ExpressionController::class, 'show'])->name('web.expressions.show');
    Route::post('expressions',                    [App\Express\ExpressionController::class, 'storeOrUpdate'])->name('web.expressions.store');
    Route::patch('expressions/{expression}',         [App\Express\ExpressionController::class, 'update'])->name('web.expressions.update');
    Route::delete('expressions/{expression}',                 [App\Express\ExpressionController::class, 'destroy'])->name('web.expressions.destroy');
});

//require __DIR__.'/auth.php';
