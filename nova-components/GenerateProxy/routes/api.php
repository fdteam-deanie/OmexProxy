<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Proxy\GenerateProxy\GenerateProxyAction;

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

 Route::post('/', GenerateProxyAction::class)->name('generate-proxy');
