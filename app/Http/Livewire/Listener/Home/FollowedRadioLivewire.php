<?php

namespace App\Http\Livewire\Listener\Home;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class FollowedRadioLivewire extends Component
{
    public function render()
    {
        $listener = Auth::guard('listener')->user();
        $followedRadios = collect();

        if ($listener) {
            // Fetch internal followed radios with listener count.
            $internal = $listener->followedInternalRadios()
                ->with(['radio_configuration_profile', 'genres'])
                ->withCount('listeners')
                ->orderBy('pivot_created_at', 'desc')
                ->get();

            // Fetch external followed radios with listener count.
            $external = $listener->followedExternalRadios()
                ->with(['external_radio_configuration_profile', 'genres'])
                ->withCount('listeners')
                ->orderBy('pivot_created_at', 'desc')
                ->get();

            $followedRadios = $internal->merge($external)->take(10);
        }

        return view('listener.pages.home.followedRadio', [
            'followedRadios' => $followedRadios,
        ]);
    }
}
