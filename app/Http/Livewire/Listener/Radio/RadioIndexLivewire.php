<?php

namespace App\Http\Livewire\Listener\Radio;

use Livewire\Component;
use App\Models\RadioConfiguration;
use App\Models\ExternalRadioConfiguration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RadioIndexLivewire extends Component
{
    public $slug;
    public $radio_id;
    public $radioType = 'internal';
    public $radio;
    public $otherRadio;
    public $streamUrl;

    public $isFollowed = false;
    public $isLiked = false;
    public $followersCount = 0;
    public $likesCount = 0;

    public function mount($slug)
    {
        $this->slug = $slug;

        // Try internal radio first
        $radio = RadioConfiguration::with([
            'plan',
            'radio_configuration_profile.languages',
            'genres'
        ])->where('radio_name_slug', $slug)->first();

        if ($radio) {
            $this->radioType = 'internal';
        } else {
            // Try external if internal not found
            $radio = ExternalRadioConfiguration::with([
                'external_radio_configuration_profile',
                'genres'
            ])->where('radio_name_slug', $slug)->firstOrFail();

            $this->radioType = 'external';
        }

        $this->radio = $radio;
        $this->radio_id = $radio->id;

        // Set stream URL
        if ($this->radioType === 'internal') {
            $slug = $this->radio->radio_name_slug;
            $this->streamUrl = app('server_ip') . ':' . app('server_post') . '/' . $slug;
        } else {
            $this->streamUrl = $this->radio->stream_url;
        }

        // Fetch both internal and external radios for this subscriber
        $internalRadios = RadioConfiguration::with([
            'radio_configuration_profile',
            'genres'
        ])
        ->withCount('listeners')
        ->where('subscriber_id', $this->radio->subscriber_id)
        ->where('status', 1)
        ->get();
    
    $externalRadios = ExternalRadioConfiguration::with([
            'external_radio_configuration_profile',
            'genres'
        ])
        ->withCount('listeners')
        ->where('subscriber_id', $this->radio->subscriber_id)
        ->where('status', 1)
        ->get();
    
    // Tag them with type and convert to array manually
    $internalMapped = $internalRadios->map(function ($radio) {
        return [
            'id' => $radio->id,
            'type' => 'internal',
            'radio_name' => $radio->radio_name,
            'radio_name_slug' => $radio->radio_name_slug,
            'listeners_count' => $radio->listeners_count,
            'profile' => [
                'logo' => $radio->radio_configuration_profile->logo ?? null,
            ],
        ];
    });
    
    $externalMapped = $externalRadios->map(function ($radio) {
        return [
            'id' => $radio->id,
            'type' => 'external',
            'radio_name' => $radio->radio_name,
            'radio_name_slug' => $radio->radio_name_slug,
            'listeners_count' => $radio->listeners_count,
            'profile' => [
                'logo' => $radio->external_radio_configuration_profile->logo ?? null,
            ],
        ];
    });
    
    $merged = $internalMapped->merge($externalMapped);
    
    // Optional: sort by a property if needed
    $sortedRadios = $merged->sortByDesc(function ($radio) {
        return $radio['listeners_count'] ?? 0;
    });
    
    $this->otherRadio = $sortedRadios->values()->all(); // Now safe for Livewire
    

        // Check follow/like status if listener is authenticated
        $user = Auth::guard('listener')->user();
        if ($user) {
            $modelType = $this->radioType === 'internal'
                ? RadioConfiguration::class
                : ExternalRadioConfiguration::class;

            $this->isFollowed = $user->follows()
                ->where('radioable_id', $this->radio_id)
                ->where('radioable_type', $modelType)
                ->exists();

            $this->isLiked = $user->likes()
                ->where('radioable_id', $this->radio_id)
                ->where('radioable_type', $modelType)
                ->exists();

            $this->followersCount = DB::table('follows')
                ->where('radioable_id', $this->radio_id)
                ->where('radioable_type', $modelType)
                ->count();

            $this->likesCount = DB::table('likes')
                ->where('radioable_id', $this->radio_id)
                ->where('radioable_type', $modelType)
                ->count();
        }
    }

    public function toggleFollow()
    {
        $user = Auth::guard('listener')->user();
        if (!$user) {
            session()->flash('error', 'Please login to follow.');
            return;
        }

        $modelType = $this->radioType === 'internal' ? RadioConfiguration::class : ExternalRadioConfiguration::class;

        if ($this->isFollowed) {
            $user->follows()
                ->where('radioable_id', $this->radio_id)
                ->where('radioable_type', $modelType)
                ->delete();
            $this->isFollowed = false;
            $this->followersCount = max(0, $this->followersCount - 1);
        } else {
            $user->follows()->create([
                'radioable_id'   => $this->radio_id,
                'radioable_type' => $modelType,
            ]);
            $this->isFollowed = true;
            $this->followersCount++;
        }
    }

    public function toggleLike()
    {
        $user = Auth::guard('listener')->user();
        if (!$user) {
            session()->flash('error', 'Please login to like.');
            return;
        }

        $modelType = $this->radioType === 'internal' ? RadioConfiguration::class : ExternalRadioConfiguration::class;

        $exists = $user->likes()
            ->where('radioable_id', $this->radio_id)
            ->where('radioable_type', $modelType)
            ->exists();

        if ($exists) {
            $user->likes()
                ->where('radioable_id', $this->radio_id)
                ->where('radioable_type', $modelType)
                ->delete();
            $this->isLiked = false;
            $this->likesCount = max(0, $this->likesCount - 1);
        } else {
            $user->likes()->create([
                'radioable_id'   => $this->radio_id,
                'radioable_type' => $modelType,
            ]);
            $this->isLiked = true;
            $this->likesCount++;
        }
    }

    public function render()
    {
        return view('listener.pages.radio.radioView', [
            'radio'      => $this->radio,
            'otherRadio' => $this->otherRadio,
        ]);
    }
}
