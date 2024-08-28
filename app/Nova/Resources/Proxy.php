<?php

namespace App\Nova\Resources;

use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use ShuvroRoy\NovaDynamicViews\CustomComponents;
use App\Traits\HasPermissions;
class Proxy extends Resource
{

  use HasPermissions;

  /**
   * The role for permission checks.
   *
   * @var string
   */
  protected $role = 'proxy';

  /**
   * The model the resource corresponds to.
   *
   * @var class-string<\App\Models\Proxy>
   */
  public static $model = \App\Models\Proxy::class;

  /**
   * The single value that should be used to represent the resource when being displayed.
   *
   * @var string
   */
  public static $title = 'ip_shown';

  /**
   * The columns that should be searched.
   *
   * @var array
   */
  public static $search = [
    'id',
    'ip_shown',
    'domain',
    'max_users_count',
    'state->name',
    'city->name',
    'isp->name',
    'zip->name',
    'type->name',
    'countryIp->name'
  ];

  public function fieldsForIndex(NovaRequest $request)
  {
    return [
      ID::make()->sortable(),
      Text::make(__("IP"), "ip_shown"),
      Text::make(__("Port"), "port"),
      Text::make(__("Domain"), "domain_shown"),
      Text::make(__('State'), 'state->name'),
      Text::make(__('City'), 'city->name'),
      Text::make(__('ISP'), 'isp->name'),
      Text::make(__('ZIP'), 'zip->name'),
      Text::make(__('Speed'), "speed"),
      Text::make(__('Ping'), "ping"),
      Text::make(__('Type'), 'type->name'),
      Date::make(__('Added At'), 'created_at'),
      Number::make(__('Price'), 'price'),
      Number::make(__('Amount of Use'), 'orders_count'),
      Text::make(__('Active User'), function () {
        $user = $this->getLastUser();
        return $user ? $user->email : null;
      })->onlyOnIndex()
    ];
  }
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
      Text::make(__("IP"), "ip_shown"),
      Text::make(__("Port"), "port"),

      Text::make(__("Domain"), "domain_shown"),

      Number::make(__("Max user's count"), "max_users_count")->default(env('MAX_PROXY_USERS_COUNT')),

      BelongsTo::make(__('Country Host'), 'countryIp', CountryHost::class)
        ->nullable()
        ->showCreateRelationButton(),

      BelongsTo::make(__('Continent'), 'continent', Continent::class)
        ->nullable()
        ->showCreateRelationButton(),

      BelongsTo::make(__('Country'), 'country', Country::class)
        ->nullable()
        ->showCreateRelationButton(),

      BelongsTo::make(__('State'), 'state', State::class)
        ->nullable()
        ->showCreateRelationButton(),

      BelongsTo::make(__('City'), 'city', City::class)
        ->nullable()
        ->showCreateRelationButton(),

      BelongsTo::make(__('ISP'), 'isp', ProxyISP::class)
        ->nullable()
        ->showCreateRelationButton(),

      BelongsTo::make(__('Org'), 'org', ProxyOrg::class)
        ->nullable()
        ->showCreateRelationButton(),

      BelongsTo::make(__('Type'), 'type', ProxyType::class)
        ->nullable()
        ->showCreateRelationButton(),

      BelongsTo::make(__('ZIP'), 'zip', Zip::class)
        ->nullable()
        ->showCreateRelationButton(),

      Text::make(__('Speed'), 'speed'),
      Text::make(__('Ping'), 'ping'),

      Currency::make(__('Price'), 'price')
        ->min(0)
        ->max(1000)
        ->step(0.01)
        ->currency('USD'),
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

  /**
   * Get the displayable label of the resource.
   *
   * @return string
   */
  public static function label()
  {
    return __('Proxy');
  }

  /**
   * Get the displayable singular label of the resource.
   *
   * @return string
   */
  public static function singularLabel()
  {
    return __('Proxies');
  }

  /**
   * Get the text for the create resource button.
   *
   * @return string|null
   */
  public static function createButtonLabel()
  {
    return __('Create Proxy');
  }

  /**
   * Get the text for the update resource button.
   *
   * @return string|null
   */
  public static function updateButtonLabel()
  {
    return __('Save Changes');
  }

  /**
   * Get the text for the create & add another resource button.
   *
   * @return string|null
   */
  public static function createAnotherButtonLabel()
  {
    return __('Create & Add Another');
  }

    /**
   * Determine if the user can view the resource.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return bool
   */
}
