<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tips')->insert([
            [
                'title' => "Menyimpan uang dengan bijak",
                'thumbnail' => "nabung.jpg",
                'url' => "google.co.id",
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => "Investasi Untuk Masa Depan",
                'thumbnail' => "investasi.jpg",
                'url' => "facebook.com",
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => "Instrument Investasi Terbaik",
                'thumbnail' => "reksadana.jpg",
                'url' => "twitter.com",
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
