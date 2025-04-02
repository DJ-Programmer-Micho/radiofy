<?php

namespace App\Http\Livewire\Listener\Home;

use Livewire\Component;
use App\Models\RadioConfiguration;
use App\Models\ExternalRadioConfiguration;

class VerifiedRadioLivewire extends Component
{
    public function render()
    {
        // Fetch verified internal radios with profile, genres, and count of listeners
        $internalRadios = RadioConfiguration::with([
                'radio_configuration_profile',
                'genres'
            ])
            ->withCount('listeners')
            ->where('status', 1)
            ->where('verified', 1) // Only verified
            ->get();

        // Fetch verified external radios with profile, genres, and count of listeners
        $externalRadios = ExternalRadioConfiguration::with([
                'external_radio_configuration_profile',
                'genres'
            ])
            ->withCount('listeners')
            ->where('status', 1)
            ->where('verified', 1) // Only verified
            ->get();

        // Merge both collections
        $allRadios = $internalRadios->merge($externalRadios);

        // Sort descending by highest_peak_listeners from the profile; if not available, default to 0.
        $allRadios = $allRadios->sortByDesc(function ($radio) {
            if ($radio instanceof RadioConfiguration && $radio->radio_configuration_profile) {
                return $radio->radio_configuration_profile->highest_peak_listeners ?? 0;
            } elseif ($radio instanceof ExternalRadioConfiguration && $radio->external_radio_configuration_profile) {
                return $radio->external_radio_configuration_profile->highest_peak_listeners ?? 0;
            }
            return 0;
        });

        // Take the top 10
        $verifiedRadio = $allRadios->take(10);

        return view('listener.pages.home.verifiedRadio', [
            'verifiedRadio' => $verifiedRadio,
        ]);
    }
}
