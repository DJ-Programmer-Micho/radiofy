<?php

namespace App\Http\Livewire\Listener\Genre;

use App\Models\Genre;
use Livewire\Component;
use App\Models\RadioConfiguration;
use App\Models\ExternalRadioConfiguration;

class GenreIndexLivewire extends Component
{
    public $selectedGenre;
    public $genreId;

    public $topRadios = [];
    public $otherRadios = [];

    public function mount($id)
    {
        $this->genreId = $id;

        // Load selected genre info
        $this->selectedGenre = Genre::with('genreTranslation')->find($id);

        // Load radios based on genre
        $this->loadRadiosByGenre();
    }

    public function loadRadiosByGenre()
    {
        $internalRadios = RadioConfiguration::with(['radio_configuration_profile', 'genres'])
            ->whereHas('genres', fn($q) => $q->where('genres.id', $this->genreId))
            ->withCount('listeners')
            ->where('status', 1)
            ->get();

        $externalRadios = ExternalRadioConfiguration::with(['external_radio_configuration_profile', 'genres'])
            ->whereHas('genres', fn($q) => $q->where('genres.id', $this->genreId))
            ->withCount('listeners')
            ->where('status', 1)
            ->get();

        $combined = $internalRadios->merge($externalRadios);

        $sorted = $combined->sortByDesc(function ($radio) {
            if ($radio instanceof RadioConfiguration && $radio->radio_configuration_profile) {
                return $radio->radio_configuration_profile->highest_peak_listeners ?? 0;
            }

            if ($radio instanceof ExternalRadioConfiguration && $radio->external_radio_configuration_profile) {
                return $radio->external_radio_configuration_profile->highest_peak_listeners ?? 0;
            }

            return 0;
        })->values();

        $this->topRadios = $sorted->take(12);
        $this->otherRadios = $sorted->slice(13);
    }

    public function render()
    {
        $genres = Genre::with('genreTranslation')->inRandomOrder()->take(6)->get();

        return view('listener.pages.genreview.view', [
            'selectedGenre' => $this->selectedGenre,
            'genres'        => $genres,
            'filterRadios'  => $this->topRadios,
            'otherRadios'   => $this->otherRadios,
        ]);
    }
}
