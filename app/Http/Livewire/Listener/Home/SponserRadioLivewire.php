<?php

namespace App\Http\Livewire\Listener\Home;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\RadioPromotion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class SponserRadioLivewire extends Component
{
    protected $listeners = ['incrementCampaignClick' => 'incrementCampaign'];

    public function incrementCampaign($campaignId)
    {
        $campaign = RadioPromotion::where('radioable_id', $campaignId)->first();

        if ($campaign) {
            $radio = $campaign->radioable;
            if ($radio) {
                $listener = auth()->guard('listener')->user();
                // Use a fresh query to check the pivot table
                $exists = DB::table('listener_radios')
                    ->where('listener_id', $listener->id)
                    ->where('radioable_id', $radio->id)
                    ->where('radioable_type', get_class($radio))
                    ->exists();
    
                Log::info("Fresh check: Listener exists? " . ($exists ? 'Yes' : 'No'));
    
                if (!$exists) {
                    
                    // And increment new_listener_count on the campaign
                    $campaign->increment('new_listener_count');
                }
            }
        }
    }
    
    public function render()
    {
        // Only show campaigns if a listener is logged in
        $listener = auth()->guard('listener')->user();
        if (!$listener) {
            return view('listener.pages.home.sponserRadio', ['sponserRadios' => collect([])]);
        }

        $listenerAge = Carbon::parse($listener->listener_profile->dob)->age;

        $allCampaigns = Cache::remember('sponser_radios', now()->addSeconds(60), function () {
            return RadioPromotion::with(['promotion', 'radioable'])
                ->where('status', 1)
                ->where('expire_date', '>', Carbon::now())
                ->inRandomOrder()
                ->take(10)
                ->get();
        });

        $sponserRadios = $allCampaigns->filter(function ($campaign) use ($listener, $listenerAge) {
            $targetGenders = $campaign->target_gender; // e.g., [1] or [1,2]
            $genderMatch = empty($targetGenders) || in_array($listener->listener_profile->gender, $targetGenders);

            $targetAges = $campaign->target_age_range;
            $ageMatch = false;
            if (empty($targetAges)) {
                $ageMatch = true;
            } else {
                foreach ($targetAges as $range) {
                    if (strpos($range, '+') !== false) {
                        $min = (int) rtrim($range, '+');
                        if ($listenerAge >= $min) {
                            $ageMatch = true;
                            break;
                        }
                    } elseif (strpos($range, '-') !== false) {
                        [$min, $max] = explode('-', $range);
                        if ($listenerAge >= (int)$min && $listenerAge <= (int)$max) {
                            $ageMatch = true;
                            break;
                        }
                    }
                }
            }
            return $genderMatch && $ageMatch;
        });

        // You can optionally update each campaign’s new listener count here,
        // but our incrementCampaignClick method will handle it on click.

        return view('listener.pages.home.sponserRadio', [
            'sponserRadios' => $sponserRadios,
        ]);
    }
}
