<?php

namespace App\Http\Livewire\Subscriber\Radio;

use App\Models\Genre;
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
    
    // Radio configuration fields
    public $radio_id;
    public $radioName;
    public $radioNameSlug;
    public $location;
    public $selectedLanguages = []; // array of language IDs
    public $selectedGenres = [];    // array of genre IDs
    public $logo;      // file path for logo
    public $banner;    // file path for banner
    public $description;
    public $meta_keywords; // as string
    public $social_media = [];  // associative array e.g. [ 'facebook' => 'url', ... ]
    public $highestPeakListeners = 0; // integer
    
    // Options loaded from DB
    public $languagesOptions = [];
    public $genresOptions = [];

    protected $listeners = [
        'logoUploaded',
        'bannerUploaded',
    ];

    
    

    public function mount($radio_id)
    {
        $this->radio_id = $radio_id;
        
        // Load main configuration and its profile
        $config = RadioConfiguration::with('radio_configuration_profile')
                    ->where('id', $this->radio_id)
                    ->first();
        if ($config) {
            $this->radioName = $config->radio_name;
            $this->radioNameSlug = $config->radio_name_slug;
            if ($config->radio_configuration_profile) {
                $profile = $config->radio_configuration_profile;
                $this->location          = $profile->location;
                // Here we assume that radio_locale holds the selected language IDs
                $this->selectedLanguages = json_decode($profile->radio_locale, true) ?? [];
                $this->selectedGenres    = json_decode($profile->genres, true) ?? [];
                $this->logo              = $profile->logo;
                $this->banner            = $profile->banner;
                $this->description       = $profile->description;
                $this->meta_keywords     = $profile->meta_keywords;
                $this->social_media      = json_decode($profile->social_media, true) ?? [];
                $this->highestPeakListeners = $profile->highest_peak_listeners;
            }
        }
        // Load options for languages and genres
        $this->languagesOptions = Language::where('status', 1)
            ->orderBy('priority')
            ->get();
        $this->genresOptions = Genre::with(['genreTranslation' => function ($query) {
            $query->where('locale', app()->getLocale());
        }])->orderBy('priority')->get();
    }
    
    protected function rules()
    {
        return [
            'radioName'         => 'required|string',
            'location'          => 'required|string',
            'selectedLanguages' => 'required|array|min:1',
            'selectedGenres'    => 'required|array|min:1',
            'logo'              => 'nullable|string',
            'banner'            => 'nullable|string',
            'description'       => 'nullable|string',
            'meta_keywords'     => 'nullable|string',
            'social_media'      => 'nullable|array',
        ];
    }
    
    public function updateRadioNamePreview(){
        $this->radioNameSlug = Str::slug($this->radioName, '-', '');
    }
    public function updateConfig()
    {
        $validatedData = $this->validate();
        
        // Load main configuration record
        $config = RadioConfiguration::with('radio_configuration_profile')
                    ->find($this->radio_id);
        if (!$config) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' => __('Radio configuration not found.')
            ]);
            return;
        }
        
        // Update main configuration (radio name)
        $config->radio_name = $validatedData['radioName'];
        $config->radio_name_slug = Str::slug($this->radioName, '-', '');
        $config->save();
        
        // Get or create associated profile
        $profile = $config->radio_configuration_profile;
        if (!$profile) {
            $profile = new RadioConfigurationProfile();
            $profile->radio_id = $config->id;
        }
        
        // Move logo file from temporary folder if needed
        if ($this->logo && strpos($this->logo, 'radio/tmp/logo') === 0) {
            $newLogoPath = str_replace('radio/tmp/logo', 'radio/logo', $this->logo);
            Storage::disk('public')->move($this->logo, $newLogoPath);
            $this->logo = $newLogoPath;
        }
        
        // Move banner file from temporary folder if needed
        if ($this->banner && strpos($this->banner, 'radio/tmp/banner') === 0) {
            $newBannerPath = str_replace('radio/tmp/banner', 'radio/banner', $this->banner);
            Storage::disk('public')->move($this->banner, $newBannerPath);
            $this->banner = $newBannerPath;
        }
        
        $profile->location      = $validatedData['location'];
        $profile->radio_locale  = json_encode($validatedData['selectedLanguages']);
        $profile->genres        = json_encode($validatedData['selectedGenres']);
        $profile->logo          = $this->logo;
        $profile->banner        = $this->banner;
        $profile->description   = $validatedData['description'] ?? '';
        $profile->meta_keywords = $validatedData['meta_keywords'] ?? '';
        $profile->social_media  = json_encode($validatedData['social_media'] ?? []);
        $profile->highest_peak_listeners = $validatedData['highest_peak_listeners'] ?? null;
        $profile->save();
        
        $this->dispatchBrowserEvent('alert', [
            'type'    => 'success',
            'message' => __('Radio configuration updated successfully.')
        ]);

        return redirect()->route('subs-radios');
    }
    
    public function logoUploaded($path)
    {
        $cleaned = trim($path, '"');
        // If there is an existing temporary logo and it's different, remove it.
        if ($this->logo && $this->logo !== $cleaned && strpos($this->logo, 'radio/tmp/logo') === 0) {
            Storage::disk('public')->delete($this->logo);
        }
        $this->logo = $cleaned;
    }
    
    public function bannerUploaded($path)
    {
        $cleaned = trim($path, '"');
        // If there is an existing temporary banner and it's different, remove it.
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
