<?php

namespace App\Http\Livewire\Listener\Library;

use App\Models\Genre;
use App\Models\Listener;
use Livewire\Component;

class LibraryLivewire extends Component
{
    public $listener;

    public function mount()
    {
        // Assuming the logged-in user is a Listener instance
        $this->listener = auth()->guard('listener')->user();
    }

    public function render()
    {
        // Get all genres with their translations
        $genres = Genre::with('genreTranslation')->get();

        // Retrieve listener stats:
        // Count of follows using the relationship defined in the Listener model.
        $followsCount = $this->listener->follows()->count();

        // Count of likes using the relationship defined in the Listener model.
        $likesCount = $this->listener->likes()->count();

        // For membership, you might have a field or a method to determine the membership type.
        // For now, we assume a free membership.
        $membership = 'FREE';

        // Use the created_at field as the join date and format it as "05 Jan, 2022"
        $joinDate = $this->listener->created_at->format('d M, Y');

        return view('listener.pages.Library.view', [
            'genres'       => $genres,
            'listener'     => $this->listener,
            'followsCount' => $followsCount,
            'likesCount'   => $likesCount,
            'membership'   => $membership,
            'joinDate'     => $joinDate,
        ]);
    }
}
