<?php

namespace App\Http\Livewire\Listener\Home;

use Livewire\Component;
use App\Models\Genre;

class SomeGenreLivewire extends Component
{
    public $limit = 6; // default limit

    protected $listeners = [
        'updateLimit'  // listens for an event from JavaScript
    ];

    // This method is triggered by the JS event with the current screen width.
    public function updateLimit($width)
    {
        // Adjust the limit based on screen width. For example:
        // For desktop (width >= 1024) we want 4 genres; otherwise 6.
        $this->limit = ($width >= 1400) ? 6 : 4;
    }

    public function render()
    {
        $genres = Genre::with('genreTranslation')->inRandomOrder()->take($this->limit)->get();

        return view('listener.pages.home.someGenre', [
            'genres' => $genres,
        ]);
    }
}
