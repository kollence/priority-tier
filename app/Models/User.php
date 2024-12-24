<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

     /**
     * The permissions that belong to the user.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'user_permissions')
                    ->withTimestamps();
    }

    /**
     * Check if user has a specific permission
     */
    public function hasPermission(array|string $permissions): bool
    {
        if (is_array($permissions)) {
            return $this->permissions->whereIn('name', $permissions)->isNotEmpty();
        }

        return $this->permissions->contains('name', $permissions);
    }

    /**
     * Check if user has any of the given permissions
     */
    public function hasAnyPermission(array $permissions): bool
    {
        return $this->permissions->whereIn('name', $permissions)->isNotEmpty();
    }

    /**
     * Check if user has all of the given permissions
     */
    public function hasAllPermissions(array $permissions): bool
    {
        return $this->permissions->whereIn('name', $permissions)->count() === count($permissions);
    }

    /**
     * Assign permissions to user
     */
    public function assignPermissions(array|int|Permission $permissions): void
    {
        if (!is_array($permissions)) {
            $permissions = [$permissions];
        }

        $this->permissions()->sync($permissions, false);
    }

    /**
     * Remove permissions from user
     */
    public function removePermissions(array|int|Permission $permissions): void
    {
        if (!is_array($permissions)) {
            $permissions = [$permissions];
        }

        $this->permissions()->detach($permissions);
    }
}
