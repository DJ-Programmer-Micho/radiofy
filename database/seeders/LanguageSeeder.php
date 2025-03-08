<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Language;

class LanguageSeeder extends Seeder
{
    public function run()
    {
        $languages = [
            ['code' => 'en', 'name' => 'English'],
            ['code' => 'ar', 'name' => 'Arabic'],
            ['code' => 'ku', 'name' => 'Kurdish'],
        ];

        foreach ($languages as $lang) {
            Language::create($lang);
        }
    }
}
