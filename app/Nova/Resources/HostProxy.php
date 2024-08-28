<?php

namespace App\Nova\Resources;

use App\Models\CountryIp;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use ShuvroRoy\NovaDynamicViews\CustomComponents;

class HostProxy extends Resource
{
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
        'id', 'ip_shown', 'domain', 'max_users_count', 'state->name', 'city->name', 'isp->name', 'zip->name', 'type->name'
    ];


    public function fieldsForIndex(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            Text::make(__("Name"), "name"),
            Text::make(__("Port"), "port"),
            Text::make(__('Type'), 'type->name'),
            Date::make(__('Added At'), 'created_at')
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

//            Text::make(__("Name"), "name"),
//
            Text::make(__("IP"), "ip_shown"),

            Text::make(__("Port"), "port"),

            Text::make(__("Domain"), "domain_shown"),

            Number::make(__("Max user's count"), "max_users_count")->default(env('MAX_PROXY_USERS_COUNT')),

            BelongsTo::make(__('Country Host'), 'countryIp', CountryHost::class)
                ->default(function ($request) {
                    $viaResourceId = $request->viaResourceId;
                    return $viaResourceId ?? null;
                })
                ->nullable(),

            BelongsTo::make(__('Continent'), 'continent', Continent::class)
                ->default(function ($request) {
                    $viaResourceId = $request->viaResourceId;
                    $host = CountryIp::find($viaResourceId);
                    return $host->country->continent->id ?? null;
                })
                ->nullable(),

            BelongsTo::make(__('Country'), 'country', Country::class)
                ->default(function ($request) {
                    $viaResourceId = $request->viaResourceId;
                    $host = CountryIp::find($viaResourceId);
                    return $host->country->id ?? null;
                })
                ->nullable(),

            BelongsTo::make(__('City'), 'city', City::class)
                ->default(function ($request) {
                    $viaResourceId = $request->viaResourceId;
                    $host = CountryIp::find($viaResourceId);
                    return $host->city->id ?? null;
                })
                ->nullable(),

            BelongsTo::make(__('ISP'), 'isp', ProxyISP::class)
                ->default(function ($request) {
                    $viaResourceId = $request->viaResourceId;
                    $host = CountryIp::find($viaResourceId);
                    return $host->isp->id ?? null;
                })
                ->nullable(),

            BelongsTo::make(__('Org'), 'org', ProxyOrg::class)
                ->default(function ($request) {
                    $viaResourceId = $request->viaResourceId;
                    $host = CountryIp::find($viaResourceId);
                    return $host->org->id ?? null;
                })
                ->nullable(),

            BelongsTo::make(__('ZIP'), 'zip', Zip::class)
                ->default(function ($request) {
                    $viaResourceId = $request->viaResourceId;
                    $host = CountryIp::find($viaResourceId);
                    return $host->zip->id ?? null;
                })
                ->nullable(),

            BelongsTo::make(__('Type'), 'type', ProxyType::class)
                ->nullable(),

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
        return __('Attach Proxy');
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

    public static function redirectAfterCreate(NovaRequest $request, $resource)
    {
        if ($request->viaResource) {
            return "/resources/{$request->viaResource}/{$request->viaResourceId}";
        }

        return parent::redirectAfterCreate($request, $resource);
    }

    public static function redirectAfterUpdate(NovaRequest $request, $resource)
    {
        if ($request->viaResource) {
            return "/resources/{$request->viaResource}/{$request->viaResourceId}";
        }

        return parent::redirectAfterUpdate($request, $resource);
    }
}
