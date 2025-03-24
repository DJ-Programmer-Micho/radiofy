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
            ['bitrate' => 64,  'max_listeners' => 10,  'sell_price_monthly' => 3.00, 'sell_price_yearly' => 3.00, 'priority' => 1, 'support' => 0, 'ribbon' => 0, 'rib_text' => 'Popular'],
            ['bitrate' => 64,  'max_listeners' => 25,  'sell_price_monthly' => 7.50, 'sell_price_yearly' => 7.50, 'priority' => 2, 'support' => 0, 'ribbon' => 0, 'rib_text' => 'Popular'],
            ['bitrate' => 64,  'max_listeners' => 50,  'sell_price_monthly' => 15.00, 'sell_price_yearly' => 15.00, 'priority' => 3, 'support' => 1, 'ribbon' => 1, 'rib_text' => 'Popular'],
            ['bitrate' => 64,  'max_listeners' => 100, 'sell_price_monthly' => 30.00, 'sell_price_yearly' => 30.00, 'priority' => 4, 'support' => 1, 'ribbon' => 1, 'rib_text' => 'Popular'],
            ['bitrate' => 96,  'max_listeners' => 10,  'sell_price_monthly' => 4.50, 'sell_price_yearly' => 4.50, 'priority' => 5, 'support' => 0, 'ribbon' => 0, 'rib_text' => 'Popular'],
            ['bitrate' => 96,  'max_listeners' => 25,  'sell_price_monthly' => 12.00, 'sell_price_yearly' => 12.00, 'priority' => 6, 'support' => 0, 'ribbon' => 0, 'rib_text' => 'Popular'],
            ['bitrate' => 96,  'max_listeners' => 50,  'sell_price_monthly' => 22.00, 'sell_price_yearly' => 22.00, 'priority' => 7, 'support' => 1, 'ribbon' => 1, 'rib_text' => 'Popular'],
            ['bitrate' => 96,  'max_listeners' => 100, 'sell_price_monthly' => 48.00, 'sell_price_yearly' => 48.00, 'priority' => 8, 'support' => 1, 'ribbon' => 1, 'rib_text' => 'Popular'],
            ['bitrate' => 128, 'max_listeners' => 10,  'sell_price_monthly' => 6.00, 'sell_price_yearly' => 6.00, 'priority' => 9, 'support' => 0, 'ribbon' => 0, 'rib_text' => 'Popular'],
            ['bitrate' => 128, 'max_listeners' => 25,  'sell_price_monthly' => 15.00, 'sell_price_yearly' => 15.00, 'priority' => 10, 'support' => 0, 'ribbon' => 0, 'rib_text' => 'Popular'],
            ['bitrate' => 128, 'max_listeners' => 50,  'sell_price_monthly' => 30.00, 'sell_price_yearly' => 30.00, 'priority' => 11, 'support' => 1, 'ribbon' => 1, 'rib_text' => 'Popular'],
            ['bitrate' => 128, 'max_listeners' => 100, 'sell_price_monthly' => 60.00, 'sell_price_yearly' => 60.00, 'priority' => 12, 'support' => 1, 'ribbon' => 1, 'rib_text' => 'Popular'],
            ['bitrate' => 256, 'max_listeners' => 10,  'sell_price_monthly' => 12.00, 'sell_price_yearly' => 12.00, 'priority' => 13, 'support' => 0, 'ribbon' => 0, 'rib_text' => 'Popular'],
            ['bitrate' => 256, 'max_listeners' => 25,  'sell_price_monthly' => 30.00, 'sell_price_yearly' => 30.00, 'priority' => 14, 'support' => 0, 'ribbon' => 0, 'rib_text' => 'Popular'],
            ['bitrate' => 256, 'max_listeners' => 50,  'sell_price_monthly' => 60.00, 'sell_price_yearly' => 60.00, 'priority' => 15, 'support' => 1, 'ribbon' => 1, 'rib_text' => 'Popular'],
            ['bitrate' => 256, 'max_listeners' => 100, 'sell_price_monthly' => 120.00, 'sell_price_yearly' => 120.00, 'priority' => 16, 'support' => 1, 'ribbon' => 1, 'rib_text' => 'Popular'],
            ['bitrate' => 320, 'max_listeners' => 10,  'sell_price_monthly' => 15.00, 'sell_price_yearly' => 15.00, 'priority' => 17, 'support' => 0, 'ribbon' => 0, 'rib_text' => 'Popular'],
            ['bitrate' => 320, 'max_listeners' => 25,  'sell_price_monthly' => 40.00, 'sell_price_yearly' => 40.00, 'priority' => 18, 'support' => 0, 'ribbon' => 0, 'rib_text' => 'Popular'],
            ['bitrate' => 320, 'max_listeners' => 50,  'sell_price_monthly' => 80.00, 'sell_price_yearly' => 80.00, 'priority' => 19, 'support' => 1, 'ribbon' => 1, 'rib_text' => 'Popular'],
            ['bitrate' => 320, 'max_listeners' => 100, 'sell_price_monthly' => 160.00, 'sell_price_yearly' => 160.00, 'priority' => 20, 'support' => 1, 'ribbon' => 1, 'rib_text' => 'Popular'],
        ];

        DB::table('plans')->insert($plans);
    }
}
