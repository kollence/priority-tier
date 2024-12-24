<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'name' => 'Product 1',
                'sku' => 'SKU001',
                'description' => 'Product 1 Description',
                'cost' => 10.50,
                'price' => 15.00,
                'stock' => 100,
            ],
            [
                'name' => 'Product 2',
                'sku' => 'SKU002',
                'description' => 'Product 2 Description',
                'cost' => 20.00,
                'price' => 30.00,
                'stock' => 200,
            ],
            [
                'name' => 'Product 3',
                'sku' => 'SKU003',
                'description' => 'Product 3 Description',
                'cost' => 5.75,
                'price' => 10.00,
                'stock' => 150,
            ],
            [
                'name' => 'Product 4',
                'sku' => 'SKU004',
                'description' => 'Product 4 Description',
                'cost' => 15.00,
                'price' => 25.00,
                'stock' => 175,
            ],
            [
                'name' => 'Product 5',
                'sku' => 'SKU005',
                'description' => 'Product 5 Description',
                'cost' => 12.50,
                'price' => 20.00,
                'stock' => 125,
            ],
        ]);
    }
}
