<?php

namespace App\Traits;

use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
trait HasRole
{
  /**
   * Define the relationship to the Role model.
   *
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function role(): BelongsTo
  {
    return $this->belongsTo(Role::class, 'role_id');
  }


  public function getAllRoles(): BelongsTo
  {
    return $this->belongsTo(Role::class, 'role_id');
  }
  /**
   * Get the index of the role
   *
   * @return array
   */
  public function getRoleIds(): array
  {
    return $this->getAllRoles->pluck('index', 'name')->toArray();
  }

  /**
   * Get the index of the role.
   *
   * @return int|null
   */
  public function getRoleIndex(): int
  {
    return $this->role->index;
  }

  /**
   * Get the id of the role.
   *
   * @return string|null
   */
  public function roleId(): string
  {
    return $this->role->id;
  }

  public function hasBlock(): bool
  {
    $user = Auth::user();
    $permission = $user->as_block;
    if ($permission == 1) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * Get the name of the role.
   *
   * @return string|null
   */
  public function roleName(): string
  {
    return $this->role->name;
  }

  /**
   * Get the value of a role permission.
   * Return true if the permission equals 1, otherwise false
   * 
   * @param string $permissionName
   * @return bool
   */
  public function hasPermission(string $permissionName): bool
  {
    $permission = $this->role->$permissionName;

    if ($permission == 1) {
      return true;
    } else {
      return false;
    }
  }
}
