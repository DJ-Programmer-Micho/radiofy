<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Hash;

class SubscriberSeeder extends Seeder
{
    public function run()
    {
        // Create three sample subscribers
        Subscriber::create([
            'username' => 'subscriber1',
            'email'    => 'subscriber1@example.com',
            'password' => Hash::make('123456789'),
            'uid'      => 'UID1',
            'status'   => true,
            'email_verify' => true,
            'phone_verify' => true,
        ]);

        Subscriber::create([
            'username' => 'subscriber2',
            'email'    => 'subscriber2@example.com',
            'password' => Hash::make('123456789'),
            'uid'      => 'UID2',
            'status'   => true,
            'email_verify' => true,
            'phone_verify' => true,
        ]);

        Subscriber::create([
            'username' => 'subscriber3',
            'email'    => 'subscriber3@example.com',
            'password' => Hash::make('123456789'),
            'uid'      => 'UID3',
            'status'   => true,
            'email_verify' => true,
            'phone_verify' => true,
        ]);
    }
}
