<?php

namespace App\Http\Livewire\Subscriber\ERadio;

use GuzzleHttp\Client;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use App\Models\ExternalRadioConfiguration;
use App\Models\ExternalRadioConfigurationProfile;

class ExternalRadioLivewire extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    
    protected $queryString = ['statusFilter', 'page'];
    
    // Form fields
    public $radioId;
    public $radioName;
    public $streamUrl;
    public $status;

    // Render/search parameters
    public $search = '';
    public $statusFilter = 'all';
    public $page = 1;
    public $activeCount = 0;
    public $nonActiveCount = 0;
    
    public function mount(){
        $this->status = 1;
        $this->statusFilter = request()->query('statusFilter', 'all');
        $this->page = request()->query('page', 1);
    }

    protected function rules()
    {
        return [
            'radioName' => 'required|string|max:255',
            'streamUrl' => 'nullable|string|max:255',
        ];
    }
    
    public function addRadio()
    {
        $this->validate();
        
        $subscriber = auth()->guard('subscriber')->user();
        $radioNameSlug = Str::slug($this->radioName, '-', '');
        $radio = ExternalRadioConfiguration::create([
            'subscriber_id'   => $subscriber->id,
            'radio_name'      => $this->radioName,
            'radio_name_slug' => $radioNameSlug,
            'stream_url'      => $this->streamUrl,
            'status'          => 1,
        ]);

        ExternalRadioConfigurationProfile::create([
            'radio_id' => $radio->id,
        ]);
        
        $this->dispatchBrowserEvent('alert', [
            'type'    => 'success',
            'message' => __('External Radio Added Successfully.')
        ]);
        $this->resetInputFields();
    }
    
    public function editRadio(int $id)
    {
        $subscriber = auth()->guard('subscriber')->user();
        $radio = ExternalRadioConfiguration::where('subscriber_id', $subscriber->id)
            ->findOrFail($id);
        $this->radioId   = $radio->id;
        $this->radioName = $radio->radio_name;
        $this->streamUrl = $radio->stream_url;
        $this->status    = $radio->status;
    }
    
    public function updateRadio()
    {
        $this->validate();
        
        $subscriber = auth()->guard('subscriber')->user();
        if ($this->radioId) {
            $radio = ExternalRadioConfiguration::where('subscriber_id', $subscriber->id)
                ->findOrFail($this->radioId);
            $radioNameSlug = Str::slug($this->radioName, '-', '');
            $radio->update([
                'radio_name'      => $this->radioName,
                'radio_name_slug' => $radioNameSlug,
                'stream_url'      => $this->streamUrl,
            ]);
    
            $this->dispatchBrowserEvent('alert', [
                'type'    => 'success',
                'message' => __('External Radio Updated Successfully.')
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
        $this->radioId   = null;
        $this->radioName = null;
        $this->streamUrl = null;
    }
    
    public function render()
    {
        $subscriber = auth()->guard('subscriber')->user();
        $this->activeCount = ExternalRadioConfiguration::where('subscriber_id', $subscriber->id)
            ->where('status', 1)->count();
        $this->nonActiveCount = ExternalRadioConfiguration::where('subscriber_id', $subscriber->id)
            ->where('status', 0)->count();
    
        $query = ExternalRadioConfiguration::where('subscriber_id', $subscriber->id);
    
        if ($this->statusFilter === 'active') {
            $query->where('status', 1);
        } elseif ($this->statusFilter === 'non-active') {
            $query->where('status', 0);
        }
    
        if (!empty($this->search)) {
            $query->where('radio_name', 'like', '%' . $this->search . '%');
        }
    
        $tableData = $query->orderBy('created_at', 'ASC')->paginate(10)->withQueryString();
    
        return view('subscriber.pages.e-radio.radio-table', [
            'tableData' => $tableData,
        ]);
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
            $radio = ExternalRadioConfiguration::where('subscriber_id', $subscriber->id)
                ->findOrFail($this->deleteRadioId);
            $radio->delete();
            $this->dispatchBrowserEvent('alert', [
                'type'    => 'success',
                'message' => __('External Radio Deleted Successfully.')
            ]);
            $this->resetInputFields();
        }
    }

    public function changeTab($status)
    {
        $this->statusFilter = $status;
        $this->page = 1;
        $this->emitSelf('refresh');
    }
}
