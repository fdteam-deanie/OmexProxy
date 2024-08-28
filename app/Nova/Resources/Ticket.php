<?php

namespace App\Nova\Resources;

use App\Nova\Resource;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Proxy\SupportChat\SupportChat;
use ShuvroRoy\NovaTabs\Tab;
use ShuvroRoy\NovaTabs\Tabs;
use App\Traits\HasPermissions;

class Ticket extends Resource
{

  use HasPermissions;

  /**
   * The role for permission checks.
   *
   * @var string
   */
  protected $role = 'tickets';

  /**
   * The model the resource corresponds to.
   *
   * @var class-string<\App\Models\Support\Ticket>
   */
  public static $model = \App\Models\Support\Ticket::class;

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
    'subject',
    'status',
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
      Tabs::make('Ticket', [
        Tab::make(__('Ticket Details'), [
          ID::make()->sortable(),
          Text::make(__('Subject'), 'subject'),
          Select::make(__('Status'), 'status')->options([
            '0' => 'Открыт',
            '1' => 'В процессе',
            '2' => 'Закрыт',
          ])->displayUsingLabels(),
          DateTime::make(__('Date'), 'created_at')->sortable(),
          Image::make(__('Image'), 'image')
            ->disk('public')
            ->path('tickets')
            ->prunable(),
          BelongsTo::make(__('User'), 'user', User::class),
        ]),
        Tab::make(__('Chat'), [
          SupportChat::make()
        ])
      ])
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
}
