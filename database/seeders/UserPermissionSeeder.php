<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all permissions
        $allPermissions = Permission::all();
        $userAdminPermission = Permission::where('name', 'user-admin')->first();

        // Get user management permission
        $userManagementPermission = Permission::where('name', 'user-management')->first();

        // Get data import permission
        $dataImportPermission = Permission::where('name', 'import-orders')->first();

        // Assign all permissions to admin
        $admin = User::where('email', 'admin@mail.com')->first();
        $admin->permissions()->sync([$userAdminPermission->id]);

        // Assign specific permissions to manager
        $manager = User::where('email', 'manager@mail.com')->first();
        $manager->permissions()->sync([
            $userManagementPermission->id,
            Permission::where('name', 'import-orders')->first()->id,
            Permission::where('name', 'import-customers')->first()->id,
        ]);

        // Assign basic permission to regular user
        $user = User::where('email', 'user@mail.com')->first();
        $user->permissions()->sync([
            $dataImportPermission->id,
            Permission::where('name', 'import-products')->first()->id,
        ]);

        // Randomly assign permissions to other users
        User::whereNotIn('email', [
            'admin@mail.com',
            'manager@mail.com',
            'user@mail.com'
        ])->each(function ($user) use ($allPermissions) {
            $randomPermissions = $allPermissions->random(rand(1, 3));
            $user->permissions()->sync($randomPermissions->pluck('id')->toArray());
        });
    }
}
