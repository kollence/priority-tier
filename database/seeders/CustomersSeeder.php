<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('customers')->insert([
            [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'phone' => '123-456-7890',
                'address' => '123 Main St, Anytown, USA'
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'phone' => '987-654-3210',
                'address' => '456 Elm St, Othertown, USA'
            ],
            [
                'name' => 'Alice Johnson',
                'email' => 'alice.johnson@example.com',
                'phone' => '555-123-4567',
                'address' => '789 Oak St, Sometown, USA'
            ],
            [
                'name' => 'Bob Brown',
                'email' => 'bob.brown@example.com',
                'phone' => '111-222-3333',
                'address' => '101 Pine St, Anycity, USA'
            ],
            [
                'name' => 'Carol White',
                'email' => 'carol.white@example.com',
                'phone' => '444-555-6666',
                'address' => '202 Maple St, Othercity, USA'
            ],
        ]);
    }
}
