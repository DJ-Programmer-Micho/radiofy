<?php

namespace App\Http\Livewire\Subscriber\Radio;

use Livewire\Component;
use App\Models\Language;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use App\Models\RadioConfiguration;
use Illuminate\Support\Facades\Storage;
use App\Models\RadioConfigurationProfile;

class RadioManageLivewire extends Component
{
    use WithFileUploads;
    
    public $radio_id;
    public $radioName;
    public $radioNameSlug;
    public $location;
    public $selectedLanguages = [];
    public $selectedGenres = [];
    public $logo;
    public $banner;
    public $description;
    public $meta_keywords;
    public $social_media = [];
    public $highestPeakListeners = 0;

    public $languagesOptions = [];
    public $genresOptions = [];

    protected $listeners = [
        'logoUploaded',
        'bannerUploaded',
    ];

    public function mount($radio_id)
    {
        $this->radio_id = $radio_id;
        
        // Get the authenticated subscriber and ensure we load only their radio config.
        $subscriber = auth()->guard('subscriber')->user();
        $config = RadioConfiguration::with(['radio_configuration_profile', 'languages', 'genres'])
            ->where('subscriber_id', $subscriber->id)
            ->find($this->radio_id);

        // If no configuration exists for this subscriber, redirect back.
        if (!$config) {
            $this->redirectRoute('subs-radios');
            return;
        }
        
        // Populate component properties.
        $this->radioName = $config->radio_name;
        $this->radioNameSlug = $config->radio_name_slug;

        if ($config->radio_configuration_profile) {
            $profile = $config->radio_configuration_profile;
            $this->location = $profile->location;
            $this->logo = $profile->logo;
            $this->banner = $profile->banner;
            $this->description = $profile->description;
            $this->meta_keywords = $profile->meta_keywords;
            $this->social_media = json_decode($profile->social_media, true) ?? [];
            $this->highestPeakListeners = $profile->highest_peak_listeners;
        }

        $this->selectedGenres = $config->genres->pluck('id')->toArray();
        $this->selectedLanguages = $config->languages->pluck('id')->toArray();

        $this->languagesOptions = Language::where('status', 1)->orderBy('priority')->get();
        $this->genresOptions = \App\Models\Genre::with(['genreTranslation' => function ($query) {
            $query->where('locale', app()->getLocale());
        }])->orderBy('priority')->get();
    }

    protected function rules()
    {
        return [
            'radioName'         => 'required|string',
            'location'          => 'required|string',
            'selectedLanguages' => 'array|min:1',
            'selectedGenres'    => 'required|array|min:1',
            'logo'              => 'nullable|string',
            'banner'            => 'nullable|string',
            'description'       => 'nullable|string',
            'meta_keywords'     => 'nullable|string',
            'social_media'      => 'nullable|array',
        ];
    }

    public function updateRadioNamePreview()
    {
        $this->radioNameSlug = Str::slug($this->radioName, '-', '');
    }

    public function updateConfig()
    {
        try {
            $validatedData = $this->validate();

            // Get the authenticated subscriber
            $subscriber = auth()->guard('subscriber')->user();
            $config = RadioConfiguration::with('radio_configuration_profile')
                ->where('subscriber_id', $subscriber->id)
                ->find($this->radio_id);
            if (!$config) {
                dd('asd');
                return redirect()->route('subs-radios');
            }

            $config->radio_name = $validatedData['radioName'];
            $config->radio_name_slug = Str::slug($this->radioName, '-', '');
            $config->save();

            $config->genres()->sync($this->selectedGenres);
            $config->languages()->sync($this->selectedLanguages);

            $profile = $config->radio_configuration_profile;
            if (!$profile) {
                $profile = new RadioConfigurationProfile();
                $profile->radio_id = $config->id;
            }

            if ($this->logo && strpos($this->logo, 'radio/tmp/logo') === 0) {
                $newLogoPath = str_replace('radio/tmp/logo', 'radio/logo', $this->logo);
                Storage::disk('public')->move($this->logo, $newLogoPath);
                $this->logo = $newLogoPath;
            }

            if ($this->banner && strpos($this->banner, 'radio/tmp/banner') === 0) {
                $newBannerPath = str_replace('radio/tmp/banner', 'radio/banner', $this->banner);
                Storage::disk('public')->move($this->banner, $newBannerPath);
                $this->banner = $newBannerPath;
            }

            $profile->location = $validatedData['location'];
            $profile->logo = $this->logo;
            $profile->banner = $this->banner;
            $profile->description = $validatedData['description'] ?? '';
            $profile->meta_keywords = $validatedData['meta_keywords'] ?? '';
            $profile->social_media = json_encode($validatedData['social_media'] ?? []);
            $profile->highest_peak_listeners = $this->highestPeakListeners;
            $profile->save();

            $this->dispatchBrowserEvent('alert', [
                'type'    => 'success',
                'message' => __('Radio configuration updated successfully.')
            ]);

            return redirect()->route('subs-radios');
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('alert', [
                'type'    => 'error',
                'message' => __('Err: ' . $e->getMessage())
            ]);
        }
    }

    public function logoUploaded($path)
    {
        $cleaned = trim($path, '"');
        if ($this->logo && $this->logo !== $cleaned && strpos($this->logo, 'radio/tmp/logo') === 0) {
            Storage::disk('public')->delete($this->logo);
        }
        $this->logo = $cleaned;
    }

    public function bannerUploaded($path)
    {
        $cleaned = trim($path, '"');
        if ($this->banner && $this->banner !== $cleaned && strpos($this->banner, 'radio/tmp/banner') === 0) {
            Storage::disk('public')->delete($this->banner);
        }
        $this->banner = $cleaned;
    }

    public function render()
    {
        return view('subscriber.pages.radio-manage.radio-manage');
    }
}
