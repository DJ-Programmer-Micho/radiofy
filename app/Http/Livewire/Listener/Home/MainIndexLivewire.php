<?php

namespace App\Http\Livewire\Listener\Home;

use Livewire\Component;

class MainIndexLivewire extends Component
{

    public function mount() {

    }
    public function render()
    {
        return view('listener.pages.home.container', [
        ]);
    }
}
