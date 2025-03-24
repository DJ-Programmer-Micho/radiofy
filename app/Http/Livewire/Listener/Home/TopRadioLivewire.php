<?php

namespace App\Http\Livewire\Listener\Home;

use Livewire\Component;
use App\Models\RadioConfiguration;

class TopRadioLivewire extends Component
{

    public function mount() {

    }
    public function render()
    {
        // Fetch active radios with the count of listeners, ordered by listeners_count in descending order
        $topRadios = RadioConfiguration::where('radio_configurations.status', 1)
            ->join('radio_configuration_profiles', 'radio_configurations.id', '=', 'radio_configuration_profiles.radio_id')
            ->orderBy('radio_configuration_profiles.highest_peak_listeners', 'desc')
            ->select('radio_configurations.*', 'radio_configuration_profiles.highest_peak_listeners')
            ->take(10)
            ->get();
    

        return view('listener.pages.home.topRadio', [
            'topRadios' => $topRadios,
        ]);
    }
}
