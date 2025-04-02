<?php

namespace App\Http\Livewire\Listener\Language;

use App\Models\Language;
use Livewire\Component;
use App\Models\RadioConfiguration;
use App\Models\ExternalRadioConfiguration;
use Illuminate\Support\Collection;

class LanguageIndexLivewire extends Component
{
    public $selectedLanguage;
    public $codeId;

    public array $topRadios = [];
    public array $otherRadios = [];

    public function mount($code)
    {
        $this->codeId = $code;

        $this->selectedLanguage = Language::where('code', $code)->first();
        $this->loadRadiosByLanguage();
    }

    public function loadRadiosByLanguage()
    {
        $internalRadios = RadioConfiguration::with(['radio_configuration_profile', 'languages'])
            ->whereHas('languages', fn($q) => $q->where('languages.code', $this->codeId))
            ->withCount('listeners')
            ->where('status', 1)
            ->get();

        $externalRadios = ExternalRadioConfiguration::with(['external_radio_configuration_profile', 'languages'])
            ->whereHas('languages', fn($q) => $q->where('languages.code', $this->codeId))
            ->withCount('listeners')
            ->where('status', 1)
            ->get();

        $combined = new Collection([...$internalRadios, ...$externalRadios]);

        $sorted = $combined->sortByDesc(fn($radio) => $radio->profile->highest_peak_listeners ?? 0)->values();

        $this->topRadios = $sorted->take(12)->all();
        $this->otherRadios = $sorted->slice(12)->all();
    }

    public function render()
    {
        $languages = Language::inRandomOrder()->take(6)->get();

        return view('listener.pages.languageview.view', [
            'selectedLanguage' => $this->selectedLanguage,
            'languages'        => $languages,
            'filterRadios'     => $this->topRadios,
            'otherRadios'      => $this->otherRadios,
        ]);
    }
}