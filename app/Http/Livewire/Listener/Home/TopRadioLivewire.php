<?php

namespace App\Http\Livewire\Listener\Home;

use Livewire\Component;
use App\Models\RadioConfiguration;
use App\Models\ExternalRadioConfiguration;

class TopRadioLivewire extends Component
{
    public function render()
    {
        // Fetch, sort, and take top 10 internal radios
        $internalRadios = RadioConfiguration::with([
                'radio_configuration_profile',
                'genres'
            ])
            ->withCount('listeners')
            ->where('status', 1)
            ->get()
            ->sortByDesc(function ($radio) {
                $listeners = $radio->listeners_count;
                $peak = $radio->radio_configuration_profile->highest_peak_listeners ?? 0;
                return max($peak, $listeners);
            })
            ->take(5);

        // Fetch, sort, and take top 10 external radios
        $externalRadios = ExternalRadioConfiguration::with([
                'external_radio_configuration_profile',
                'genres'
            ])
            ->withCount('listeners')
            ->where('status', 1)
            ->get()
            ->sortByDesc(function ($radio) {
                $listeners = $radio->listeners_count;
                $peak = $radio->external_radio_configuration_profile->highest_peak_listeners ?? 0;
                return max($peak, $listeners);
            })
            ->take(5);

        // Merge both collections
        $topRadios = $internalRadios->merge($externalRadios);

        // Optional: sort the combined collection if you want a unified ranking
        $topRadios = $topRadios->sortByDesc(function ($radio) {
            $listeners = $radio->listeners_count;
            if ($radio instanceof RadioConfiguration && $radio->radio_configuration_profile) {
                $peak = $radio->radio_configuration_profile->highest_peak_listeners ?? 0;
            } elseif ($radio instanceof ExternalRadioConfiguration && $radio->external_radio_configuration_profile) {
                $peak = $radio->external_radio_configuration_profile->highest_peak_listeners ?? 0;
            } else {
                $peak = 0;
            }
            return max($peak, $listeners);
        });


        return view('listener.pages.home.topRadio', [
            'topRadios' => $topRadios,
        ]);
    }
}
