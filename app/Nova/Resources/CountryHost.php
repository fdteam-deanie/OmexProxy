<?php

namespace App\Nova\Resources;

use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Illuminate\Database\Eloquent\Builder;
use Proxy\GenerateProxy\GenerateProxy;

class CountryHost extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Country>
     */
    public static $model = \App\Models\CountryIp::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * Indicates if the resource should be displayed in the sidebar.
     *
     * @var bool
     */
    public static $displayInNavigation = false;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name',
    ];


    public function fieldsForIndex(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            Text::make(__('Name'), 'name')
                ->sortable(),

            Text::make(__('IP'), 'ip')
                ->sortable(),

            Text::make(__('Domain'), 'domain')
                ->sortable(),

            Text::make(__('Username'), 'username')
                ->sortable(),

            Text::make(__('Password'), 'password')
                ->sortable(),

            Text::make(__('Proxies count'), 'proxies_count')
                ->sortable(),
        ];
    }

    public function fields(NovaRequest $request)
    {
        $countryFields = [];
        if(empty($request->viaResource)) {
            $countryFields = [
                BelongsTo::make(__('Country'), 'country', Country::class)
                    ->nullable(),
            ];
        }
        return [
            new Panel(__('Host Information') , [
                ID::make()->sortable(),

                Text::make(__('Name'), 'name')
                    ->sortable()
                    ->rules('required', 'max:255'),

                Text::make(__('IP'), 'ip')
                    ->sortable()
                    ->rules('required', 'max:255'),

                Text::make(__('Domain'), 'domain')
                    ->sortable()
                    ->rules('required', 'max:255'),

                ...$countryFields,

                BelongsTo::make(__('State'), 'state', State::class)
                    ->nullable()->dependsOn('country', function (BelongsTo $field, NovaRequest $request, FormData $formData) {
                        $countryId = (int) $formData->resource(Country::uriKey(), $formData->country);
                        $field->relatableQueryUsing(function (NovaRequest $request, Builder $query) use ($countryId) {
                            $query->where('country_id', $countryId);
                        });
                    })
                    ->showCreateRelationButton(),

                BelongsTo::make(__('City'), 'city', City::class)
                    ->nullable()->dependsOn('country', function (BelongsTo $field, NovaRequest $request, FormData $formData) {
                        $countryId = (int) $formData->resource(Country::uriKey(), $formData->country);
                        $field->relatableQueryUsing(function (NovaRequest $request, Builder $query) use ($countryId) {
                            $query->where('country_id', $countryId);
                        });
                    })
                    ->showCreateRelationButton(),

                BelongsTo::make(__('ZIP'), 'zip', Zip::class)
                    ->nullable()->dependsOn('country', function (BelongsTo $field, NovaRequest $request, FormData $formData) {
                        $countryId = (int) $formData->resource(Country::uriKey(), $formData->country);
                        $field->relatableQueryUsing(function (NovaRequest $request, Builder $query) use ($countryId) {
                            $query->where('country_id', $countryId);
                        });
                    })
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

                Number::make(__('Speed'), 'speed'),
                Number::make(__('Ping'), 'ping'),


                Text::make(__('Username'), 'username')
                    ->sortable()
                    ->rules('required'),

                Text::make(__('Password'), 'password')
                    ->sortable()
                    ->rules('required'),

                GenerateProxy::make()->onlyOnDetail()
            ]),


            HasMany::make(__('Proxies'), 'proxies', HostProxy::class),

        ];
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        $query->withCount('proxies');
        return parent::indexQuery($request, $query);
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

    public static function createButtonLabel()
    {
        return __('Attach Host');
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
}
