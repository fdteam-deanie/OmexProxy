<?php

namespace App\Nova\Resources;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use App\Traits\HasPermissions;

class Role extends Resource
{
  use HasPermissions;

  /**
   * The role for permission checks.
   *
   * @var string
   */
  protected $role = 'role';

  /**
   * The model the resource corresponds to.
   *
   * @var class-string<\App\Models\Role>
   */
  public static $model = \App\Models\Role::class;

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

        Text::make(__('Index'), 'index')
        ->sortable()
        ->rules('required', 'max:255'),

      new Panel(__('User Permissions'), $this->permissionsCollection('user')),
      new Panel(__('Role Permissions'), $this->permissionsCollection('role')),
      new Panel(__('Ticket Permissions'), $this->permissionsCollection('tickets')),
      new Panel(__('Complaint Permissions'), $this->permissionsCollection('complaint')),
      new Panel(__('Purchase Permissions'), $this->permissionsCollection('purchase')),
      new Panel(__('Transaction Permissions'), $this->permissionsCollection('transaction')),
      new Panel(__('Proxy Permissions'), $this->permissionsCollection('proxy')),
      new Panel(__('Hosting management Permissions'), $this->permissionsCollection('hostingmanagement')),
      new Panel(__('Proxy period Permissions'), $this->permissionsCollection('proxyrentperiod')),
      new Panel(__('Rate Permissions'), $this->permissionsCollection('rate')),
      new Panel(__('No Limit in day Permissions'), $this->permissionsCollection('nolimitinday')),
      new Panel(__('Trial period Permissions'), $this->permissionsCollection('trialperiod')),
      new Panel(__('Category  Permissions'), $this->permissionsCollection('category')),
      new Panel(__('Articles Permissions'), $this->permissionsCollection('articles')),

      $this->generatePermissionField("view_any_wallet", "View any wallet"),
      $this->generatePermissionField("view_any_proxyhosting", "View any proxy hosting"),
    ];
  }

  /**
   * Generate a collection of permission fields for a given entity.
   *
   * @param  string  $query
   * @return array
   */
  private function permissionsCollection($query)
  {
    return [
      $this->generatePermissionField("view_any_{$query}", "View any {$query}"),
      $this->generatePermissionField("view_{$query}", "View {$query}"),
      $this->generatePermissionField("create_{$query}", "Create {$query}"),
      $this->generatePermissionField("update_{$query}", "Update {$query}"),
      $this->generatePermissionField("edit_{$query}", "Edit {$query}"),
      $this->generatePermissionField("restore_{$query}", "Restore {$query}"),
      $this->generatePermissionField("delete_{$query}", "Delete {$query}"),
      $this->generatePermissionField("force_delete_{$query}", "Force delete {$query}"),
      $this->generatePermissionField("replicate_{$query}", "Replicate {$query}"),
      $this->generatePermissionField("attach_{$query}", "Attach {$query}"),
      $this->generatePermissionField("detach_{$query}", "Detach {$query}"),
      $this->generatePermissionField("add_{$query}", "Add {$query}"),
      $this->generatePermissionField("run_action_{$query}", "Run action {$query}"),
    ];
  }

  /**
   * Generate a permission field.
   *
   * @param  string  $key
   * @param  string  $label
   * @return \Laravel\Nova\Fields\Boolean
   */
  private function generatePermissionField(string $key, string $label)
  {
    return Boolean::make(__($label), $key)
      ->trueValue(1)
      ->falseValue(0)
      ->required()
      ->hideFromIndex()
      ->default($this->model()->{$key});
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
   * Get the label for the resource.
   *
   * @return string
   */
  public static function label()
  {
    return __('Roles');
  }

  /**
   * Get the singular label for the resource.
   *
   * @return string
   */
  public static function singularLabel()
  {
    return __('Role');
  }
}
