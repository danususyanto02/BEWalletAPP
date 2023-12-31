<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('payment_methods')->insert([
            [
                'name' => 'WalletAPP',
                'code' => 'walletapp',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                'name' => 'Bank Mandiri',
                'code' => 'mandiri_va',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                'name' => 'Bank BNI',
                'code' => 'bni_va',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                'name' => 'Bank BCA',
                'code' => 'bca_va',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),

            ],
        ]);
    }
}
