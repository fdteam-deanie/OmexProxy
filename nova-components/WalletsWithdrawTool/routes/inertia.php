<?php

use Illuminate\Support\Facades\Route;
use Laravel\Nova\Http\Requests\NovaRequest;
use Zavix\WalletsWithdrawTool\Http\Controllers\WalletsWithdrawToolController;

/*
|--------------------------------------------------------------------------
| Tool Routes
|--------------------------------------------------------------------------
|
| Here is where you may register Inertia routes for your tool. These are
| loaded by the ServiceProvider of the tool. The routes are protected
| by your tool's "Authorize" middleware by default. Now - go build!
|
*/

Route::get('/', [WalletsWithdrawToolController::class, 'index']);
