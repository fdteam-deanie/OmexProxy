<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\BalanceService;
use App\Services\PaymentService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    protected ?User $user;
    protected PaymentService $paymentService;
    protected BalanceService $balanceService;

    public function boot()
    {
        $this->user = auth()->user();
        if(Auth::check()) {
            $this->paymentService = new PaymentService($this->user);
            $this->balanceService = new BalanceService($this->user);
        }
    }

    /**
     * @deprecated
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function index()
    {
        $authUser = null;
        if(Auth::check()) {
            $authUser = [
                'id' => $this->user->id,
                'username' => $this->user->username,
                'balance' => number_format($this->balanceService->getUserBalance(), 2, '.', '')
            ];
        }

        return view('home')->with([
            'authUser' => $authUser,
        ]);
    }
}
