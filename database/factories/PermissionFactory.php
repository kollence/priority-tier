<?php

namespace Database\Factories;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Permission>
 */
class PermissionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Permission::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $permissions = [
            'user-management' => 'Manage users and their permissions',
            'data-import' => 'Import data from files',
            'view-reports' => 'View system reports',
            'data-export' => 'Export data from system',
            'user-admin' => 'All permissions granted to admin users',
        ];

        $permissionName = $this->faker->unique()->randomElement(array_keys($permissions));

        return [
            'name' => $permissionName,
            'description' => $permissions[$permissionName],
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Indicate that the permission is for user management.
     */
    public function userManagement(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'user-management',
            'description' => 'Manage users and their permissions',
        ]);
    }

    /**
     * Indicate that the permission is for data import.
     */
    public function dataImport(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'data-import',
            'description' => 'Import data from files',
        ]);
    }
}
