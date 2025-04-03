<?php

namespace App\Http\Livewire\Subscriber\ERadio;

use Livewire\Component;
use App\Models\Language;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use App\Models\Genre;
use App\Models\ExternalRadioConfiguration;
use Illuminate\Support\Facades\Storage;
use App\Models\ExternalRadioConfigurationProfile;

class ExternalRadioManageLivewire extends Component
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

    public $languagesOptions = [];
    public $genresOptions = [];

    protected $listeners = [
        'logoUploaded',
        'bannerUploaded',
    ];

    public function mount($radio_id)
    {
        $this->radio_id = $radio_id;
        $subscriber = auth()->guard('subscriber')->user();

        $config = ExternalRadioConfiguration::with(['external_radio_configuration_profile', 'languages', 'genres'])
            ->where('subscriber_id', $subscriber->id)
            ->find($this->radio_id);
            
        if (!$config) {
            // Redirect back if no configuration is found for this subscriber.
            session()->flash('error', __('Radio configuration not found.'));
            return $this->redirectRoute('subs-radios');
        }
        
        $this->radioName = $config->radio_name;
        $this->radioNameSlug = $config->radio_name_slug;

        if ($config->external_radio_configuration_profile) {
            $profile = $config->external_radio_configuration_profile;
            $this->location = $profile->location;
            $this->logo = $profile->logo;
            $this->banner = $profile->banner;
            $this->description = $profile->description;
            $this->meta_keywords = $profile->meta_keywords;
            $this->social_media = json_decode($profile->social_media, true) ?? [];
        }

        $this->selectedGenres = $config->genres->pluck('id')->toArray();
        $this->selectedLanguages = $config->languages->pluck('id')->toArray();

        $this->languagesOptions = Language::where('status', 1)->orderBy('priority')->get();
        $this->genresOptions = Genre::with(['genreTranslation' => function ($query) {
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

            $subscriber = auth()->guard('subscriber')->user();
            $config = ExternalRadioConfiguration::with('external_radio_configuration_profile')
                ->where('subscriber_id', $subscriber->id)
                ->find($this->radio_id);
            if (!$config) {
                $this->dispatchBrowserEvent('alert', [
                    'type'    => 'error',
                    'message' => __('Radio configuration not found.')
                ]);
                return $this->redirectRoute('subs-radios');
            }

            $config->radio_name = $validatedData['radioName'];
            $config->radio_name_slug = Str::slug($this->radioName, '-', '');
            $config->save();

            $config->genres()->sync($this->selectedGenres);
            $config->languages()->sync($this->selectedLanguages);

            $profile = $config->external_radio_configuration_profile;
            if (!$profile) {
                $profile = new ExternalRadioConfigurationProfile();
                $profile->radio_id = $config->id;
            }

            if ($this->logo && strpos($this->logo, 'e-radio/tmp/logo') === 0) {
                $newLogoPath = str_replace('e-radio/tmp/logo', 'radio/logo', $this->logo);
                Storage::disk('public')->move($this->logo, $newLogoPath);
                $this->logo = $newLogoPath;
            }

            if ($this->banner && strpos($this->banner, 'e-radio/tmp/banner') === 0) {
                $newBannerPath = str_replace('e-radio/tmp/banner', 'radio/banner', $this->banner);
                Storage::disk('public')->move($this->banner, $newBannerPath);
                $this->banner = $newBannerPath;
            }

            $profile->location = $validatedData['location'];
            $profile->logo = $this->logo;
            $profile->banner = $this->banner;
            $profile->description = $validatedData['description'] ?? '';
            $profile->meta_keywords = $validatedData['meta_keywords'] ?? '';
            $profile->social_media = json_encode($validatedData['social_media'] ?? []);
            $profile->save();

            $this->dispatchBrowserEvent('alert', [
                'type'    => 'success',
                'message' => __('Radio configuration updated successfully.')
            ]);

            return $this->redirectRoute('subs-radios');
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
        if ($this->logo && $this->logo !== $cleaned && strpos($this->logo, 'e-radio/tmp/logo') === 0) {
            Storage::disk('public')->delete($this->logo);
        }
        $this->logo = $cleaned;
    }

    public function bannerUploaded($path)
    {
        $cleaned = trim($path, '"');
        if ($this->banner && $this->banner !== $cleaned && strpos($this->banner, 'e-radio/tmp/banner') === 0) {
            Storage::disk('public')->delete($this->banner);
        }
        $this->banner = $cleaned;
    }

    public function render()
    {
        return view('subscriber.pages.e-radio-manage.radio-manage');
    }
}
