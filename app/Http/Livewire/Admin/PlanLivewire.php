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

    public $planId;
    public $bitrate;
    public $maxListeners;
    public $sellPriceMonthly;
    public $sellPriceYearly;
    public $priority;
    public $status = 1;
    public $support = 0;
    public $ribbon = 0;
    public $ribbonText;

    public $search = '';
    public $statusFilter = 'all';
    public $page = 1;
    public $activeCount = 0;
    public $nonActiveCount = 0;

    public $deletePlanId;
    public $planNameToDelete  = '';

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

    protected function rulesForSave()
    {
        return [
            'bitrate'       => ['required', 'integer'],
            'maxListeners' => ['required', 'integer'],
            'sellPriceMonthly' => ['required', 'numeric'],
            'sellPriceYearly'  => ['required', 'numeric'],
            'priority'      => ['required', 'integer'],
            'support'       => ['required', 'in:0,1'],
            'ribbon'        => ['required', 'in:0,1'],
            'status'        => ['required', 'in:0,1'],
            'ribbonText'    => ['required', 'string'],
        ];
    }

    protected function rulesForUpdate()
    {
        return $this->rulesForSave();
    }

    public function savePlan()
    {
        $validatedData = $this->validate($this->rulesForSave());
        Plan::create([
           'bitrate' => $validatedData['bitrate'],
            'max_listeners' => $validatedData['maxListeners'],
            'sell_price_monthly' => $validatedData['sellPriceMonthly'],
            'sell_price_yearly' => $validatedData['sellPriceYearly'],
            'priority' => $validatedData['priority'],
            'support' => $validatedData['support'],
            'ribbon' => $validatedData['ribbon'],
            'status' => $validatedData['status'],
            'rib_text'  => $validatedData['ribbonText']
        ]);

        $this->resetInput();
        $this->closeModal();
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => __('New Plan Added Successfully')
        ]);
    }

    public function editPlan($id)
    {
        $plan = Plan::findOrFail($id);
        $this->planId           = $plan->id;
        $this->bitrate          = $plan->bitrate;
        $this->maxListeners     = $plan->max_listeners;
        $this->sellPriceMonthly = $plan->sell_price_monthly;
        $this->sellPriceYearly  = $plan->sell_price_yearly;
        $this->priority         = $plan->priority;
        $this->status           = $plan->status;
        $this->support          = $plan->support;
        $this->ribbon           = $plan->ribbon;
        $this->ribbonText       = $plan->rib_text;
    }

    public function updatePlan()
    {
        $validatedData = $this->validate($this->rulesForUpdate());

        if ($this->planId) {
            $plan = Plan::findOrFail($this->planId);
            $plan->update([
                'bitrate' => $validatedData['bitrate'],
                 'max_listeners' => $validatedData['maxListeners'],
                 'sell_price_monthly' => $validatedData['sellPriceMonthly'],
                 'sell_price_yearly' => $validatedData['sellPriceYearly'],
                 'priority' => $validatedData['priority'],
                 'support' => $validatedData['support'],
                 'ribbon' => $validatedData['ribbon'],
                 'status' => $validatedData['status'],
                 'rib_text'  => $validatedData['ribbonText']
             ]);

            $this->resetInput();
            $this->closeModal();
            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => __('Plan updated successfully.')
            ]);
        }
    }

    public function confirmDeletePlan($id)
    {
        $this->deletePlanId = $id;
        $this->planNameToDelete = '';
        $this->dispatchBrowserEvent('open-delete-plan-modal');
    }

    public function destroyPlan()
    {
        if ($this->planNameToDelete !== 'DELETE PLAN') {
            return;
        }

        $plan = Plan::findOrFail($this->deletePlanId);
        $plan->delete();

        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => __('Plan deleted successfully.')
        ]);
        $this->resetInput();
        $this->closeModal();
        $this->closeDeleteModal();
    }

    public function updateStatus(int $id)
    {
        $dataStatus = Plan::find($id);

        if ($dataStatus) {
            $dataStatus->status = !$dataStatus->status;
            $dataStatus->save();

            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => __('Status Updated Successfully')
            ]);
        } else {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' => __('Record Not Found')
            ]);
        }
    }

    public function updatePriority(int $p_id, $updatedPriority)
    {
        if (!is_numeric($updatedPriority)) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' => __('Invalid priority value')
            ]);
            return;
        }

        $plan = Plan::find($p_id);

        if ($plan) {
            $plan->priority = $updatedPriority;
            $plan->save();

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
        $this->maxListeners = null;
        $this->sellPriceMonthly = null;
        $this->sellPriceYearly = null;
        $this->priority = Plan::max('priority') + 1;
        $this->status = 1;
        $this->support = 0;
        $this->ribbon = 0;
        $this->ribbonText = null;
        $this->deletePlanId = null;
        $this->planNameToDelete = '';
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
                  ->orWhere('sell_price_monthly', 'like', '%' . $this->search . '%')
                  ->orWhere('sell_price_yearly', 'like', '%' . $this->search . '%');
            });
        }

        $tableData = $query->orderBy('priority', 'ASC')->paginate(10)->withQueryString();

        return view('admin.pages.plan.plan-table', [
            'tableData' => $tableData,
        ]);
    }
}
