<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OperatorCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('operator_cards')->insert([
            [
                'name' => 'Tri',
                'thumbnail' => 'operator_tri.png',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                'name' => 'XL',
                'thumbnail' => 'operator_xl.png',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                'name' => 'Telkomsel',
                'thumbnail' => 'operator_telkomsel.png',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),

            ],

        ]);
    }
}
