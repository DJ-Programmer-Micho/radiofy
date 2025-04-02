<?php

namespace App\Http\Livewire\Listener\Genre;

use App\Models\Genre;
use Livewire\Component;


class GenreLivewire extends Component
{
    
    public function render()
    {
        $generes = Genre::with('genreTranslation')->get();
        // dd($this->otherRadio);
        return view('listener.pages.genre.gridView', [
            'genres' => $generes,
        ]);
    }
}
