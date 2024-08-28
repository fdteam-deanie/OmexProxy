<?php

namespace App\Nova\Resources\News;

use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Traits\HasPermissions;
class Article extends Resource
{
  use HasPermissions;

  /**
   * The role for permission checks.
   *
   * @var string
   */
  protected $role = 'articles';

  /**
   * The model the resource corresponds to.
   *
   * @var class-string<\App\Models\News\Article>
   */
  public static $model = \App\Models\News\Article::class;

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

      BelongsTo::make(__('Category'), 'category', Category::class)
        ->sortable()
        ->rules('required'),

      Text::make(__('Title'), 'title')
        ->sortable()
        ->rules('required', 'max:255'),

      Textarea::make(__('Short Description'), 'short_description')
        ->sortable()
        ->rules('required', 'max:250'),

      Markdown::make(__('Body'), 'body'),

      Image::make(__('Image'), 'image')
        ->disk('public')
        ->path('news')
        ->prunable(),

      Boolean::make(__('Is Published'), 'is_published')
        ->sortable()
        ->rules('required'),
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
    return __('Articles');
  }

  public static function singularLabel()
  {
    return __('Article');
  }
}
