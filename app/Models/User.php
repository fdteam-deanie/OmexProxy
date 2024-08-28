<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Http\Controllers\Api\BalanceController;
use App\Http\Requests\StoreBalanceRequest;
use App\Models\Pivot\UserProxy;
use App\Models\Support\Ticket;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Services\BalanceService;
use Illuminate\Database\Eloquent\Model;
use App\Mail\BalanceIncreased;
use Illuminate\Support\Facades\Mail;
//use Laravel\Sanctum\HasApiTokens;
use App\Traits\HasRole;

class User extends Authenticatable
{
  use HasApiTokens, HasFactory, Notifiable, HasRole;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'role_id',
    'as_block',
    'username',
    'email',
    'password',
    'socks5_username',
    'socks5_password'
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
  ];

  /**
     User's balance
    */
  //	public function getBalance() {
//
//		$userId = \Auth::user()->id;
//
//		/* Deposit payments */
//		$deposit = Payment::where(['user_id' => $userId, 'is_deposit' => 1])->sum('amount');
//
//		/* Withdraw payments */
//		$withdraw = Payment::where(['user_id' => $userId, 'is_deposit' => 0])->sum('amount');
//
//		$result = $deposit - $withdraw;
//		$result = number_format($result, 2, '.', '');
//
//		return $result;
//
//	}

  /**
     User's balance with currency symbol
    */
  //	public function getBalanceHTML() {
//
//		return '$'.Self::getBalance();
//
//	}

  public function payments(): HasMany
  {
    return $this->hasMany(Payment::class);
  }

  public function orders(): HasMany
  {
    return $this->hasMany(Order::class);
  }

  public function unlimitedSubscriptions(): HasMany
  {
    return $this->hasMany(UnlimitedSubscription::class);
  }

  public function authCodes(): HasMany
  {
    return $this->hasMany(AuthCode::class);
  }

  public function getBalanceAttribute()
  {
    $balanceService = new BalanceService($this);
    return $balanceService->getUserBalance();
  }

  public function increaseBalance($amount, $user, $email)
  {

    $request = new StoreBalanceRequest();
    $request->merge(['amount' => $amount]);
    $request->merge(['user_id'=> $user]);
    $balanceController = new BalanceController();

    $user = User::find($user);

    Mail::to($email)->send(new BalanceIncreased($amount, $user));

    return $balanceController->increaseBalance($request);
  }

  public function trialPeriods(): HasMany
  {
    return $this->hasMany(TrialPeriod::class);
  }

  public function wallets(): HasMany
  {
    return $this->hasMany(CryptoWallet::class);
  }

  public function proxies(): BelongsToMany
  {
    return $this->belongsToMany(Proxy::class, 'user_proxies', 'user_id', 'proxy_id')
      ->using(UserProxy::class)
      ->withPivot('paid_at', 'expired_at', 'is_paid', 'connect_host', 'username', 'password')->withTimestamps();
  }

  public function tickets(): HasMany
  {
    return $this->hasMany(Ticket::class);
  }

    public function telegramUsers(): HasMany
    {
        return $this->hasMany(TelegramUser::class);
    }
}
