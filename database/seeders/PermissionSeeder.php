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
                'name' => 'user-admin',
                'description' => 'All permissions granted to admin users'
            ],
            [
                'name' => 'import-orders',
                'description' => 'Import orders data'
            ],
            [
                'name' => 'import-products',
                'description' => 'Import products data'
            ],
            [
                'name' => 'import-customers',
                'description' => 'Import customers data'
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
