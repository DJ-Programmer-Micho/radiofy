<?php

namespace App\Http\Livewire\Admin;

use App\Models\Brand;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use App\Models\BrandTranslation;
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
            'maxListeners'   => 'required|integer|min:1',
            'burstSize'      => 'required|integer|min:1',
            'port'           => 'required|integer',
            'bindAddress'    => 'required|string',
            'sourcePassword' => 'required|string',
            'relayPassword'  => 'nullable|string',
            'adminPassword'  => 'nullable|string',
            'fallbackMount'  => 'nullable|string',
            'status'         => 'required|in:0,1',
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
            'max_listeners'    => $this->maxListeners,
            'burst_size'       => $this->burstSize,
            'port'             => $this->port,
            'bind_address'     => $this->bindAddress,
            'source_password'  => $this->sourcePassword,
            'relay_password'   => $this->relayPassword,
            'admin_password'   => $this->adminPassword,
            'fallback_mount'   => $this->fallbackMount,
            'status'           => $this->status,
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
        $this->maxListeners   = $radio->max_listeners;
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
            ]);
            
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
    }

    // Render
    public function render()
    {
        // Count active and non-active configurations
        $this->activeCount = IcecastConfiguration::where('status', 1)->count();
        $this->nonActiveCount = IcecastConfiguration::where('status', 0)->count();
    
        // Start with the query builder
        $query = IcecastConfiguration::query();
    
        // Apply status filter
        if ($this->statusFilter === 'active') {
            $query->where('status', 1);
        } elseif ($this->statusFilter === 'non-active') {
            $query->where('status', 0);
        }
    
        // Apply search filter based on the related brandtranslation
        if (!empty($this->search)) {
            $query->whereHas('brandtranslation', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }
    
        // Execute the query with ordering and pagination
        $tableData = $query->orderBy('created_at', 'ASC')->paginate(10)->withQueryString();
    
        return view('admin.pages.radio.radio-table', [
            'tableData' => $tableData,
        ]);
    }    

    //************************************** */
    //XML FILE TRIGGER */
    //************************************** */
    public function updateIcecastXml()
{
    // Path to the XML template in your resources folder
    $templatePath = resource_path('xml/icecast-template.xml');
dd(resource_path('xml/icecast-template.xml'));
    // Load the template using DOMDocument
    $dom = new \DOMDocument('1.0', 'UTF-8');
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    if (!$dom->load($templatePath)) {
        session()->flash('xml_update', 'Failed to load XML template.');
        return;
    }

    // Locate the placeholder comment where mountpoints should be inserted
    $xpath = new \DOMXPath($dom);
    $placeholderNodes = $xpath->query('//comment()[contains(., "MOUNTS_PLACEHOLDER")]');
    if ($placeholderNodes->length > 0) {
        $placeholder = $placeholderNodes->item(0);
        $parentNode = $placeholder->parentNode;
    } else {
        // If not found, use the document element as a fallback
        $parentNode = $dom->documentElement;
    }

    // Remove the placeholder comment
    $parentNode->removeChild($placeholder);

    // Retrieve active radio configurations from the database
    $configs = \App\Models\IcecastConfiguration::where('status', 1)->get();

    // For each configuration, create a <mount> element with its children.
    foreach ($configs as $config) {
        $mount = $dom->createElement('mount');
        $mount->setAttribute('type', 'normal');

        // Generate mount-name from radio name (e.g., /radiolive)
        $mountNameText = '/' . strtolower(str_replace(' ', '_', $config->radio_name));
        $mountName = $dom->createElement('mount-name', htmlspecialchars($mountNameText));
        $mount->appendChild($mountName);

        // Add username element (if needed)
        $username = $dom->createElement('username', 'source');
        $mount->appendChild($username);

        // Add password element (using server_password or source_password based on your needs)
        $password = $dom->createElement('password', $config->server_password);
        $mount->appendChild($password);

        // Add max-listeners element
        $maxListeners = $dom->createElement('max-listeners', $config->max_listeners);
        $mount->appendChild($maxListeners);

        // Add burst-size element
        $burstSize = $dom->createElement('burst-size', $config->burst_size);
        $mount->appendChild($burstSize);

        // Optionally add fallback-mount, genre, description if provided
        if ($config->fallback_mount) {
            $fallback = $dom->createElement('fallback-mount', $config->fallback_mount);
            $mount->appendChild($fallback);
        }
        if ($config->genre) {
            $genre = $dom->createElement('genre', $config->genre);
            $mount->appendChild($genre);
        }
        if ($config->description) {
            $description = $dom->createElement('description', $config->description);
            $mount->appendChild($description);
        }

        // Append the mount element to the parent node (where the placeholder was)
        $parentNode->appendChild($mount);
    }

    // Define the target XML file path; ensure permissions allow writing here.
    $xmlFilePath = '/etc/icecast2/icecast.xml';

    // Save the updated XML file
    if ($dom->save($xmlFilePath)) {
        session()->flash('xml_update', 'icecast.xml updated successfully.');
        // Optionally, reload the Icecast service
        // shell_exec('sudo systemctl reload icecast2');
    } else {
        session()->flash('xml_update', 'Failed to update icecast.xml.');
    }
}

}