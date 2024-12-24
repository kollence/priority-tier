<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('orders')->insert([
            [
            'order_date' => '2023-10-01',
            'channel' => 'PT',
            'sku' => 'SKU001',
            'item_description' => 'Item 1 Description',
            'origin' => 'USA',
            'so_num' => 'SO001',
            'cost' => 100.00,
            'shipping_cost' => 10.00,
            'total_price' => 110.00,
            ],
            [
            'order_date' => '2023-10-02',
            'channel' => 'Amazon',
            'sku' => 'SKU002',
            'item_description' => 'Item 2 Description',
            'origin' => 'Canada',
            'so_num' => 'SO002',
            'cost' => 200.00,
            'shipping_cost' => 20.00,
            'total_price' => 220.00,
            ],
            [
            'order_date' => '2023-10-03',
            'channel' => 'PT',
            'sku' => 'SKU003',
            'item_description' => 'Item 3 Description',
            'origin' => 'Mexico',
            'so_num' => 'SO003',
            'cost' => 300.00,
            'shipping_cost' => 30.00,
            'total_price' => 330.00,
            ],
            [
            'order_date' => '2023-10-04',
            'channel' => 'Amazon',
            'sku' => 'SKU004',
            'item_description' => 'Item 4 Description',
            'origin' => 'UK',
            'so_num' => 'SO004',
            'cost' => 400.00,
            'shipping_cost' => 40.00,
            'total_price' => 440.00,
            ],
            [
            'order_date' => '2023-10-05',
            'channel' => 'PT',
            'sku' => 'SKU005',
            'item_description' => 'Item 5 Description',
            'origin' => 'Germany',
            'so_num' => 'SO005',
            'cost' => 500.00,
            'shipping_cost' => 50.00,
            'total_price' => 550.00,
            ],
        ]);
    }
}
