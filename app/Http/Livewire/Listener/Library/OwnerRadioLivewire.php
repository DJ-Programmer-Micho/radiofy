<?php

namespace App\Http\Livewire\Listener\Library;

use Livewire\Component;

class OwnerRadioLivewire extends Component
{
    public $listener;

    public function mount()
    {
        // Assuming the logged-in user is a Listener instance using the "listener" guard.
        $this->listener = auth()->guard('listener')->user();
    }

    public function render()
    {
        // Fetch followed internal radios with the owner (subscriber) profile.
        $followedInternalRadios = $this->listener->followedInternalRadios()
            ->with(['subscriber.subscriber_profile'])
            ->withCount('listeners')
            ->get();

        // Fetch followed external radios with the owner (subscriber) profile.
        $followedExternalRadios = $this->listener->followedExternalRadios()
            ->with(['subscriber.subscriber_profile'])
            ->withCount('listeners')
            ->get();

        // Merge both collections into one.
        $followedOwner = $followedInternalRadios->merge($followedExternalRadios);

        // Filter out duplicates based on the subscriber's id.
        // Only keep radios that have a subscriber and then return unique subscriber ids.
        $uniqueOwners = $followedOwner->filter(function($radio) {
            return isset($radio->subscriber);
        })->unique(function($radio) {
            return $radio->subscriber->id;
        });

        return view('listener.pages.library.profileRadio', [
            'followedOwner' => $uniqueOwners,
        ]);
    }
}
