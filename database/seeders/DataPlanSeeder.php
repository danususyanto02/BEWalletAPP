<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('data_plans')->insert([
            [
                'name' => '24GB',
                'price' => 60000,
                'operator_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                'name' => '32GB',
                'price' => 70000,
                'operator_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                'name' => '15 GB',
                'price' => 6000,
                'operator_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                'name' => '41 GB',
                'price' => 66000,
                'operator_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                'name' => '64 GB',
                'price' => 52000,
                'operator_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                'name' => '55 GB',
                'price' => 86000,
                'operator_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                'name' => '48 GB',
                'price' => 77000,
                'operator_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),

            ],

        ]);
    }
}
