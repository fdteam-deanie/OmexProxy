<?php

namespace App\Nova\Resources;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaInlineTextField\InlineText;
use App\Traits\HasPermissions;
class UnlimitedSubscription extends Resource
{
  use HasPermissions;

  /**
   * The role for permission checks.
   *
   * @var string
   */
  protected $role = 'nolimitinday';


  /**
   * The model the resource corresponds to.
   *
   * @var class-string<\App\Models\Setting>
   */
  public static $model = \App\Models\Setting::class;

  /**
   * The single value that should be used to represent the resource when being displayed.
   *
   * @var string
   */
  public static $title = 'key';

  /**
   * The columns that should be searched.
   *
   * @var array
   */
  public static $search = [
    'key',
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
      Text::make("", function () {
        return $this->getLabelByKey($this->key);
      })->exceptOnForms()->rules('required', 'max:255'),

      InlineText::make('', 'value')
        ->rules('required', 'max:255'),
    ];
  }

  public static function indexQuery(NovaRequest $request, $query)
  {
    $query->where('group', 'unlimited_subscription');
    return parent::indexQuery($request, $query);
  }

  public function getLabelByKey($key)
  {
    $labels = [
      'unlimited_subscription_price' => __('Unlimited Subscription Price'),
    ];
    return $labels[$key] ?? $key;
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

  public static function authorizedToCreate(Request $request)
  {
    return false;
  }

  public function authorizedToDelete(Request $request)
  {
    return false;
  }

  public function authorizedToUpdate(Request $request)
  {
    return false;
  }

  public function authorizedToView(Request $request)
  {
    return false;
  }

  public function authorizedToReplicate(Request $request)
  {
    return false;
  }

  public static function searchable()
  {
    return false;
  }

  public static function label()
  {
    return __('Unlimited Subscriptions');
  }

  public static function singularLabel()
  {
    return __('Unlimited Subscription');
  }
}
