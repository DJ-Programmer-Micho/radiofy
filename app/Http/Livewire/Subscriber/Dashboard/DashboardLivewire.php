<?php

namespace App\Http\Livewire\Subscriber\Dashboard;

use Livewire\Component;

class DashboardLivewire extends Component
{

    public function mount() {

    }
    public function render()
    {
        return view('subscriber.pages.dashboard.container', [
        ]);
    }
}
