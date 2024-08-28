<?php

namespace App\Nova\Resources;

use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Traits\HasPermissions;
class Rate extends Resource
{
  use HasPermissions;

  /**
   * The role for permission checks.
   *
   * @var string
   */
  protected $role = 'rate';

  /**
   * The model the resource corresponds to.
   *
   * @var class-string<\App\Models\Rate>
   */
  public static $model = \App\Models\Rate::class;


  public static $defaultSort = [
    'days' => 'asc',
    'id' => 'asc'
  ];
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
    'name',
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
      Text::make(__("Name"), "name")->sortable(),

      Currency::make(__("Mobile Price"), "mobile_price")
        ->sortable()
        ->min(0)
        ->max(1000)
        ->step(0.01)
        ->currency('USD'),

      Currency::make(__("Residential Price"), "residential_price")
        ->sortable()
        ->min(0)
        ->max(1000)
        ->step(0.01)
        ->currency('USD'),

      Currency::make(__("Server Price"), "server_price")
        ->sortable()
        ->min(0)
        ->max(1000)
        ->step(0.01)
        ->currency('USD'),

      Number::make(__("Limit Live Time"), "days")
        ->hideFromIndex(),
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
    return __('Rates');
  }

  public static function singularLabel()
  {
    return __('Rate');
  }
}
