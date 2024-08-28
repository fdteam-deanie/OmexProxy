<?php

use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\ComplaintController;
use App\Http\Controllers\Api\GeoController;
use App\Http\Controllers\Api\HistoryController;
use App\Http\Controllers\Api\MFAController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentsController;
use App\Http\Controllers\Api\Proxy\CatalogController;
use App\Http\Controllers\Api\Proxy\ProxyController;
use App\Http\Controllers\Api\Proxy\ProxyRentPeriodController;
use App\Http\Controllers\Api\Proxy\RefundController;
use App\Http\Controllers\Api\Proxy\UserProxyController;
use App\Http\Controllers\Api\ProxyTypeController;
use App\Http\Controllers\Api\Auth;
use App\Http\Controllers\Api\Support\TicketController;
use App\Http\Controllers\Api\Support\TicketMessageController;
use App\Http\Controllers\Api\UnlimitedSubscriptionController;
use App\Http\Controllers\Api\Wallet;
use App\Http\Controllers\StripeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/* USER */
Route::middleware(['web', 'auth:nova'])->group(function() {

    Route::prefix('mfa')
        ->controller(MFAController::class)
        ->name('mfa.')
        ->group(function () {
            Route::get('/left-time','getLeftTimeToRegenerateCode')
                ->name('left_time');
            Route::post('/regenerate', 'regenerateCode')
                ->name('regenerate');
            Route::post('/verify', 'verifyCode')
                ->name('verify');
        });
});

Route::group([], function () {

    Route::prefix('twa/auth')
        ->controller(\App\Http\Controllers\Api\TWA\AuthController::class)
        ->group(function () {
            Route::post('init-telegram', 'initTelegram')
                ->middleware('auth:api')
                ->name('twaInitTelegram');
            Route::post('login', 'login')
                ->name('twaLogin');
            Route::post('update-auth-setting', 'updateAuthSetting')
                ->middleware('auth:api')
                ->name('twaUpdateAuthSetting');
        });

    Route::prefix('unlimited-subscriptions')
        ->middleware('auth:api')
        ->controller(UnlimitedSubscriptionController::class)
        ->name('unlimited-subscriptions.')
        ->group(function () {
            Route::post('subscribe', 'subscribe')
                ->name('subscribe');
            Route::post('unsubscribe', 'unsubscribe')
                ->name('unsubscribe');
            Route::post('renew', 'renew')
                ->name('renew');
            Route::get('active', 'getActiveSubscription')
                ->name('active');
            Route::get('price', 'getPrice')
                ->name('price');
        });

    Route::prefix('history')
        ->middleware('auth:api')
        ->controller(HistoryController::class)
        ->name('history.')
        ->group(function () {
            Route::post('/', 'getProxies')
                ->name('proxies');
        });

    Route::group(['prefix' => 'auth'], function (){
        Route::post('/login', [Auth\LoginController::class, 'login']);
        Route::post('/register', [Auth\RegistrationController::class, 'register']);

        Route::group(['middleware' => 'auth:api'], function() {
            Route::post('/password', [Auth\ResetPasswordController::class, 'resetPassword']);
            Route::post('/logout', [Auth\LoginController::class, 'logout']);
            Route::post('/token/check', [Auth\TokenController::class ,'refresh']);
        });

        Route::post('/recovery', [Auth\ResetPasswordController::class, 'recovery']);
        Route::post('/reset-password/{token}', [Auth\ResetPasswordController::class, 'resetPasswordByToken']);

        Route::post('/me', [Auth\MeController::class, 'index']);
    });

    Route::group(['middleware' => 'auth:api'], function(){
        Route::group(['prefix' => 'user'], function () {
            Route::get('/balance', [App\Http\Controllers\Api\BalanceController::class, 'getBalance']);
            Route::get('/socks5', [App\Http\Controllers\Auth\Socks5Controller::class, 'getSocks5']);
            Route::post('/socks5', [App\Http\Controllers\Auth\Socks5Controller::class, 'updateSocks5']);
            Route::post('/topup', [App\Http\Controllers\Api\BalanceController::class, 'increaseBalance']);
        });

        Route::group(['prefix' => 'catalog'], function () {
            Route::post('/', [CatalogController::class, 'index']);
            Route::post('/order', [OrderController::class, 'order'])->name('confirmOrder');
            Route::get('/types', [ProxyTypeController::class, 'index'])->name('proxy-types.index');
        });

        Route::group(['prefix' => 'cart'], function () {
            Route::get('/', [CartController::class, 'getCart']);
            Route::post('/add', [CartController::class, 'addToCart']);
            Route::post('/update', [CartController::class, 'updateCart']);
            Route::delete('/remove', [CartController::class, 'removeFromCart']);
        });

        Route::group(['prefix' => 'proxies'], function () {
            Route::get('/{proxy}/detail', [ProxyController::class, 'detail'])->name('proxy.detail');
            Route::get('/types', [ProxyController::class, 'getTypes']);
            Route::post('/refund/{proxy}', [RefundController::class, 'index'])->name('proxy.refund');
        });

        Route::post('/my_catalog', [UserProxyController::class, 'index']);
        Route::get('/my_payments', [PaymentsController::class, 'index']);

        Route::prefix('complaints')
            ->controller(ComplaintController::class)
            ->name('complaints.')
            ->group(function () {
                Route::post('/', 'store')
                    ->name('store');
            });

        Route::prefix('orders')
            ->controller(OrderController::class)
            ->name('orders.')
            ->group(function () {
                Route::post('quick-buy', 'quickBuyProxy')
                    ->name('quick-buy');
                Route::post('renew-rental', 'renewRental')
                    ->name('renew-rental');
            });

        Route::prefix('rent-periods')
            ->controller(ProxyRentPeriodController::class)
            ->name('rent-periods.')
            ->group(function () {
                Route::get('/', 'index')
                    ->name('index');
            });

        Route::prefix('news')
            ->controller(NewsController::class)
            ->name('news.')
            ->group(function () {
                Route::get('categories', 'categories')
                    ->name('categories');
                Route::get('categories/{category}/articles', 'index')
                    ->name('index');
                Route::get('articles/{article}', 'show')
                    ->name('show');
            });

        Route::prefix('support')
            ->name('support.')
            ->group(function () {
                Route::get('tickets', [TicketController::class, 'index'])
                    ->name('index');
                Route::get('tickets/{ticket}', [TicketController::class, 'show'])
                    ->name('show');
                Route::post('tickets', [TicketController::class, 'store'])
                    ->name('store');

                Route::get('tickets/{ticket}/messages', [TicketMessageController::class, 'index'])
                    ->name('messages.index');
                Route::post('tickets/{ticket}/messages', [TicketMessageController::class, 'store'])
                    ->name('messages.store');
            });

        Route::group(['prefix' => 'geo'], function () {
            Route::get('/continents', [GeoController::class, 'getContinents']);
            Route::get('/countries/{continentId?}', [GeoController::class, 'getCountries']);
        });

    Route::group(['prefix' => 'wallets'], function () {
      Route::post('/', Wallet\GetTransactionAddressesAction::class);
      Route::post('/balance-recheck/{coin}', Wallet\SetBalanceRecheckAction::class);
      Route::get('/get-stripe-session/{amount}/{userId}', [StripeController::class, 'getSession']);
    });
  });

});

Route::post('wallets/stripe-webhook', [StripeController::class, 'handleWebhook']);
