<?php

namespace App\Providers;

use App\Nova\Dashboards\Main;

use App\Nova\Resources\Complaint;
use App\Nova\Resources\News\Article;
use App\Nova\Resources\News\Category;
use App\Nova\Resources\Proxy;
use App\Nova\Resources\ProxyHosting;
use App\Nova\Resources\ProxyRentPeriod;
use App\Nova\Resources\Ticket;
use App\Nova\Resources\TransactionHistory;
use App\Nova\Resources\TrialPeriod;
use App\Nova\Resources\UnlimitedSubscription;
use App\Nova\Resources\Rate;
use App\Nova\Resources\User;
use App\Nova\Resources\PurchaseHistory;
use App\Nova\Resources\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;

use App\Models\Support\Ticket as TicketModel;
use App\Models\User as UserModel;
use Proxy\ProxyHostingManagementTool\ProxyHostingManagementTool;
use Zavix\WalletsWithdrawTool\WalletsWithdrawTool;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
    parent::boot();

    Nova::mainMenu(function (Request $request) {
      $proxyHosting = "";
      $wallet = "";

      if (!$request->user()->hasBlock() && $request->user()->hasPermission("view_any_proxyhosting")) {
        $proxyHosting =  MenuItem::make(__('Proxy Hosting'))->path('/hosting-management');
      }

      if (!$request->user()->hasBlock() && $request->user()->hasPermission("view_any_wallet")) {
        $wallet = MenuSection::make(__('Wallets Withdraw'))->path('/wallets-withdraw')->icon('currency-dollar'); 
      }

      return [
        MenuSection::dashboard(Main::class)->icon('chart-bar'),
        MenuSection::make(__('Proxy'), [
          MenuItem::resource(Proxy::class),
          $proxyHosting,
          MenuItem::resource(ProxyRentPeriod::class),
        ])->icon('cloud')->collapsable(),

        MenuSection::make(__('Support'), [
          MenuItem::resource(Ticket::class)->withBadgeIf(function () {
            return TicketModel::whereStatus(TicketModel::STATUS_OPEN)->count();
          }, 'info', function () {
            return TicketModel::whereStatus(TicketModel::STATUS_OPEN)->count() > 0;
          }),
          MenuItem::resource(Complaint::class),
          // MenuItem::resource(TransactionHistory::class),
          // MenuItem::resource(PurchaseHistory::class),
          MenuItem::resource(User::class),
          MenuItem::resource(Role::class),
        ])->icon('thumb-up')->collapsable(),

        MenuSection::make(__('Prices'), [
          MenuItem::resource(Rate::class),
          MenuItem::resource(UnlimitedSubscription::class),
          MenuItem::resource(TrialPeriod::class),
        ])->icon('shopping-bag')->collapsable(),

        MenuSection::make(__('News'), [
          MenuItem::resource(Category::class),
          MenuItem::resource(Article::class),
        ])->icon('newspaper')->collapsable(),

        $wallet,
        //                MenuSection::resource(Proxy::class)->icon('cloud'),

        //                MenuSection::resource(Rate::class)->icon('currency-dollar'),

        //                MenuSection::resource(Complaint::class)->icon('emoji-sad'),
        //
        //                MenuSection::resource(Ticket::class)->icon('emoji-sad'),

        //                MenuSection::resource(UnlimitedSubscription::class)->icon('star'),
        //
        //                MenuSection::resource(TrialPeriod::class)->icon('clock'),
        //
        //                MenuSection::resource(ProxyRentPeriod::class)->icon('clock'),

        //                MenuSection::resource(User::class)->icon('user'),

        //                MenuSection::make(__('Proxy Hosting'))->path('/hosting-management')->icon('server'),
      ];
    });

    Nova::createUserUsing(function ($command) {
      return [
        $command->ask('Name'),
        $command->ask('Email Address'),
        $command->ask('Username'),
        $command->secret('Password')
      ];
    }, function ($name, $email, $username, $password) {
      (new UserModel)->forceFill([
        'name' => $name,
        'email' => $email,
        'username' => $username,
        'password' => Hash::make($password),
        'email_verified_at' => now(),
      ])->save();
    });

    Nova::booted(function () {
      app()->setlocale('ru');
    });
  }

  /**
   * Register the Nova routes.
   *
   * @return void
   */
  protected function routes()
  {
    Nova::routes()
      ->withAuthenticationRoutes()
      ->withPasswordResetRoutes()
      ->register();
  }

  /**
   * Register the Nova gate.
   *
   * This gate determines who can access Nova in non-local environments.
   *
   * @return void
   */
  protected function gate()
  {
    Gate::define('viewNova', function ($user) {
      return $user->is_admin;
    });
  }

  /**
   * Get the dashboards that should be listed in the Nova sidebar.
   *
   * @return array
   */
  protected function dashboards()
  {
    return [
      new \App\Nova\Dashboards\Main,
    ];
  }

  /**
   * Get the tools that should be listed in the Nova sidebar.
   *
   * @return array
   */
  public function tools()
  {
    return [
      new WalletsWithdrawTool(),
      new ProxyHostingManagementTool()
    ];
  }

  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    //
  }
}
