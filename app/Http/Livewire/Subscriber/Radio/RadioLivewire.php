<?php

namespace App\Http\Livewire\Subscriber\Radio;

use Carbon\Carbon;
use App\Models\RadioConfiguration;
use GuzzleHttp\Client;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RadioLivewire extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    
    protected $queryString = ['statusFilter', 'page'];
    
    // Form fields
    public $radioId;
    public $radioName;
    public $location; // (if needed; location might later be part of the profile)
    public $source;
    public $sourcePassword;
    public $selectedPlanId;
    public $status;
    public $plans = [];
    public $now;

    // Render/search parameters
    public $search = '';
    public $statusFilter = 'all';
    public $page = 1;
    public $activeCount = 0;
    public $nonActiveCount = 0;
    
    public function mount(){
        $this->now = Carbon::now();
        $this->status = 1;
        $this->statusFilter = request()->query('statusFilter', 'all');
        $this->page = request()->query('page', 1);
        $this->plans = \App\Models\Plan::all();
    }

    protected function rules()
    {
        return [
            'radioName'      => 'required|string|max:255',
            'location'       => 'nullable|string|max:255',
            'source'         => 'nullable|string|max:255',
            'sourcePassword' => 'nullable|string|max:255',
            'selectedPlanId' => 'required|exists:plans,id',
        ];
    }
    
    public function addRadio()
    {
        $this->validate();

        // Get the authenticated subscriber
        $subscriber = auth()->guard('subscriber')->user();

        $radioNameSlug = Str::slug($this->radioName, '-', '');
        $radio = RadioConfiguration::create([
            'subscriber_id'   => $subscriber->id, // Use authenticated subscriber ID
            'radio_name'      => $this->radioName,
            'radio_name_slug' => $radioNameSlug,
            'source'          => $this->source . "@mradiofy",
            'source_password' => $this->sourcePassword,
            'plan_id'         => $this->selectedPlanId,
            'status'          => 1,
        ]);
        
        // Immediately send configuration update to the Python service.
        $this->sendRadioConfigUpdate($radio);
        
        session()->flash('message', 'Radio added successfully.');
        $this->resetInputFields();
    }
    
    public function editRadio(int $id)
    {
        $subscriber = auth()->guard('subscriber')->user();
        $radio = RadioConfiguration::where('subscriber_id', $subscriber->id)
            ->findOrFail($id);
        $this->radioId        = $radio->id;
        $this->radioName      = $radio->radio_name;
        $this->source         = $radio->source;
        $this->sourcePassword = $radio->source_password;
        $this->selectedPlanId = $radio->plan_id;
    }
    
    public function updateRadio()
    {
        $this->validate();
        
        $subscriber = auth()->guard('subscriber')->user();
        if ($this->radioId) {
            $radio = RadioConfiguration::where('subscriber_id', $subscriber->id)
                ->findOrFail($this->radioId);
            $radioNameSlug = Str::slug($this->radioName, '-', '');
            $radio->update([
                'radio_name'      => $this->radioName,
                'radio_name_slug' => $radioNameSlug,
                'source'          => $this->source,
                'source_password' => $this->sourcePassword,
                'plan_id'         => $this->selectedPlanId,
            ]);
            // Update Python service with new configuration.
            $this->sendRadioConfigUpdate($radio);
    
            session()->flash('message', 'Radio updated successfully.');
            $this->resetInputFields();
        }
    }
    
    public function closeModal(){
        $this->dispatchBrowserEvent('close-modal');
        $this->resetInputFields();
    }
    
    private function resetInputFields()
    {
        $this->radioId        = null;
        $this->radioName      = null;
        $this->location       = null;
        $this->source         = null;
        $this->sourcePassword = null;
        $this->selectedPlanId = null;
    }
    
    public function render()
    {
        $subscriber = auth()->guard('subscriber')->user();
        $this->activeCount = RadioConfiguration::where('subscriber_id', $subscriber->id)
            ->where('status', 1)
            ->count();
        $this->nonActiveCount = RadioConfiguration::where('subscriber_id', $subscriber->id)
            ->where('status', 0)
            ->count();
    
        $query = RadioConfiguration::where('subscriber_id', $subscriber->id);
    
        if ($this->statusFilter === 'active') {
            $query->where('status', 1);
        } elseif ($this->statusFilter === 'non-active') {
            $query->where('status', 0);
        }
    
        if (!empty($this->search)) {
            $query->where('radio_name', 'like', '%' . $this->search . '%');
        }
    
        $tableData = $query->orderBy('created_at', 'ASC')
            ->paginate(10)
            ->withQueryString();
    
        return view('subscriber.pages.radio.radio-table', [
            'tableData' => $tableData,
        ]);
    }
    
    public function updateRadioPython($id)
    {
        $subscriber = auth()->guard('subscriber')->user();
        $radio = RadioConfiguration::where('subscriber_id', $subscriber->id)
            ->find($id);
        if ($radio) {
            $this->sendRadioConfigUpdate($radio);
            $this->restartAllRadios();
            $this->restartPythonTranscoder();
            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => __('Reloaded')
            ]);
        } else {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' => __('Radio not found.')
            ]);
        }
    }

    public $deleteRadioId;
    public $radioNameToDelete = '';

    public function confirmDeleteRadio($id)
    {
        $this->deleteRadioId = $id;
        $this->radioNameToDelete = '';
    }

    public function removeRadio()
    {
        if ($this->radioNameToDelete !== 'DELETE RADIO') {
            // Optionally, add a flash message or error
            return;
        }

        $subscriber = auth()->guard('subscriber')->user();
        if ($this->deleteRadioId) {
            $radio = RadioConfiguration::where('subscriber_id', $subscriber->id)
                ->findOrFail($this->deleteRadioId);
            $radio->delete();
            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => __('Radio Deleted Successfully.')
            ]);
            $this->resetInputFields();
        }
    }
    
    // Send configuration update to Python transcoding service.
    protected function sendRadioConfigUpdate($radio)
    {
        $mountName = '/' . $radio->radio_name_slug;
        $sourceMount = '/source_' . ltrim($mountName, '/');
        $bitrate = ($radio->plan && $radio->plan->bitrate) ? $radio->plan->bitrate : 64;
        $config = [
            'source_url' => app('server_ip').':'.app('server_post')."{$sourceMount}",
            'mount'      => $mountName,
            'bitrate'    => $bitrate,
            'source'     => $radio->source,
            'password'   => $radio->source_password,
            'host'       => (string) app('server_ip'),
            'port'       => app('server_post'),
        ];
        $pythonServiceUrl = app('server_ip').':5000/update_radio_config';

        try {
            $client = new Client();
            $response = $client->post($pythonServiceUrl, [
                'json'    => [
                    'radio_id' => $radio->id,
                    'config'   => $config,
                ],
                'timeout' => 10,
            ]);
            
            if ($response->getStatusCode() !== 200) {
                Log::error("Python service update failed for radio ID {$radio->id}: HTTP " . $response->getStatusCode());
            }
        } catch (\Exception $e) {
            Log::error("Failed to update Python service for radio ID {$radio->id}: " . $e->getMessage());
        }
    }
    
    public function updateIcecastXml()
    {
        $templatePath = resource_path('xml/icecast-template.xml');
    
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        if (!$dom->load($templatePath)) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' => __('Failed to update XML')
            ]);
            return;
        }
    
        $xpath = new \DOMXPath($dom);
        $placeholderNodes = $xpath->query('//comment()[contains(., "MOUNTS_PLACEHOLDER")]');
        if ($placeholderNodes->length > 0) {
            $placeholder = $placeholderNodes->item(0);
            $parentNode = $placeholder->parentNode;
            $parentNode->removeChild($placeholder);
        } else {
            $parentNode = $dom->documentElement;
        }
    
        $subscriber = auth()->guard('subscriber')->user();
        $configs = RadioConfiguration::where('subscriber_id', $subscriber->id)
            ->where('status', 1)
            ->with('plan')
            ->get();
    
        foreach ($configs as $config) {
            $ingestionMount = $dom->createElement('mount');
            $ingestionMount->setAttribute('type', 'normal');
            $ingestionMountName = $dom->createElement('mount-name', '/source_' . $config->radio_name_slug);
            $ingestionMount->appendChild($ingestionMountName);
            $ingestionUsername = $dom->createElement('username', $config->source);
            $ingestionMount->appendChild($ingestionUsername);
            $ingestionPassword = $dom->createElement('password', $config->source_password);
            $ingestionMount->appendChild($ingestionPassword);
            $ingestionMaxListeners = $dom->createElement('max-listeners', 5);
            $ingestionMount->appendChild($ingestionMaxListeners);
            $parentNode->appendChild($ingestionMount);
    
            $listenerMount = $dom->createElement('mount');
            $listenerMount->setAttribute('type', 'normal');
            $listenerMountName = $dom->createElement('mount-name', '/' . $config->radio_name_slug);
            $listenerMount->appendChild($listenerMountName);
            $listenerRadio = $dom->createElement('radio-id', $config->id);
            $listenerMount->appendChild($listenerRadio);
            $listenerUsername = $dom->createElement('username', $config->source);
            $listenerMount->appendChild($listenerUsername);
            $listenerPassword = $dom->createElement('password', $config->source_password);
            $listenerMount->appendChild($listenerPassword);
            $maxListenersValue = $config->plan ? $config->plan->max_listeners : 5;
            $maxListeners = $dom->createElement('max-listeners', $maxListenersValue);
            $listenerMount->appendChild($maxListeners);

            // For genres, use the polymorphic relationship:
            if ($config->genres()->exists()) {
                $genreNames = $config->genres()->pluck('id')->toArray();
                $genreElement = $dom->createElement('genre', implode(',', $genreNames));
                $listenerMount->appendChild($genreElement);
            }
            if ($config->description) {
                $description = $dom->createElement('description', $config->description);
                $listenerMount->appendChild($description);
            }
            $parentNode->appendChild($listenerMount);
        }
    
        $xmlFilePath = '/etc/icecast2/icecast.xml';
    
        if ($dom->save($xmlFilePath)) {
            session()->flash('xml_update', 'icecast.xml updated successfully.');
            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => __('icecast.xml updated successfully')
            ]);
            $output = shell_exec('sudo systemctl reload icecast2 2>&1');
            $this->dispatchBrowserEvent('alert', [
                'type' => 'info',
                'message' => __('Icecast Reloaded in SERVER ' . $output)
            ]);
        } else {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' => __('Failed to update the icecast.xml')
            ]);
        }
    }
    
    public function restartPythonTranscoder()
    {
        try {
            $output = shell_exec('sudo supervisorctl restart python_transcoder 2>&1');
            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => __('Python Transcoder restarted successfully. Output: ' . $output)
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to restart Python Transcoder: " . $e->getMessage());
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' => __('Failed to restart Python Transcoder.')
            ]);
        }
    }
    
    public function changeTab($status)
    {
        $this->statusFilter = $status;
        $this->page = 1;
        $this->emitSelf('refresh');
    }
    
    public function restartAllRadios()
    {
        $this->updateIcecastXml();
    
        $subscriber = auth()->guard('subscriber')->user();
        $radios = RadioConfiguration::where('subscriber_id', $subscriber->id)
            ->where('status', 1)
            ->with('plan')
            ->get();
        foreach ($radios as $radio) {
            $this->sendRadioConfigUpdate($radio);
        }
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => __('All active radios have been updated')
        ]);
    }
}
