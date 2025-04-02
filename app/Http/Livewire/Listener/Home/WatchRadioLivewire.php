<?php

namespace App\Http\Livewire\Listener\Home;

use Livewire\Component;
use App\Models\RadioConfiguration;
use App\Models\ExternalRadioConfiguration;
use Illuminate\Support\Facades\Auth;

class WatchRadioLivewire extends Component
{
    public $watchRadios; // Collection of recent radios

    public function mount()
    {
        $listener = Auth::guard('listener')->user();

        if ($listener && !empty($listener->recent_radios)) {
            $recent = $listener->recent_radios;
            $recentReversed = array_reverse($recent);
            $recentReversed = array_slice($recentReversed, 0, 10);
            $radios = [];

            foreach ($recentReversed as $entry) {
                $radioId = $entry['radioable_id'] ?? null;
                $radioType = $entry['radioable_type'] ?? 'internal';

                if ($radioId) {
                    if ($radioType === 'internal') {
                        $radio = RadioConfiguration::with([
                            'plan',
                            'radio_configuration_profile',
                            'genres'
                        ])->withCount('listeners')->find($radioId);
                    } else {
                        $radio = ExternalRadioConfiguration::with([
                            'external_radio_configuration_profile',
                            'genres'
                        ])->withCount('listeners')->find($radioId);
                    }
                    if ($radio) {
                        $radio->type = $radioType;
                        $radios[] = $radio;
                    }
                }
            }
            $this->watchRadios = collect($radios);
        } else {
            $internalRadios = RadioConfiguration::where('status', 1)
                ->withCount('listeners')
                ->inRandomOrder()
                ->take(5)
                ->get();
            $externalRadios = ExternalRadioConfiguration::where('status', 1)
                ->withCount('listeners')
                ->inRandomOrder()
                ->take(5)
                ->get();
            $this->watchRadios = $internalRadios->merge($externalRadios);
        }
    }

    public function render()
    {
        return view('listener.pages.home.watchRadio', [
            'watchRadios' => $this->watchRadios,
        ]);
    }
}
