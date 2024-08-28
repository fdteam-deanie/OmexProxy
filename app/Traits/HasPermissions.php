<?php
namespace App\Traits;

trait HasPermissions
{
  /**
   * Determine if the user can view the resource.
   *
   * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
   * @return bool
   */
  public static function authorizedToViewAny($request)
  {
    if (!$request->user()) {
      return false;
    }

    if ($request->user()->hasBlock() == 1) {
      return false;
    }

    return $request->user()->hasPermission("view_any_" . (new static)->getRole());
  }

  /**
   * Determine if the user can view the resource.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return bool
   */
  public function authorizedToView($request)
  {
    if (!$request->user() || $request->user()->hasBlock()) {
      return false;
    }

    if ($request->user()->hasBlock() == 1) {
      return false;
    }

    return $request->user()->hasPermission("view_" . $this->getRole());
  }

  /**
   * Determine if the user can create the resource.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return bool
   */
  public static function authorizedToCreate($request)
  {
    if (!$request->user()) {
      return false;
    }

    if ($request->user()->hasBlock() == 1) {
      return false;
    }

    return $request->user()->hasPermission("create_" . (new static)->getRole());
  }

  /**
   * Determine if the user can update the resource.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return bool
   */
  public function authorizedToUpdate($request)
  {
    if (!$request->user()) {
      return false;
    }

    if ($request->user()->hasBlock() == 1) {
      return false;
    }

    return $request->user()->hasPermission("update_" . $this->getRole());
  }

  /**
   * Determine if the user can edit the resource.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return bool
   */
  public function authorizedToEdit($request)
  {
    if (!$request->user()) {
      return false;
    }

    if ($request->user()->hasBlock() == 1) {
      return false;
    }

    return $request->user()->hasPermission("edit_" . $this->getRole());
  }

  /**
   * Determine if the user can restore the resource.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return bool
   */
  public function authorizedToRestore($request)
  {
    if (!$request->user()) {
      return false;
    }

    if ($request->user()->hasBlock() == 1) {
      return false;
    }

    return $request->user()->hasPermission("restore_" . $this->getRole());
  }

  /**
   * Determine if the user can delete the resource.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return bool
   */
  public function authorizedToDelete($request)
  {
    if (!$request->user()) {
      return false;
    }

    if ($request->user()->hasBlock() == 1) {
      return false;
    }

    return $request->user()->hasPermission("delete_" . $this->getRole());
  }

  /**
   * Determine if the user can force delete the resource.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return bool
   */
  public function authorizedToForceDelete($request)
  {
    if (!$request->user()) {
      return false;
    }

    if ($request->user()->hasBlock() == 1) {
      return false;
    }

    return $request->user()->hasPermission("force_delete_" . $this->getRole());
  }

  /**
   * Determine if the user can replicate the resource.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  mixed  $model
   * @return bool
   */
  public function authorizedToReplicate($request)
  {
    if (!$request->user()) {
      return false;
    }

    if ($request->user()->hasBlock() == 1) {
      return false;
    }

    return $request->user()->hasPermission("replicate_" . $this->getRole());
  }

  /**
   * Determine if the user can attach related resources.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  mixed  $model
   * @return bool
   */
  public function authorizedToAttach($request, $model)
  {
    if (!$request->user()) {
      return false;
    }

    if ($request->user()->hasBlock() == 1) {
      return false;
    }

    return $request->user()->hasPermission("attach_" . $this->getRole());
  }

  /**
   * Determine if the user can detach related resources.
   *
   * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
   * @param  mixed  $model
   * @param  mixed  $relationship
   * @return bool
   */
  public function authorizedToDetach($request, $model, $relationship)
  {
    if (!$request->user()) {
      return false;
    }

    if ($request->user()->hasBlock() == 1) {
      return false;
    }

    return $request->user()->hasPermission("detach_" . $this->getRole());
  }

  /**
   * Determine if the user can add related resources.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  mixed  $model
   * @return bool
   */
  public function authorizedToAdd($request, $model)
  {
    if (!$request->user()) {
      return false;
    }

    if ($request->user()->hasBlock() == 1) {
      return false;
    }

    return $request->user()->hasPermission("add_" . $this->getRole());
  }

  /**
   * Determine if the user can run actions on the resource.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  mixed  $model
   * @return bool
   */
  public function authorizedToRun($request, $model)
  {
    if (!$request->user()) {
      return false;
    }

    if ($request->user()->hasBlock() == 1) {
      return false;
    }

    return $request->user()->hasPermission("run_action_" . $this->getRole());
  }

  /**
   * Get the role for permission checks.
   *
   * @return string
   */
  public function getRole()
  {
    return $this->role;
  }
}
