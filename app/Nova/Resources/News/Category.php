<?php

namespace App\Nova\Resources\News;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource;
use App\Traits\HasPermissions;
class Category extends Resource
{
  use HasPermissions;

  /**
   * The role for permission checks.
   *
   * @var string
   */
  protected $role = 'category';

  /**
   * The model the resource corresponds to.
   *
   * @var class-string<\App\Models\News\Category>
   */
  public static $model = \App\Models\News\Category::class;

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

      Text::make(__('Name'), 'name')
        ->sortable()
        ->rules('required', 'max:255'),

      Boolean::make(__('Is Published'), 'is_published')
        ->sortable()
        ->rules('required'),

      HasMany::make(__('Articles'), 'articles', Article::class)
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
    return __('Categories');
  }

  public static function singularLabel()
  {
    return __('Category');
  }
}
