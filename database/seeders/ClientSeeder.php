<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = [
            ['name' => 'John Doe', 'phone' => '1234567890'],
            ['name' => 'Jane Smith', 'phone' => '0987654321'],
            ['name' => 'Michael Johnson', 'phone' => '1122334455'],
            ['name' => 'Emily Williams', 'phone' => '5544332211'],
            ['name' => 'David Brown', 'phone' => '9876543210'],
            ['name' => 'Sarah Davis', 'phone' => '1357924680'],
            ['name' => 'James Miller', 'phone' => '2468013579'],
            ['name' => 'Jennifer Wilson', 'phone' => '3691234567'],
            ['name' => 'Robert Moore', 'phone' => '7654321098'],
            ['name' => 'Lisa Taylor', 'phone' => '1029384756'],
        ];

        foreach ($clients as $client) {
            DB::table('clients')->insert([
                'name' => $client['name'],
                'phone' => $client['phone'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
