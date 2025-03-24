<?php

namespace App\Http\Livewire\Admin;

use App\Models\RadioConfiguration;
use GuzzleHttp\Client;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class RadioLivewire extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    
    protected $queryString = ['statusFilter', 'page'];
    
    // Form fields
    public $radioId;
    public $radioName;
    public $location;
    public $source;
    public $sourcePassword;
    public $genre;
    public $fallbackMount;
    public $selectedPlanId;
    public $status;
    public $plans = [];

    // Render / search parameters
    public $search = '';
    public $statusFilter = 'all';
    public $page = 1;
    public $activeCount = 0;
    public $nonActiveCount = 0;
    
    public function mount(){
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
            'source_Password'=> 'nullable|string|max:255',
            'genre'          => 'required|string',
            'selectedPlanId' => 'required|exists:plans,id',
        ];
    }
    
    public function addRadio()
    {
        $this->validate();
        
        $radio = RadioConfiguration::create([
            'subscriber_id'    => 1,
            'radio_name'       => $this->radioName,
            'location'         => $this->location,
            'source'           => $this->source."@mradiofy",
            'source_Password'  => $this->sourcePassword,
            'genre'            => $this->genre,
            'plan_id'          => $this->selectedPlanId,
        ]);
        
        // Immediately send configuration update to the Python service.
        $this->sendRadioConfigUpdate($radio);
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => __('Radio Added Successfully')
        ]);
        $this->resetInputFields();
    }
    
    public function editRadio(int $id)
    {
        $radio = RadioConfiguration::findOrFail($id);
        $this->radioId        = $radio->id;
        $this->radioName      = $radio->radio_name;
        $this->location       = $radio->location;
        $this->source         = $radio->source;
        $this->sourcePassword = $radio->source_password;
        $this->genre          = $radio->genre;
        $this->selectedPlanId = $radio->plan_id;
    }
    
    public function updateRadio()
    {
        $this->validate();
        
        if ($this->radioId) {
            $radio = RadioConfiguration::findOrFail($this->radioId);
            $radio->update([
                'radio_name'       => $this->radioName,
                'location'         => $this->location,
                'genre'            => $this->genre,
                'source'           => $this->source,
                'source_Password'  => $this->sourcePassword,
                'plan_id'          => $this->selectedPlanId,
            ]);
            // Update Python transcoding service with new configuration.
            $this->sendRadioConfigUpdate($radio);
            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => __('Radio Updated Successfully')
            ]);
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
        $this->genre          = null;
        $this->selectedPlanId = null;
    }
    
    public function render()
    {
        $this->activeCount = RadioConfiguration::where('status', 1)->count();
        $this->nonActiveCount = RadioConfiguration::where('status', 0)->count();
    
        $query = RadioConfiguration::query();
    
        if ($this->statusFilter === 'active') {
            $query->where('status', 1);
        } elseif ($this->statusFilter === 'non-active') {
            $query->where('status', 0);
        }
    
        if (!empty($this->search)) {
            $query->where('radio_name', 'like', '%' . $this->search . '%');
        }
    
        $tableData = $query->orderBy('created_at', 'ASC')->paginate(10)->withQueryString();
    
        return view('admin.pages.radio.radio-table', [
            'tableData' => $tableData,
        ]);
    }
    
    public function updateRadioPython($id)
    {
        $radio = RadioConfiguration::find($id);
        if ($radio) {
            $this->sendRadioConfigUpdate($radio);
            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => __('Radio {$radio->radio_name} updated in Python service.')
            ]);
        } else {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' => __('Radio Not Found')
            ]);
        }
    }

    // Send updated configuration to the Python transcoding service.
    protected function sendRadioConfigUpdate($radio)
    {
        // Generate listener mount name based on radio name.
        // For example, "Radio One1" becomes "/radio_one1"
        $mountName = '/' . strtolower(str_replace(' ', '_', $radio->radio_name));

        // Construct the ingestion mount URL using a fixed prefix.
        // For each radio, we want an ingestion mount like: "/source_radio_one1"
        $sourceMount = '/source_' . ltrim($mountName, '/');
        
        // Use the bitrate from the associated plan; default to 64 if not set.
        $bitrate = ($radio->plan && $radio->plan->bitrate) ? $radio->plan->bitrate : 64;
        
        // Build the configuration payload.
        // We hardcode the Icecast port (8000) here.
        $config = [
            'source_url' => "app('server_ip').':'.app('server_post'){$sourceMount}",  // e.g., http://192.168.0.113:8000/source_radio_one1
            'mount'      => $mountName,  // e.g., /radio_one1
            'bitrate'    => $bitrate,
            'source'     => $radio->source,
            'password'   => $radio->source_password,
            'host'       => '192.168.0.113',
            'port'       => 8000,
        ];
        
        // Define the Python service URL.
        $pythonServiceUrl = 'http://192.168.0.113:5000/update_radio_config';

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
    
    // Legacy XML update for Icecast (optional if using dynamic Python updates)
    public function updateIcecastXml()
    {
        $templatePath = resource_path('xml/icecast-template.xml');
    
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        if (!$dom->load($templatePath)) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' => __('Faild to update XML')
            ]);
            return;
        }
    
        $xpath = new \DOMXPath($dom);
        // Look for a placeholder comment to insert mounts.
        $placeholderNodes = $xpath->query('//comment()[contains(., "MOUNTS_PLACEHOLDER")]');
        if ($placeholderNodes->length > 0) {
            $placeholder = $placeholderNodes->item(0);
            $parentNode = $placeholder->parentNode;
            $parentNode->removeChild($placeholder);
        } else {
            $parentNode = $dom->documentElement;
        }
    
        // Loop through active radio configurations and add both ingestion and listener mounts.
        $configs = RadioConfiguration::where('status', 1)->with('plan')->get();
    
        foreach ($configs as $config) {
            $radioNameSlug = strtolower(str_replace(' ', '_', $config->radio_name));
            // Ingestion mount for this radio.
            $ingestionMount = $dom->createElement('mount');
            $ingestionMount->setAttribute('type', 'normal');
            $ingestionMountName = $dom->createElement('mount-name', '/source_' . $radioNameSlug);
            $ingestionMount->appendChild($ingestionMountName);
            $ingestionUsername = $dom->createElement('username', $config->source);
            $ingestionMount->appendChild($ingestionUsername);
            // Use the proper field name (lowercase) for password.
            $ingestionPassword = $dom->createElement('password', $config->source_password);
            $ingestionMount->appendChild($ingestionPassword);
            $ingestionMaxListeners = $dom->createElement('max-listeners', 5);
            $ingestionMount->appendChild($ingestionMaxListeners);
            $parentNode->appendChild($ingestionMount);
    
            // Listener mount for this radio.
            $listenerMount = $dom->createElement('mount');
            $listenerMount->setAttribute('type', 'normal');
            $listenerMountName = $dom->createElement('mount-name', '/' . $radioNameSlug);
            $listenerMount->appendChild($listenerMountName);
            // Use the same source and password as ingestion mount.
            $listenerRadio = $dom->createElement('radio-id', $config->id);
            $listenerMount->appendChild($listenerRadio);
            $listenerUsername = $dom->createElement('username', $config->source);
            $listenerMount->appendChild($listenerUsername);
            $listenerPassword = $dom->createElement('password', $config->source_password);
            $listenerMount->appendChild($listenerPassword);
            $maxListenersValue = $config->plan ? $config->plan->max_listeners : 5;
            $maxListeners = $dom->createElement('max-listeners', $maxListenersValue);
            $listenerMount->appendChild($maxListeners);

            if ($config->fallback_mount) {
                $fallback = $dom->createElement('fallback-mount', $config->fallback_mount);
                $listenerMount->appendChild($fallback);
            }
            if ($config->plan && $config->plan->bitrate) {
                $bitrate = $dom->createElement('bitrate', $config->plan->bitrate);
                $listenerMount->appendChild($bitrate);
            }
            if ($config->genre) {
                $genre = $dom->createElement('genre', $config->id);
                $listenerMount->appendChild($genre);
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
                'message' => __('Faild to update the icecast.xml')
            ]);
        }
    }
    
    // Restart all radios: update Icecast XML (if needed) and trigger Python updates.
    public function restartAllRadios()
    {
        $this->updateIcecastXml();
    
        $radios = RadioConfiguration::where('status', 1)->with('plan')->get();
        foreach ($radios as $radio) {
            $this->sendRadioConfigUpdate($radio);
        }
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => __('NAll Active Radios have been updated')
        ]);
    }

    public function generateStatusFile()
    {
        // 1. Fetch Icecast status JSON.
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->get('http://192.168.0.113:8000/status-json.xsl', [
                'timeout' => 10,
            ]);
            $icecastStatus = json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => __('Faild to fetch icecast status')
            ]);
            Log::error('Failed to fetch Icecast status: ' . $e->getMessage());
            return;
        }
        
        // Get the list of dynamic sources.
        // Depending on your Icecast configuration the array key may be 'source' or 'sources'.
        $sources = $icecastStatus['icestats']['source'] ?? [];
        
        // 2. Get all radio configurations from the database.
        $radios = RadioConfiguration::with('radio_configuration_profile', 'plan')->get();
        
        $mergedData = [];
        
        // 3. Loop over each radio configuration.
        foreach ($radios as $radio) {
            // Generate mount name from radio name, e.g. "Radio One" becomes "/radio_one"
            $mount = '/' . strtolower(str_replace(' ', '_', $radio->radio_name));
            
            // Try to find a matching dynamic source.
            // We'll assume that the listenurl contains the mount.
            $sourceData = collect($sources)->first(function ($s) use ($mount) {
                return isset($s['listenurl']) && strpos($s['listenurl'], $mount) !== false;
            });
            
            // If a dynamic source is found, update highest_peak_listeners if needed.
            if ($sourceData) {
                $currentPeak = (int)$sourceData['listener_peak'] ?? 0;
                // Access the associated profile (if it exists)
                if ($radio->radio_configuration_profile) {
                    $storedPeak = (int)$radio->radio_configuration_profile->highest_peak_listeners;
                    if ($currentPeak > $storedPeak) {
                        $radio->radio_configuration_profile->highest_peak_listeners = $currentPeak;
                        $radio->radio_configuration_profile->save();
                    }
                }
            }
            
            // Build a merged entry.
            $mergedEntry = [
                'mount' => $mount,
                // Use the radio id from the database
                'radio-id' => $radio->id,
                // Static configuration from the database can include other fields if needed.
                'static' => [
                    'radio_name' => $radio->radio_name,
                    // You can add more static fields here.
                ],
                // Dynamic data from Icecast; if no matching source, dynamic is null.
                'dynamic' => $sourceData ?: null,
            ];
            
            // For convenience, also produce a flattened entry with the keys we need.
            // We’ll produce an object like:
            // { "mount": "/m_radio_iraq", "radio-id": X, "bitrate": "128", "listener_peak": 2, "listeners": 2, "listenurl": "http://localhost:8000/m_radio_iraq" }
            if ($sourceData) {
                $flattened = [
                    'mount' => $mount,
                    'radio-id' => $radio->id,
                    'bitrate' => (string)$sourceData['bitrate'].'Kbps',
                    'listener_peak' => (int)$sourceData['listener_peak'],
                    'listeners' => (int)$sourceData['listeners'],
                    'listenurl' => (string)env('APP_URL').$mount,
                ];
            } else {
                // If no dynamic data, use nulls (or defaults)
                $flattened = [
                    'mount' => $mount,
                    'radio-id' => $radio->id,
                    'bitrate' => null,
                    'listener_peak' => null,
                    'listeners' => null,
                    'listenurl' => "http://192.168.0.113:8000" . $mount,
                ];
            }
            
            $mergedData[] = $flattened;
        }
        
        // 4. Prepare the output JSON.
        $output = [
            'sources' => $mergedData,
        ];
        
        $outputJson = json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        
        // 5. Write the output to a file (for example, in the public folder).
        $filePath = public_path('api/v1/stats/mradiofy-status-json.json');
        try {
            file_put_contents($filePath, $outputJson);
            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => __('New Genre Added Successfully')
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to write status file: ' . $e->getMessage());
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' => __('Faild to write JSON file')
            ]);
        }
    }
    

}




// public function deleteRadio($id)
// {
//     // Find the radio record.
//     $radio = RadioConfiguration::findOrFail($id);

//     // Delete the radio from the database.
//     $radio->delete();

//     // Call the Python deletion endpoint.
//     $pythonServiceUrl = 'http://192.168.0.113:5000/delete_radio_config';

//     try {
//         $client = new Client();
//         $response = $client->post($pythonServiceUrl, [
//             'json'    => [
//                 'radio_id' => $radio->id,
//             ],
//             'timeout' => 10,
//         ]);
        
//         if ($response->getStatusCode() !== 200) {
//             Log::error("Python service deletion failed for radio ID {$radio->id}: HTTP " . $response->getStatusCode());
//         }
//     } catch (\Exception $e) {
//         Log::error("Failed to delete radio config for radio ID {$radio->id}: " . $e->getMessage());
//     }
    
//     session()->flash('message', 'Radio deleted successfully.');
// }
