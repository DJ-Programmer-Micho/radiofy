<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subscriber;
use App\Models\IcecastConfiguration;
use App\Models\Plan;

class IcecastConfigurationSeeder extends Seeder
{
    public function run()
    {
        $subscribers = Subscriber::all();
        $plans = Plan::all();

        // Check that there is at least one plan
        if ($plans->isEmpty()) {
            $this->command->error('No plans found. Please seed the plans table first.');
            return;
        }

        // Define the radio names for each subscriber
        $radioNames = ['radio_one', 'radio_two', 'radio_three'];
        $i = 0;

        foreach ($subscribers as $subscriber) {
            // Select a random plan from the available plans
            $plan = $plans->random();

            IcecastConfiguration::create([
                'subscriber_id'   => $subscriber->id,
                'plan_id'         => $plan->id,
                'radio_name'      => $radioNames[$i] ?? 'radio_' . $subscriber->username,
                'location'        => 'Location for ' . $subscriber->username,
                'server_admin'    => 'admin@example.com',
                'server_password' => 'password',
                'burst_size'      => 1024,
                'port'            => 8000,
                'bind_address'    => '0.0.0.0',
                'source_password' => 'sourcepass',
                'relay_password'  => null,
                'admin_password'  => 'adminpass',
                'fallback_mount'  => '/fallback',
                'genre'           => 'Pop',
                'description'     => 'A sample radio stream',
                'bitrate'         => $plan->bitrate, // from the selected plan
                'sample_rate'     => 44100,
                'status'          => 'active',
            ]);
            $i++;
        }
    }
}
