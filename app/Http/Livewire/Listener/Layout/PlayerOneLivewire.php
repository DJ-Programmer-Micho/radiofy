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
    
    protected $listeners = ['playNowEvent' => 'playNow'];
    
    /**
     * Mount the component.
     *
     * If query parameters 'radioId' and 'radioType' exist,
     * use them; otherwise, fallback to recent radios or a default.
     *
     * @param int|null $last_radio
     * @param string $radioType
     */
    public function mount($last_radio = null, $radioType = 'internal')
    {
        // Check if query parameters exist.
        if (request()->has('radioId') && request()->has('radioType')) {
            $this->lastRadio = request('radioId');
            $this->radioType = request('radioType');
        } else {
            // Fallback: use listener's recent radio if available.
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
    }
    
    /**
     * Handle the play event (for manual play requests).
     *
     * @param int|null $radioId
     * @param string $radioType
     */
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
            $listener = auth()->guard('listener')->user();
            if ($listener) {
                $type = $this->radioType === 'internal'
                    ? \App\Models\RadioConfiguration::class
                    : \App\Models\ExternalRadioConfiguration::class;
    
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
     * Update recent radios.
     */
    protected function updateRecentRadio($radioId, $radioType)
    {
        $listener = auth()->guard('listener')->user();
        if (!$listener) return;
        $recent = $listener->recent_radios ?? [];
        foreach ($recent as $key => $entry) {
            if ($entry['radioable_id'] == $radioId && $entry['radioable_type'] == ($radioType === 'internal' 
                ? \App\Models\RadioConfiguration::class 
                : \App\Models\ExternalRadioConfiguration::class)) {
                unset($recent[$key]);
            }
        }
        $recent[] = [
            'radioable_id'   => $radioId,
            'radioable_type' => $radioType === 'internal' 
                ? \App\Models\RadioConfiguration::class 
                : \App\Models\ExternalRadioConfiguration::class,
        ];
        $recent = array_slice($recent, -13);
        $listener->recent_radios = $recent;
        $listener->save();
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
    
    public function render()
    {
        return view('listener.components.player-one', [
            'current_radio' => $this->currentRadio,
            'isPlaying'     => $this->isPlaying,
        ]);
    }
}
