<?php

namespace App\Http\Livewire\Listener\Language;

use App\Models\Genre;
use App\Models\Language;
use Livewire\Component;


class LanguageLivewire extends Component
{
    
    public function render()
    {
        $languages = Language::get();
        // dd($this->otherRadio);
        return view('listener.pages.language.gridView', [
            'languages' => $languages,
        ]);
    }
}
