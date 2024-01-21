<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('', [\App\Http\Controllers\VierkandleController::class, 'index'])->name('index');
Route::get('/list', [\App\Http\Controllers\VierkandleController::class, 'list'])->name('list');
Route::get('/{vierkandle}', [\App\Http\Controllers\VierkandleController::class, 'show'])->name('show');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->group(function () {
    Route::post('/guess', [\App\Http\Controllers\VierkandleController::class, 'guess'])->name('guess');
    Route::prefix('user/')->name('user.')->controller(\App\Http\Controllers\UserController::class)->group(function () {
        Route::post('theme', 'updateTheme')->name('theme');
    });
});

Route::prefix('api')->name('api.')->group(function () {
    Route::get('vierkandle/{vierkandle}', [\App\Http\Controllers\VierkandleController::class, 'vierkandle'])->name('vierkandle');
});
