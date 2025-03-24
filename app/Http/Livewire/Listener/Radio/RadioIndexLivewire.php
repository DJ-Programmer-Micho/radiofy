<?php

namespace App\Http\Livewire\Listener\Radio;

use Livewire\Component;
use App\Models\RadioConfiguration;

class RadioIndexLivewire extends Component
{
    public $radio_id;
    public $radio; // Holds the current radio record
    public $otherRadio; // Holds the other radios for the subscriber

    public function mount($id)
    {
        $this->radio_id = $id;
        // Load the radio with its plan, profile (and its languages), and genre
        $this->radio = RadioConfiguration::with([
            'plan', 
            'radio_configuration_profile.languages', 
            'genre'
        ])->findOrFail($this->radio_id);

        // Load other radios for the same subscriber.
        // Optionally exclude the current radio by uncommenting the where clause.
        $this->otherRadio = RadioConfiguration::with([
            'plan', 
            'radio_configuration_profile.languages', 
            'genre'
        ])
            ->where('subscriber_id', $this->radio->subscriber_id)
            // ->where('id', '!=', $this->radio->id) // Exclude the current radio if needed
            ->get();
    }

    public function render()
    {
        // dd($this->otherRadio);
        return view('listener.pages.radio.radioView', [
            'radio'      => $this->radio,
            'otherRadio' => $this->otherRadio,
        ]);
    }
}
