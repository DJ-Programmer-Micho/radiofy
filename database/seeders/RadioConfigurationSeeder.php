<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RadioConfiguration;
use App\Models\RadioConfigurationProfile;
use App\Models\Subscriber;
use App\Models\Plan;
use App\Models\Genre;

class RadioConfigurationSeeder extends Seeder
{
    public function run()
    {
        $subscribers = Subscriber::all();
        $plans = Plan::all();
        $genres = Genre::all();

        if ($subscribers->isEmpty() || $plans->isEmpty() || $genres->isEmpty()) {
            $this->command->error('Missing necessary data. Please seed subscribers, plans, and genres first.');
            return;
        }

        // Define some example radio names.
        $radioNames = ['radio_one', 'radio_two', 'radio_three'];
        $i = 0;

        foreach ($subscribers as $subscriber) {
            // Choose a random plan and genre.
            $plan = $plans->random();
            $genre = $genres->random();

            // Use a preset name or fallback to a default based on subscriber username.
            $radioName = $radioNames[$i] ?? 'radio_' . $subscriber->username;

            // Create the radio configuration.
            $radio = RadioConfiguration::create([
                'subscriber_id'   => $subscriber->id,
                'plan_id'         => $plan->id,
                'genre_id'        => $genre->id,
                'radio_name'      => $radioName,
                'source'          => 'source_' . $subscriber->id,
                'source_password' => 'password',
                'fallback_mount'  => '/fallback',
                'status'          => 1,  // or 'active' if you use strings
            ]);

            // Create a profile for this radio.
            $profile = RadioConfigurationProfile::create([
                'radio_id'  => $radio->id,
                'image'     => 'default.png',   // a default image path or URL
                'frequency' => '101.1 FM',        // example frequency
                'location'  => $subscriber->location ?? 'Unknown',
                'description' => 'This is the profile for ' . $radioName,
            ]);
            $profile->languages()->sync([1, 2, 3]);
            $i++;
        }
    }
}
