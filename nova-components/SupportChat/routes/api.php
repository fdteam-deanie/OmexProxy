<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Proxy\SupportChat\Http\Controllers\TicketController;

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

Route::get('/tickets/{ticket}', [TicketController::class, 'show']);
Route::get('/tickets/{ticket}/messages', [TicketController::class, 'messages']);
Route::post('/tickets/{ticket}/messages', [TicketController::class, 'reply']);
Route::post('/tickets/{ticket}/close', [TicketController::class, 'close']);
