<?php

namespace App\Nova\Resources;

use App\Nova\Resource;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Traits\HasPermissions;

class TransactionHistory extends Resource
{

  use HasPermissions;

  /**
   * The role for permission checks.
   *
   * @var string
   */
  protected $role = 'transaction';

  /**
   * The model the resource corresponds to.
   *
   * @var class-string<\App\Models\Support\Ticket>
   */
  public static $model = \App\Models\TransactionHistory::class;

  /**
   * The single value that should be used to represent the resource when being displayed.
   *
   * @var string
   */
  public static $title = 'id';

  /**
   * The columns that should be searched.
   *
   * @var array
   */
  public static $search = [
    'id',
    'user_id',
    'order_id',
    'amount',
    'is_deposit',
    'created_at'
  ];

  /**
   * Get the fields displayed by the resource.
   *
   * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
   * @return array
   */
  public function fields(NovaRequest $request)
  {
    return [
      ID::make()->sortable(),
      Text::make(__('Order'), 'order_id'),
      Text::make(__('Price'), 'amount')
        ->sortable()
        ->rules('required', 'max:255'),
      Select::make(__('Deposit'), 'is_deposit')->options([
        '0' => 'Вывод',
        '1' => 'Ввод',
      ])->displayUsingLabels(),
      DateTime::make(__('Date'), 'created_at')->sortable(),
      Select::make(__('Status'), 'status')->options([
        '0' => 'Открыт',
        '1' => 'В процессе',
        '2' => 'Закрыт',
      ])->displayUsingLabels(),
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
    return __('Transaction history');
  }

  public static function singularLabel()
  {
    return __('Transaction history');
  }
}
