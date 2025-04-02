<?php

namespace App\Http\Livewire\Listener\Home;

use Livewire\Component;
use App\Models\RadioConfiguration;
use App\Models\ExternalRadioConfiguration;
use Illuminate\Support\Facades\Cache;

class ShortCardLivewire extends Component
{
    public function render()
    {
        $shortCards = Cache::remember('short_cards', now()->addSeconds(60), function () {
            $internal = RadioConfiguration::where('status', 1)
                ->select('id', 'radio_name', 'radio_name_slug')
                ->withCount('listeners')
                ->inRandomOrder()
                ->get();
                
            $external = ExternalRadioConfiguration::where('status', 1)
                ->select('id', 'radio_name', 'radio_name_slug')
                ->withCount('listeners')
                ->inRandomOrder()
                ->get();

            // Merge, shuffle, and take 8 records.
            return $internal->merge($external)->shuffle()->take(8);
        });

        return view('listener.pages.home.shortCard', [
            'shortCards' => $shortCards,
        ]);
    }
}
