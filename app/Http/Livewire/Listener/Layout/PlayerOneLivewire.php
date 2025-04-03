<?php

namespace App\Http\Livewire\Listener\Layout;

use Livewire\Component;
use App\Models\RadioConfiguration;
use App\Models\ExternalRadioConfiguration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PlayerOneLivewire extends Component
{
    public $lastRadio;
    public $radioType = 'internal'; // 'internal' or 'external'
    public $currentRadio;
    public $streamUrl;
    public $isPlaying = false;
    public $isLiked = false;       // New property for like status
    public $likesCount = 0;        // New property for the total like count

    protected $listeners = ['playNowEvent' => 'playNow'];

    public function mount($last_radio = null, $radioType = 'internal')
    {
        // Determine lastRadio and radioType from query parameters or fallback.
        if (request()->has('radioId') && request()->has('radioType')) {
            $this->lastRadio = request('radioId');
            $this->radioType = request('radioType');
        } else {
            $listener = auth()->guard('listener')->user();
            if ($listener && !empty($listener->recent_radios)) {
                $recentRadios = $listener->recent_radios;
                $lastEntry = end($recentRadios);
                $this->lastRadio = $lastEntry['radioable_id'] ?? null;
                $this->radioType = $lastEntry['radioable_type'] ?? 'internal';
            } else {
                $this->lastRadio = $last_radio;
            }
        }
    
        // Load the radio based on radioType and lastRadio.
        if ($this->radioType === 'internal') {
            $this->currentRadio = RadioConfiguration::with([
                'plan', 
                'radio_configuration_profile.languages', 
                'genres'
            ])->find($this->lastRadio);
            if (!$this->currentRadio) {
                $this->currentRadio = RadioConfiguration::where('status', 1)->inRandomOrder()->first();
            }
            $slug = $this->currentRadio->radio_name_slug ?? null;
            $this->streamUrl = $slug 
                ? app('server_ip') . ':' . app('server_post') . '/' . $slug 
                : 'https://usa13.fastcast4u.com/proxy/mradio02?mp=/1';
        } else {
            $this->currentRadio = ExternalRadioConfiguration::with([
                'external_radio_configuration_profile',
                'genres'
            ])->find($this->lastRadio);
            if (!$this->currentRadio) {
                $this->currentRadio = ExternalRadioConfiguration::where('status', 1)->inRandomOrder()->first();
            }
            $this->streamUrl = $this->currentRadio->stream_url ?? 'https://usa13.fastcast4u.com/proxy/mradio02?mp=/1';
        }
    
        // Dispatch a browser event to trigger auto-play.
        if ($this->currentRadio) {
            $this->dispatchBrowserEvent('auto-play-radio', ['streamUrl' => $this->streamUrl]);
        }

        // Check if the listener has liked the current radio.
        $user = Auth::guard('listener')->user();
        if ($user) {
            $modelType = $this->radioType === 'internal'
                ? RadioConfiguration::class
                : ExternalRadioConfiguration::class;

            $this->isLiked = $user->likes()
                ->where('radioable_id', $this->currentRadio->id)
                ->where('radioable_type', $modelType)
                ->exists();

            $this->likesCount = DB::table('likes')
                ->where('radioable_id', $this->currentRadio->id)
                ->where('radioable_type', $modelType)
                ->count();
            } else {
                $modelType = $this->radioType === 'internal'
                ? RadioConfiguration::class
                : ExternalRadioConfiguration::class;
                $this->likesCount = DB::table('likes')
                    ->where('radioable_id', $this->currentRadio->id)
                    ->where('radioable_type', $modelType)
                    ->count();
                
        }
    }
    
    /**
     * Toggle play/pause.
     */
    public function playNowControl()
    {
        if ($this->isPlaying) {
            $this->dispatchBrowserEvent('pause-radio');
            $this->isPlaying = false;
        } else {
            $this->dispatchBrowserEvent('play-radio', ['streamUrl' => $this->streamUrl]);
            $this->isPlaying = true;
        }
    }

    /**
     * Toggle the like status.
     */
    public function toggleLike()
    {
        $user = Auth::guard('listener')->user();
        if (!$user) {
            session()->flash('error', 'Please login to like.');
            return;
        }

        $modelType = $this->radioType === 'internal' ? RadioConfiguration::class : ExternalRadioConfiguration::class;

        if ($user->likes()
            ->where('radioable_id', $this->currentRadio->id)
            ->where('radioable_type', $modelType)
            ->exists()) {
            // Remove the like.
            $user->likes()
                ->where('radioable_id', $this->currentRadio->id)
                ->where('radioable_type', $modelType)
                ->delete();
            $this->isLiked = false;
            $this->likesCount = max(0, $this->likesCount - 1);
        } else {
            // Add a new like.
            $user->likes()->create([
                'radioable_id'   => $this->currentRadio->id,
                'radioable_type' => $modelType,
            ]);
            $this->isLiked = true;
            $this->likesCount++;
        }
    }
    
    /**
     * Update the highest peak listeners count.
     */
    protected function updateHighestPeakListeners()
    {
        if (!$this->currentRadio) return;
        $listenerCount = $this->currentRadio->listeners()->count();
        if ($this->radioType === 'internal') {
            $profile = $this->currentRadio->radio_configuration_profile;
        } else {
            $profile = $this->currentRadio->external_radio_configuration_profile;
        }
        if ($profile && $listenerCount > $profile->highest_peak_listeners) {
            $profile->highest_peak_listeners = $listenerCount;
            $profile->save();
        }
    }

    /**
     * (Optional) Function to update recent radios if needed.
     */
    protected function updateRecentRadio($radioId, $radioType)
    {
        $listener = Auth::guard('listener')->user();
        if (!$listener) return;
        $recent = $listener->recent_radios ?? [];
        foreach ($recent as $key => $entry) {
            if ($entry['radioable_id'] == $radioId && $entry['radioable_type'] == ($radioType === 'internal' 
                ? RadioConfiguration::class 
                : ExternalRadioConfiguration::class)) {
                unset($recent[$key]);
            }
        }
        $recent[] = [
            'radioable_id'   => $radioId,
            'radioable_type' => $radioType === 'internal' 
                ? RadioConfiguration::class 
                : ExternalRadioConfiguration::class,
        ];
        $recent = array_slice($recent, -13);
        $listener->recent_radios = $recent;
        $listener->save();
    }

    public function playNow($radioId = null, $radioType = 'internal')
    {
        $this->radioType = $radioType;
        if ($radioId) {
            if ($this->radioType === 'internal') {
                $this->currentRadio = RadioConfiguration::with([
                    'plan', 
                    'radio_configuration_profile', 
                    'genres'
                ])->findOrFail($radioId);
                $slug = $this->currentRadio->radio_name_slug;
                $this->streamUrl = app('server_ip') . ':' . app('server_post') . '/' . $slug;
            } else {
                $this->currentRadio = ExternalRadioConfiguration::with([
                    'external_radio_configuration_profile',
                    'genres'
                ])->findOrFail($radioId);
                $this->streamUrl = $this->currentRadio->stream_url;
            }
            
            $this->dispatchBrowserEvent('play-radio', ['streamUrl' => $this->streamUrl]);
            $this->isPlaying = true;
            $this->updateRecentRadio($radioId, $this->radioType);

            // Record the play (if needed)...
            $listener = Auth::guard('listener')->user();
            if ($listener) {
                $type = $this->radioType === 'internal'
                    ? RadioConfiguration::class
                    : ExternalRadioConfiguration::class;
    
                $lastPlay = DB::table('listener_radios')
                    ->where('listener_id', $listener->id)
                    ->where('radioable_id', $radioId)
                    ->where('radioable_type', $type)
                    ->orderBy('created_at', 'desc')
                    ->first();
    
                if (!$lastPlay || now()->diffInHours($lastPlay->created_at) >= 1) {
                    $subscriberId = $this->currentRadio->subscriber->id ?? null;
                    DB::table('listener_radios')->insert([
                        'listener_id'     => $listener->id,
                        'subscriber_id'   => $subscriberId,
                        'radioable_id'    => $radioId,
                        'radioable_type'  => $type,
                        'created_at'      => now(),
                        'updated_at'      => now(),
                    ]);
                }
            }
    
            $this->updateHighestPeakListeners();
        }
    }
    
    public function render()
    {
        return view('listener.components.player-one', [
            'current_radio' => $this->currentRadio,
            'isPlaying'     => $this->isPlaying,
            'isLiked'       => $this->isLiked,
            'likesCount'    => $this->likesCount,
        ]);
    }
}
