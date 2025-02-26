<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            ['bitrate' => 64,  'max_listeners' => 10,  'sell_price' => 3.00],
            ['bitrate' => 64,  'max_listeners' => 25,  'sell_price' => 7.50],
            ['bitrate' => 64,  'max_listeners' => 50,  'sell_price' => 15.00],
            ['bitrate' => 64,  'max_listeners' => 100, 'sell_price' => 30.00],
            ['bitrate' => 96,  'max_listeners' => 10,  'sell_price' => 4.50],
            ['bitrate' => 96,  'max_listeners' => 25,  'sell_price' => 12.00],
            ['bitrate' => 96,  'max_listeners' => 50,  'sell_price' => 22.00],
            ['bitrate' => 96,  'max_listeners' => 100, 'sell_price' => 48.00],
            ['bitrate' => 128, 'max_listeners' => 10,  'sell_price' => 6.00],
            ['bitrate' => 128, 'max_listeners' => 25,  'sell_price' => 15.00],
            ['bitrate' => 128, 'max_listeners' => 50,  'sell_price' => 30.00],
            ['bitrate' => 128, 'max_listeners' => 100, 'sell_price' => 60.00],
            ['bitrate' => 256, 'max_listeners' => 10,  'sell_price' => 12.00],
            ['bitrate' => 256, 'max_listeners' => 25,  'sell_price' => 30.00],
            ['bitrate' => 256, 'max_listeners' => 50,  'sell_price' => 60.00],
            ['bitrate' => 256, 'max_listeners' => 100, 'sell_price' => 120.00],
            ['bitrate' => 320, 'max_listeners' => 10,  'sell_price' => 15.00],
            ['bitrate' => 320, 'max_listeners' => 25,  'sell_price' => 40.00],
            ['bitrate' => 320, 'max_listeners' => 50,  'sell_price' => 80.00],
            ['bitrate' => 320, 'max_listeners' => 100, 'sell_price' => 160.00],
        ];

        DB::table('plans')->insert($plans);
    }
}
