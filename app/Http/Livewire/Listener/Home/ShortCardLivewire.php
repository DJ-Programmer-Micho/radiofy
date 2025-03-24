<?php

namespace App\Http\Livewire\Listener\Home;

use Livewire\Component;

class ShortCardLivewire extends Component
{

    public function mount() {

    }
    public function render()
    {
        return view('listener.pages.home.shortCard', [
        ]);
    }
}
