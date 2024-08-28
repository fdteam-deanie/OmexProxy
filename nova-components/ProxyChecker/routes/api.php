<?php

use Illuminate\Support\Facades\Route;
use Proxy\ProxyChecker\Http\Controllers\ProxyCheckerController;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. You're free to add
| as many additional routes to this file as your tool may require.
|
*/

 Route::post('/check', [ProxyCheckerController::class, 'check']);
 Route::get('/complaints/{complaint}/proxies', [ProxyCheckerController::class, 'proxies']);
 Route::post('/complaints/{complaint}/proxies/replace', [ProxyCheckerController::class, 'replaceProxy']);
