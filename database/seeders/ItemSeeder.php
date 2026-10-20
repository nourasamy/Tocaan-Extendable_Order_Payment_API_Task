<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['name' => 'Laptop', 'price' => 999.99],
            ['name' => 'Monitor', 'price' => 299.99],
            ['name' => 'Keyboard', 'price' => 79.99],
            ['name' => 'Mouse', 'price' => 29.99],
            ['name' => 'USB Cable', 'price' => 9.99],
            ['name' => 'Headphones', 'price' => 149.99],
            ['name' => 'Webcam', 'price' => 59.99],
            ['name' => 'External HDD', 'price' => 89.99],
            ['name' => 'SSD 500GB', 'price' => 59.99],
            ['name' => 'RAM 16GB', 'price' => 129.99],
        ];

        foreach ($items as $item) {
            DB::table('items')->insert([
                'name' => $item['name'],
                'price' => $item['price'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
