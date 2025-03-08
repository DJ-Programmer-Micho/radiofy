<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Genre;
use App\Models\GenreTranslater;

class GenreSeeder extends Seeder
{
    public function run()
    {
        // Define genres with their translations
        $genres = [
            [
                'priority' => 1,
                'status' => 1,
                'image' => null,
                'translations' => [
                    ['locale' => 'en', 'name' => 'Pop', 'slug' => 'pop'],
                    ['locale' => 'ar', 'name' => 'بوب', 'slug' => 'pop-ar'],
                    ['locale' => 'ku', 'name' => 'Pop', 'slug' => 'pop-ku'],
                ],
            ],
            [
                'priority' => 2,
                'status' => 1,
                'image' => null,
                'translations' => [
                    ['locale' => 'en', 'name' => 'HipHop', 'slug' => 'hiphop'],
                    ['locale' => 'ar', 'name' => 'هيبهوب', 'slug' => 'hiphop-ar'],
                    ['locale' => 'ku', 'name' => 'HipHop', 'slug' => 'hiphop-ku'],
                ],
            ],
            [
                'priority' => 3,
                'status' => 1,
                'image' => null,
                'translations' => [
                    ['locale' => 'en', 'name' => 'News', 'slug' => 'news'],
                    ['locale' => 'ar', 'name' => 'أخبار', 'slug' => 'news-ar'],
                    ['locale' => 'ku', 'name' => 'News', 'slug' => 'news-ku'],
                ],
            ],
        ];

        foreach ($genres as $data) {
            // Create the genre record
            $genre = Genre::create([
                'priority' => $data['priority'],
                'status' => $data['status'],
                'image' => $data['image'],
            ]);

            // Create translations for this genre
            foreach ($data['translations'] as $translation) {
                GenreTranslater::create([
                    'genre_id' => $genre->id,
                    'locale'   => $translation['locale'],
                    'name'     => $translation['name'],
                    'slug'     => $translation['slug'],
                ]);
            }
        }
    }
}
