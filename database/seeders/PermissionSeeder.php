<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'user-management',
                'description' => 'Manage users and their permissions'
            ],
            [
                'name' => 'data-import',
                'description' => 'Import data from files'
            ],
            [
                'name' => 'view-reports',
                'description' => 'View system reports'
            ],
            [
                'name' => 'manage-content',
                'description' => 'Manage system content'
            ],
            [
                'name' => 'data-export',
                'description' => 'Export data from system'
            ],
            [
                'name' => 'user-admin',
                'description' => 'All permissions granted to admin users'
            ]
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
