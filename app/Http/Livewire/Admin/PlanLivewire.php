<?php

namespace App\Http\Livewire\Admin;

use App\Models\Plan;
use Livewire\Component;
use Livewire\WithPagination;

class PlanLivewire extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['statusFilter', 'page'];

    // Language fields
    public $planId;
    public $bitrate;
    public $max_listeners;
    public $sell_price;
    public $priority;
    public $status = 1;

    // Render/search parameters
    public $search = '';
    public $statusFilter = 'all';
    public $page = 1;
    public $activeCount = 0;
    public $nonActiveCount = 0;

    public $deletePlanId;
    public $planNameToDelete  = '';

    // Listen for the FilePond upload event
    protected $listeners = ['fileUploaded'];

    public function mount()
    {
        $this->status = 1;
        $this->statusFilter = request()->query('statusFilter', 'all');
        $this->priority = Plan::max('priority') + 1;
    }

    public function changeTab($status)
    {
        $this->statusFilter = $status;
        $this->page = 1;
        $this->emitSelf('refresh');
    }

    // Validation rules for saving a new language
    protected function rulesForSave()
    {
        return [
            'bitrate'       => ['required', 'integer'],
            'max_listeners' => ['required', 'integer'],
            'sell_price'    => ['required', 'numeric'],
            'priority'      => ['required', 'integer'],
            'status'        => ['required', 'in:0,1'],
        ];
    }

    // Validation rules for updating an existing language
    protected function rulesForUpdate()
    {
        return [
            'bitrate'       => ['required', 'integer'],
            'max_listeners' => ['required', 'integer'],
            'sell_price'    => ['required', 'numeric'],
            'priority'      => ['required', 'integer'],
            'status'        => ['required', 'in:0,1'],
        ];
    }


    // Saving a new Language
    public function savePlan()
    {
        $validatedData = $this->validate($this->rulesForSave());
        Plan::create($validatedData);

        $this->resetInput();
        $this->closeModal();
        $this->dispatchBrowserEvent('alert', [
            'type'    => 'success',
            'message' => __('New Plan Added Successfully')
        ]);
    }

    // Editing an existing Language
    public function editPlan($id)
    {
        $plan = Plan::findOrFail($id);
        $this->planId        = $plan->id;
        $this->bitrate       = $plan->bitrate;
        $this->max_listeners = $plan->max_listeners;
        $this->sell_price    = $plan->sell_price;
        $this->priority      = $plan->priority;
        $this->status        = $plan->status;
    }
    
    // Updating an existing Language
    public function updatePlan()
    {
        $validatedData = $this->validate($this->rulesForUpdate());
        if ($this->planId) {
            $plan = Plan::findOrFail($this->planId);
            $plan->update($validatedData);

            $this->resetInput();
            $this->closeModal();
            $this->dispatchBrowserEvent('alert', [
                'type'    => 'success',
                'message' => __('Plan updated successfully.')
            ]);
        }
    }

    public function confirmDeletePlan($id)
    {
        $this->deletePlanId = $id;
        $this->planNameToDelete  = '';
        $this->dispatchBrowserEvent('open-delete-plan-modal');
    }

    public function destroyPlan()
    {
        if ($this->planNameToDelete  !== 'DELETE PLAN') {
            return;
        }

        $plan = Plan::findOrFail($this->deletePlanId);
        $plan->delete();

        $this->dispatchBrowserEvent('alert', [
            'type'    => 'success',
            'message' => __('Plan deleted successfully.')
        ]);
        $this->resetInput();
        $this->closeModal();
        $this->closeDeleteModal();
    }

    public function updateStatus(int $id)
    {
        // Find the brand by ID, if not found return an error
        $dataStatus = Plan::find($id);
    
        if ($dataStatus) {
            // Toggle the status (0 to 1 and 1 to 0)
            $dataStatus->status = !$dataStatus->status;
            $dataStatus->save();
    
            // Dispatch a browser event to show success message
            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => __('Status Updated Successfully')
            ]);
        } else {
            // Dispatch a browser event to show error message
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' => __('Record Not Found')
            ]);
        }
    }

    public function updatePriority(int $p_id, $updatedPriority)
    {
        // Validate if updatedPriority is a number
        if (!is_numeric($updatedPriority)) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',  
                'message' => __('Invalid priority value')
            ]);
            return;
        }
    
        // Find the brand by ID
        $brand = Plan::find($p_id);
        
        if ($brand) {
            $brand->priority = $updatedPriority;
            $brand->save();
            
            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',  
                'message' => __('Priority Updated Successfully')
            ]);
        } else {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',  
                'message' => __('Record Not Found')
            ]);
        }
    }
    
    public function resetInput()
    {
        $this->planId = null;
        $this->bitrate = null;
        $this->max_listeners = null;
        $this->sell_price = null;
        $this->priority = Plan::max('priority') + 1;
        $this->status = 1;
        $this->deletePlanId = null;
        $this->planNameToDelete  = '';
    }
    public function closeModal()
    {
        $this->dispatchBrowserEvent('close-modal');
        $this->resetInput();
    }
    
    public function render()
    {
        $this->activeCount = Plan::where('status', 1)->count();
        $this->nonActiveCount = Plan::where('status', 0)->count();

        $query = Plan::query();

        if ($this->statusFilter === 'active') {
            $query->where('status', 1);
        } elseif ($this->statusFilter === 'non-active') {
            $query->where('status', 0);
        }

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('bitrate', 'like', '%' . $this->search . '%')
                  ->orWhere('max_listeners', 'like', '%' . $this->search . '%')
                  ->orWhere('sell_price', 'like', '%' . $this->search . '%');
            });
        }

        $tableData = $query->orderBy('priority', 'ASC')->paginate(10)->withQueryString();
        return view('admin.pages.plan.plan-table', [
            'tableData' => $tableData,
        ]);
    }
}
