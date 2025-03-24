<?php

namespace App\Http\Livewire\Listener\Layout;

use Livewire\Component;
use App\Models\RadioConfiguration;
use Illuminate\Support\Facades\Log;

class PlayerOneLivewire extends Component
{
    public $lastRadio;
    public $currentRadio;
    public $streamUrl;
    public $isPlaying = false;
    
    protected $listeners = ['playNowEvent' => 'playNow'];

    public function mount($last_radio)
    {
        $this->lastRadio = $last_radio;
        $this->currentRadio = RadioConfiguration::with([
            'plan', 
            'radio_configuration_profile.languages', 
            'genre'
        ])->findOrFail($this->lastRadio);
        $this->streamUrl = app('server_ip').':'.app('server_post').'/'.$this->currentRadio->radio_name_slug ?? 'https://usa13.fastcast4u.com/proxy/mradio02?mp=/1';
    }


    public function playNow($radioId = null)
    {
        if ($radioId) {
            $this->currentRadio = RadioConfiguration::with([
                'plan', 
                'radio_configuration_profile.languages', 
                'genre'
            ])->findOrFail($radioId);

            // Log::info(app('server_ip').':'.app('server_post').'/'.$this->currentRadio->radio_name_slug);
            $this->streamUrl = app('server_ip').':'.app('server_post').'/'.$this->currentRadio->radio_name_slug;

            // Testing
            // if($radioId == 12){
            //     $this->streamUrl = 'https://l3.itworkscdn.net/itwaudio/9006/stream';
            // } else {
            //     $this->streamUrl = 'https://usa13.fastcast4u.com/proxy/mradio02?mp=/1';

            // }
            $this->dispatchBrowserEvent('play-radio', ['streamUrl' => $this->streamUrl]);
            $this->isPlaying = true;
        }
    }

    public function playNowControl(){
        if($this->isPlaying) {
            $this->dispatchBrowserEvent('pause-radio');
            $this->isPlaying = false;
        } else {
            $this->dispatchBrowserEvent('play-radio', ['streamUrl' => $this->streamUrl]);
            $this->isPlaying = true;
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
