<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subscriber;
use App\Models\SubscriberProfile;

class SubscriberProfileSeeder extends Seeder
{
    public function run()
    {
        $subscribers = Subscriber::all();

        foreach ($subscribers as $subscriber) {
            SubscriberProfile::create([
                'subscriber_id' => $subscriber->id,
                'first_name'    => 'First' . $subscriber->id,
                'last_name'     => 'Last' . $subscriber->id,
                'country'       => 'Country' . $subscriber->id,
                'city'          => 'City' . $subscriber->id,
                'address'       => 'Address ' . $subscriber->id,
                'zip_code'      => 'Zip' . $subscriber->id,
                'phone_number'  => '1234567890',
                'avatar'        => null,
            ]);
        }
    }
}
