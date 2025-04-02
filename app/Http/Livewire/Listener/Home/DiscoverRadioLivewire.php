<?php

namespace App\Http\Livewire\Listener\Home;

use Livewire\Component;
use App\Models\RadioConfiguration;
use App\Models\ExternalRadioConfiguration;
use Illuminate\Support\Facades\Auth;

class DiscoverRadioLivewire extends Component
{
    public function render()
    {
        $listener = Auth::guard('listener')->user();
        $recentInternal = [];
        $recentExternal = [];

        if ($listener && !empty($listener->recent_radios)) {
            foreach ($listener->recent_radios as $entry) {
                if ($entry['radioable_type'] === 'internal') {
                    $recentInternal[] = $entry['radioable_id'];
                } elseif ($entry['radioable_type'] === 'external') {
                    $recentExternal[] = $entry['radioable_id'];
                }
            }
        }        

        // Query internal radios (with listener count) not in recent list.
        $internalRadiosQuery = RadioConfiguration::query()
            ->where('status', 1)
            ->withCount('listeners')
            ->inRandomOrder();
        if (!empty($recentInternal)) {
            $internalRadiosQuery->whereNotIn('id', $recentInternal);
        }
        $internalRadios = $internalRadiosQuery->take(10)->get();

        // Query external radios (with listener count) not in recent list.
        $externalRadiosQuery = ExternalRadioConfiguration::query()
            ->where('status', 1)
            ->withCount('listeners')
            ->inRandomOrder();
        if (!empty($recentExternal)) {
            $externalRadiosQuery->whereNotIn('id', $recentExternal);
        }
        $externalRadios = $externalRadiosQuery->take(10)->get();

        // Merge both collections.
        $discoverRadios = $internalRadios->merge($externalRadios);

        // Fallback: if filtering out recent radios left nothing.
        if ($discoverRadios->isEmpty()) {
            $internalRadios = RadioConfiguration::where('status', 1)
                ->withCount('listeners')
                ->inRandomOrder()
                ->take(10)
                ->get();
            $externalRadios = ExternalRadioConfiguration::where('status', 1)
                ->withCount('listeners')
                ->inRandomOrder()
                ->take(10)
                ->get();
            $discoverRadios = $internalRadios->merge($externalRadios);
        }

        return view('listener.pages.home.discoverRadio', [
            'discoverRadios' => $discoverRadios,
        ]);
    }
}
