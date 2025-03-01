<?php

namespace App\Http\Livewire\Admin;

use App\Models\Brand;
use GuzzleHttp\Client;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use App\Models\BrandTranslation;
use Illuminate\Support\Facades\Log;
use App\Models\IcecastConfiguration;
use Illuminate\Support\Facades\Storage;

class RadioLivewire extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    
    protected $queryString = ['statusFilter', 'page'];
    // INT
    public $glang;
    public $filteredLocales;
    // TEMP
    public $radioId;
    // FORMS
    public $radioName;
    public $location;
    public $serverAdmin;
    public $serverPassword;
    public $maxListeners;    // number of clients
    public $burstSize;
    public $port;
    public $bindAddress;
    public $sourcePassword;
    public $relayPassword;
    public $adminPassword;
    public $fallbackMount;
    public $status;    

    public $selectedPlanId;

    // DELETE
    public $brand_selected_id_delete;
    public $brand_name_selected_delete;
    public $showTextTemp;
    public $confirmDelete;
    // Render
    public $search = '';
    public $statusFilter = 'all';
    public $page = 1;
    public $activeCount = 0;
    public $nonActiveCount = 0;

    //LISTENERS
    // protected $listeners = [
    //     'filterBrands' => 'filterBrands',
    // ];

    // On Load
    public function mount(){
        $this->status = 1;
        $this->statusFilter = request()->query('statusFilter', 'all');
        $this->page = request()->query('page', 1);
    }

    protected function rules()
    {
        return [
            'radioName'      => 'required|string|max:255',
            'location'       => 'nullable|string|max:255',
            'serverAdmin'    => 'required|email',
            'serverPassword' => 'required|string',
            'burstSize'      => 'required|integer|min:1',
            'port'           => 'required|integer',
            'bindAddress'    => 'required|string',
            'sourcePassword' => 'required|string',
            'relayPassword'  => 'nullable|string',
            'adminPassword'  => 'nullable|string',
            'fallbackMount'  => 'nullable|string',
            'status'         => 'required|in:0,1',
            'selectedPlanId' => 'required|exists:plans,id',
        ];
    }

    public function addRadio()
    {
        $this->validate();
        
        IcecastConfiguration::create([
            'subscriber_id'    => 1,
            'radio_name'       => $this->radioName,
            'location'         => $this->location,
            'server_admin'     => $this->serverAdmin,
            'server_password'  => $this->serverPassword,
            'burst_size'       => $this->burstSize,
            'port'             => $this->port,
            'bind_address'     => $this->bindAddress,
            'source_password'  => $this->sourcePassword,
            'relay_password'   => $this->relayPassword,
            'admin_password'   => $this->adminPassword,
            'fallback_mount'   => $this->fallbackMount,
            'status'           => $this->status,
            'plan_id'          => $this->selectedPlanId,
        ]);
        
        session()->flash('message', 'Radio added successfully.');
        $this->resetInputFields();
    }

    public function editRadio(int $id)
    {
        $radio = IcecastConfiguration::findOrFail($id);
        $this->radioId        = $radio->id;
        $this->radioName      = $radio->radio_name;
        $this->location       = $radio->location;
        $this->serverAdmin    = $radio->server_admin;
        $this->serverPassword = $radio->server_password;
        $this->selectedPlanId = $radio->plan_id;
        $this->burstSize      = $radio->burst_size;
        $this->port           = $radio->port;
        $this->bindAddress    = $radio->bind_address;
        $this->sourcePassword = $radio->source_password;
        $this->relayPassword  = $radio->relay_password;
        $this->adminPassword  = $radio->admin_password;
        $this->fallbackMount  = $radio->fallback_mount;
        $this->status         = $radio->status;
    }

    public function updateRadio()
    {
        $this->validate();
        
        if ($this->radioId) {
            $radio = IcecastConfiguration::findOrFail($this->radioId);
            $radio->update([
                'radio_name'       => $this->radioName,
                'location'         => $this->location,
                'server_admin'     => $this->serverAdmin,
                'server_password'  => $this->serverPassword,
                'max_listeners'    => $this->maxListeners,
                'burst_size'       => $this->burstSize,
                'port'             => $this->port,
                'bind_address'     => $this->bindAddress,
                'source_password'  => $this->sourcePassword,
                'relay_password'   => $this->relayPassword,
                'admin_password'   => $this->adminPassword,
                'fallback_mount'   => $this->fallbackMount,
                'status'           => $this->status,
                'plan_id'         => $this->selectedPlanId,
            ]);
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
        $this->serverAdmin    = null;
        $this->serverPassword = null;
        $this->maxListeners   = null;
        $this->burstSize      = null;
        $this->port           = null;
        $this->bindAddress    = null;
        $this->sourcePassword = null;
        $this->relayPassword  = null;
        $this->adminPassword  = null;
        $this->fallbackMount  = null;
        $this->status         = 1;
        $this->selectedPlanId = null;
    }

    // Render
    public function render()
    {
        $this->activeCount = IcecastConfiguration::where('status', 1)->count();
        $this->nonActiveCount = IcecastConfiguration::where('status', 0)->count();
    
        $query = IcecastConfiguration::query();
    
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

    //************************************** */
    //XML FILE TRIGGER */
    //************************************** */
    protected function sendRadioConfigUpdate($radio)
    {
        // Generate mount names.
        $mountName = '/' . strtolower(str_replace(' ', '_', $radio->radio_name));
        // Assume the source stream uses a "source_" prefix.
        $sourceMount = '/source' . $mountName;
        
        // Use the bitrate from the associated plan; default to 320 if not set.
        $bitrate = ($radio->plan && $radio->plan->bitrate) ? $radio->plan->bitrate : 64;
        
        // Construct the configuration payload.
        $config = [
            'source_url' => "http://192.168.0.113:{$radio->port}{$sourceMount}",
            'mount'      => $mountName,
            'bitrate'    => $bitrate,
            'password'   => $radio->server_password,
            'host'       => '192.168.0.113',
            'port'       => $radio->port,
        ];
        // $config = [
        //     'source_url' => "http://{$radio->bind_address}:{$radio->port}{$sourceMount}",
        //     'mount'      => $mountName,
        //     'bitrate'    => $bitrate,
        //     'password'   => $radio->server_password,
        //     'host'       => $radio->bind_address,
        //     'port'       => $radio->port,
        // ];
        
        // Define the Python service URL (update with your actual host/port)
        $pythonServiceUrl = 'http://192.168.0.113:5000/update_radio_config';
        
        try {
            $client = new Client();
            $response = $client->post($pythonServiceUrl, [
                'json'    => [
                    'radio_id' => $radio->id,
                    'config'   => $config,
                ],
                'timeout' => 5,
            ]);
            
            if ($response->getStatusCode() !== 200) {
                Log::error("Python service update failed for radio ID {$radio->id}: HTTP " . $response->getStatusCode());
            }
        } catch (\Exception $e) {
            Log::error("Failed to update Python service for radio ID {$radio->id}: " . $e->getMessage());
        }
    }
    
    //************************************** */
    //XML FILE TRIGGER */
    //************************************** */
    public function updateIcecastXml()
    {
        // Path to the XML template in your resources folder
        $templatePath = resource_path('xml/icecast-template.xml');
    
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        if (!$dom->load($templatePath)) {
            session()->flash('xml_update', 'Failed to load XML template.');
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
    
        // Retrieve active radio configurations
        $configs = IcecastConfiguration::where('status', 1)->with('plan')->get();
    
        foreach ($configs as $config) {
            $mount = $dom->createElement('mount');
            $mount->setAttribute('type', 'normal');
    
            // Generate mount-name (e.g., /radiolive)
            $mountNameText = '/' . strtolower(str_replace(' ', '_', $config->radio_name));
            $mountName = $dom->createElement('mount-name', htmlspecialchars($mountNameText));
            $mount->appendChild($mountName);
    
            $username = $dom->createElement('username', 'source');
            $mount->appendChild($username);
    
            $password = $dom->createElement('password', $config->server_password);
            $mount->appendChild($password);
    
            // Use max_listeners from the plan relation
            $maxListenersValue = $config->plan ? $config->plan->max_listeners : 0;
            $maxListeners = $dom->createElement('max-listeners', $maxListenersValue);
            $mount->appendChild($maxListeners);
    
            $burstSize = $dom->createElement('burst-size', $config->burst_size);
            $mount->appendChild($burstSize);
    
            if ($config->fallback_mount) {
                $fallback = $dom->createElement('fallback-mount', $config->fallback_mount);
                $mount->appendChild($fallback);
            }
            if ($config->plan && $config->plan->bitrate) {
                // Optionally add an element to indicate bitrate
                $bitrate = $dom->createElement('bitrate', $config->plan->bitrate);
                $mount->appendChild($bitrate);
            }
            if ($config->genre) {
                $genre = $dom->createElement('genre', $config->genre);
                $mount->appendChild($genre);
            }
            if ($config->description) {
                $description = $dom->createElement('description', $config->description);
                $mount->appendChild($description);
            }
    
            $parentNode->appendChild($mount);
        }
    
        $xmlFilePath = '/etc/icecast2/icecast.xml';
    
        if ($dom->save($xmlFilePath)) {
            session()->flash('xml_update', 'icecast.xml updated successfully.');
            $output = shell_exec('sudo systemctl reload icecast2 2>&1');
            session()->flash('xml_reload', 'Icecast reloaded: ' . $output);
        } else {
            session()->flash('xml_update', 'Failed to update icecast.xml.');
        }
    }

}