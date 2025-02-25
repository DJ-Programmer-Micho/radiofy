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
    
    // Retrieve all active radio configurations; adjust the query as needed.
    $configs = \App\Models\IcecastConfiguration::where('status', 1)->get();

    // Build the base XML using a heredoc template.
    $baseXml = <<<XML
<icecast>
    <location>Earth</location>
    <admin>icemaster@localhost</admin>
    <limits>
        <clients>100</clients>
        <sources>2</sources>
        <queue-size>524288</queue-size>
        <client-timeout>30</client-timeout>
        <header-timeout>15</header-timeout>
        <source-timeout>10</source-timeout>
        <burst-on-connect>1</burst-on-connect>
        <burst-size>65535</burst-size>
    </limits>
    <authentication>
        <source-password>hackme</source-password>
        <relay-password>hackme</relay-password>
        <admin-user>admin</admin-user>
        <admin-password>hackme</admin-password>
    </authentication>
    <hostname>localhost</hostname>
    <listen-socket>
        <port>8000</port>
    </listen-socket>
    <http-headers>
        <header name="Access-Control-Allow-Origin" value="*" />
    </http-headers>
    <paths>
        <basedir>/usr/share/icecast2</basedir>
        <logdir>/var/log/icecast2</logdir>
        <webroot>/usr/share/icecast2/web</webroot>
        <adminroot>/usr/share/icecast2/admin</adminroot>
        <alias source="/" destination="/status.xsl"/>
    </paths>
    <logging>
        <accesslog>access.log</accesslog>
        <errorlog>error.log</errorlog>
        <loglevel>3</loglevel>
        <logsize>10000</logsize>
    </logging>
    <security>
        <chroot>0</chroot>
    </security>
</icecast>
XML;

    // Load the base XML into a SimpleXMLElement
    $xml = new \SimpleXMLElement($baseXml);

    // Remove any existing mount nodes if necessary (or simply append new ones)
    // Here, we assume we want to add new <mount> entries for each radio configuration.
    foreach ($configs as $config) {
        // Create a mount element.
        $mount = $xml->addChild('mount');
        $mount->addAttribute('type', 'normal');
        
        // Create a mount name based on the radio name (for example, lowercase with underscores)
        $mountName = '/' . strtolower(str_replace(' ', '_', $config->radio_name));
        $mount->addChild('mount-name', $mountName);
        
        // Add child elements with dynamic values from the DB.
        // Adjust which fields go where based on your Icecast XML structure.
        $mount->addChild('username', 'source'); // You can modify this if needed.
        $mount->addChild('password', $config->server_password); 
        $mount->addChild('max-listeners', $config->max_listeners);
        $mount->addChild('burst-size', $config->burst_size);
        
        // Optionally add fallback-mount, genre, description, etc.
        if ($config->fallback_mount) {
            $mount->addChild('fallback-mount', $config->fallback_mount);
        }
        if ($config->genre) {
            $mount->addChild('genre', $config->genre);
        }
        if ($config->description) {
            $mount->addChild('description', $config->description);
        }
    }

    // Specify the path to the icecast.xml file.
    $xmlFilePath = '/etc/icecast2/icecast.xml';

    // Save the XML file. Ensure the PHP process has write permission to this file.
    if ($xml->asXML($xmlFilePath)) {
        session()->flash('xml_update', 'icecast.xml updated successfully.');
        
        // Optionally, you can reload Icecast (uncomment if desired and if permissions allow)
        // shell_exec('sudo systemctl reload icecast2');
    } else {
        session()->flash('xml_update', 'Failed to update icecast.xml');
    }
}

}