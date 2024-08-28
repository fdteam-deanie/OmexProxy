<?php

use App\Http\Controllers\Auth\MFAController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/mfa', [MFAController::class, 'index'])
    ->name('mfa')
    ->middleware('auth:nova');

//Auth::routes();



Route::get('/{any}', function () {
    return view('root');
})->where('any', '^(?!nova).*$');
