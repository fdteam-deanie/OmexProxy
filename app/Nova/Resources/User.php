<?php

namespace App\Nova\Resources;

use Illuminate\Validation\Rules;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Traits\HasPermissions;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Number;

class User extends Resource
{
  use HasPermissions;

  /**
   * The role for permission checks.
   *
   * @var string
   */
  protected $role = 'user';

  /**
   * The model the resource corresponds to.
   *
   * @var class-string<\App\Models\User>
   */
  public static $model = \App\Models\User::class;

  /**
   * The single value that should be used to represent the resource when being displayed.
   *
   * @var string
   */
  public static $title = 'name';

  /**
   * The columns that should be searched.
   *
   * @var array
   */
  public static $search = [
    'id',
    'name',
    'email',
  ];

  /**
   * Get the fields displayed by the resource.
   *
   * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
   * @return array
   */
  public function fields(NovaRequest $request)
  {
    $currentRoleIndex = $request->user()->getRoleIndex();
    $roles = $request->user()->getRoleIds();

    $filteredRoles = array_filter($roles, function ($roleIndex) use ($currentRoleIndex) {
      return $roleIndex < $currentRoleIndex;
    });

    $filteredRoles = array_flip($filteredRoles);
    return [
      ID::make()->sortable(),

      Select::make(__('Role'), 'role_id')
        ->options($filteredRoles)
        ->rules('required')
        ->displayUsingLabels()
        ->searchable()
        ->canSee(function ($request) {
          return $request->user()->hasPermission('run_action_role');
        }),
      Gravatar::make(__('Avatar'))->maxWidth(50),

      Text::make(__('UName'), 'name')
        ->sortable()
        ->rules('required', 'max:255'),

      Text::make(__('Email'), 'email')
        ->sortable()
        ->rules('required', 'email', 'max:254')
        ->creationRules('unique:users,email')
        ->updateRules('unique:users,email,{{resourceId}}'),

      Password::make(__('Password'), 'password')
        ->onlyOnForms()
        ->creationRules('required', Rules\Password::defaults())
        ->updateRules('nullable', Rules\Password::defaults()),


      Boolean::make(__('Block user'), 'as_block')
        ->trueValue(1)
        ->falseValue(0)
        ->hideFromIndex()
        ->displayUsing(function ($value) {
          return $value ? 'Unblock' : 'Block';
        })
        ->fillUsing(function ($request, $model, $attribute, $requestAttribute) {
          $model->{$attribute} = $request->input($requestAttribute) ? 1 : 0;
        })
        ->canSee(function ($request) {
          return $request->user()->hasPermission('run_action_user');
        }),

      Text::make(__('Balance'), function () {
        return $this->balance . '$';
      })->onlyOnDetail(),

      Number::make(__('Balance controll'), 'balance_change')
        ->help('Введите сумму, на которую нужно увеличить баланс. Используйте отрицательное число для уменьшения. Текущий баланс: ' . $this->balance . '$')
        ->onlyOnForms()
        ->resolveUsing(function ($value, $resource, $attribute) {
          return $value ?? 0;
        })
        ->fillUsing(function ($request, $model, $requestAttribute) {
          $increaseAmount = $request->input($requestAttribute);
          if ($increaseAmount) {
            $model->increaseBalance($increaseAmount, $model->id, $model->email);
          }
        })
        ->default(0),

      HasMany::make(__('Transaction history'), 'payments', TransactionHistory::class)
        ->onlyOnDetail(),

      HasMany::make(__('Purchase history'), 'orders', PurchaseHistory::class)
        ->onlyOnDetail(),

      HasMany::make(__('Tickets'), 'tickets', Ticket::class)
        ->onlyOnDetail(),

    ];
  }

  /**
   * Get the cards available for the request.
   *
   * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
   * @return array
   */
  public function cards(NovaRequest $request)
  {
    return [];
  }

  /**
   * Get the filters available for the resource.
   *
   * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
   * @return array
   */
  public function filters(NovaRequest $request)
  {
    return [];
  }

  /**
   * Get the lenses available for the resource.
   *
   * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
   * @return array
   */
  public function lenses(NovaRequest $request)
  {
    return [];
  }

  /**
   * Get the actions available for the resource.
   *
   * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
   * @return array
   */
  public function actions(NovaRequest $request)
  {
    return [];
  }

  public static function label()
  {
    return __('Users');
  }

  public static function singularLabel()
  {
    return __('User');
  }

}
