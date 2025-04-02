<?php

namespace App\Http\Livewire\Subscriber\Radio;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\RadioConfiguration;
use Illuminate\Support\Str;

class RadioServerLivewire extends Component
{
    use WithFileUploads;
    
    public $radio_id;
    public $radioName;
    public $radioNameSlug;
    public $source;
    public $sourcePassword;
    
    public function mount($radio_id)
    {
        $this->radio_id = $radio_id;
        $config = RadioConfiguration::find($this->radio_id);
        if ($config) {
            $this->radioName = $config->radio_name;
            $this->radioNameSlug = $config->radio_name_slug;
            if ($config->source) {
                $this->source = preg_replace('/@.*$/', '', $config->source);
            }
            $this->sourcePassword = $config->source_password;
        }
    }
    
    protected function rules()
    {
        return [
            'source'         => 'required|string',
            'sourcePassword' => 'required|string',
        ];
    }
    
    public function updateServer()
    {
        $validatedData = $this->validate();
        
        $target = 'mradiofy';
        if (preg_match('/@(\S+)$/', $validatedData['source'], $matches)) {
            $domain = strtolower($matches[1]);
            if ($domain !== $target && levenshtein($domain, $target) <= 2) {
                $validatedData['source'] = preg_replace('/@\S+$/', '@' . $target, $validatedData['source']);
            }
        } else {
            $validatedData['source'] .= '@' . $target;
        }
        
        $config = RadioConfiguration::find($this->radio_id);
        if (!$config) {
            $this->dispatchBrowserEvent('alert', [
                'type'    => 'error',
                'message' => __('Radio configuration not found.')
            ]);
            return;
        }
        
        $config->source = $validatedData['source'];
        $config->source_password = $validatedData['sourcePassword'];
        $config->save();
        
        $this->dispatchBrowserEvent('alert', [
            'type'    => 'success',
            'message' => __('Radio configuration updated successfully.')
        ]);
        
        return redirect()->route('subs-radios');
    }
    
    public function render()
    {
        return view('subscriber.pages.radio-server.radio-server');
    }
}
