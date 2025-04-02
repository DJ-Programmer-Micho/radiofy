<?php

namespace App\Http\Livewire\Listener\Home;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class LikedRadioLivewire extends Component
{
    public function render()
    {
        $listener = Auth::guard('listener')->user();
        $likedRadios = collect();

        if ($listener) {
            // Fetch internal liked radios and pluck the associated radio.
            $internalLikes = $listener->likes()
                ->where('radioable_type', \App\Models\RadioConfiguration::class)
                ->with(['radioable.radio_configuration_profile', 'radioable.genres'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->pluck('radioable');

            // Fetch external liked radios.
            $externalLikes = $listener->likes()
                ->where('radioable_type', \App\Models\ExternalRadioConfiguration::class)
                ->with(['radioable.external_radio_configuration_profile', 'radioable.genres'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->pluck('radioable');

            $likedRadios = $internalLikes->merge($externalLikes)->take(10);

            // Optionally, if you want to load the listeners count for each liked radio:
            $likedRadios->each(function ($radio) {
                $radio->loadCount('listeners');
            });
        }

        return view('listener.pages.home.likedRadio', [
            'likedRadios' => $likedRadios,
        ]);
    }
}
