<?php

namespace App\Http\Livewire\Admin;

use App\Models\Verification;
use Livewire\Component;
use Livewire\WithPagination;

class VerifyLivewire extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['statusFilter', 'page'];

    public $verifyId;
    public $name;
    public $sellPriceMonthly;
    public $sellPriceYearly;
    public $priority;
    public $status = 1;
    public $ribbon = 0;
    public $ribbonText;
    public $features = []; 

    public $search = '';
    public $statusFilter = 'all';
    public $page = 1;
    public $activeCount = 0;
    public $nonActiveCount = 0;

    public $deleteVerifyId;
    public $verifyNameToDelete = '';

    public function mount()
    {
        $this->status = 1;
        $this->statusFilter = request()->query('statusFilter', 'all');
        $this->priority = Verification::max('priority') + 1;
    }

    
    public function addFeature()
    {
        // Add a new feature item with default values.
        $this->features[] = ['text' => '', 'check' => 1];
    }
    
    public function removeFeature($index)
    {
        // Remove the feature item at the given index.
        array_splice($this->features, $index, 1);
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
            'name'              => ['required', 'string'],
            'sellPriceMonthly'  => ['required', 'numeric'],
            'sellPriceYearly'   => ['required', 'numeric'],
            'priority'          => ['required', 'integer'],
            'ribbon'            => ['required', 'in:0,1'],
            'status'            => ['required', 'in:0,1'],
            'ribbonText'        => ['nullable', 'string'],
            'features'          => ['nullable', 'array'],
            'features.*.text'   => ['required', 'string'],
            'features.*.check'  => ['required', 'in:0,1'],
        ];
    }

    protected function rulesForUpdate()
    {
        return $this->rulesForSave();
    }

    public function saveVerification()
    {
        $validated = $this->validate($this->rulesForSave());

        Verification::create([
            'name' => $validated['name'],
            'sell_price_monthly' => $validated['sellPriceMonthly'],
            'sell_price_yearly' => $validated['sellPriceYearly'],
            'priority' => $validated['priority'],
            'ribbon' => $validated['ribbon'],
            'status' => $validated['status'],
            'rib_text' => $validated['ribbonText'],
            'features' => $validated['features'] ?? [],
        ]);

        $this->resetInput();
        $this->dispatchBrowserEvent('alert', ['type' => 'success', 'message' => __('Verification plan created successfully.')]);
    }

    public function editVerification($id)
    {
        $verify = Verification::findOrFail($id);

        $this->verifyId = $verify->id;
        $this->name = $verify->name;
        $this->sellPriceMonthly = $verify->sell_price_monthly;
        $this->sellPriceYearly = $verify->sell_price_yearly;
        $this->priority = $verify->priority;
        $this->status = $verify->status;
        $this->ribbon = $verify->ribbon;
        $this->ribbonText = $verify->rib_text;
        $this->features = $verify->features;
    }

    public function updateVerification()
    {
        $validated = $this->validate($this->rulesForUpdate());

        if ($this->verifyId) {
            $verify = Verification::findOrFail($this->verifyId);
            $verify->update([
                'name' => $validated['name'],
                'sell_price_monthly' => $validated['sellPriceMonthly'],
                'sell_price_yearly' => $validated['sellPriceYearly'],
                'priority' => $validated['priority'],
                'ribbon' => $validated['ribbon'],
                'status' => $validated['status'],
                'rib_text' => $validated['ribbonText'],
                'features' => $validated['features'] ?? [],
            ]);

            $this->resetInput();
            $this->dispatchBrowserEvent('alert', ['type' => 'success', 'message' => __('Verification plan updated successfully.')]);
        }
    }

    public function confirmDeleteVerification($id)
    {
        $this->deleteVerifyId = $id;
        $this->verifyNameToDelete = '';
        $this->dispatchBrowserEvent('open-delete-verification-modal');
    }

    public function destroyVerification()
    {
        if ($this->verifyNameToDelete !== 'DELETE VERIFICATION') {
            return;
        }

        $verify = Verification::findOrFail($this->deleteVerifyId);
        $verify->delete();

        $this->dispatchBrowserEvent('alert', ['type' => 'success', 'message' => __('Verification plan deleted.')]);
        $this->resetInput();
    }

    public function updateStatus($id)
    {
        $verify = Verification::findOrFail($id);
        $verify->status = !$verify->status;
        $verify->save();

        $this->dispatchBrowserEvent('alert', ['type' => 'success', 'message' => __('Status updated.')]);
    }

    public function updatePriority($id, $priority)
    {
        if (!is_numeric($priority)) {
            $this->dispatchBrowserEvent('alert', ['type' => 'error', 'message' => __('Invalid priority.')]);
            return;
        }

        $verify = Verification::findOrFail($id);
        $verify->priority = $priority;
        $verify->save();

        $this->dispatchBrowserEvent('alert', ['type' => 'success', 'message' => __('Priority updated.')]);
    }

    public function resetInput()
    {
        $this->verifyId = null;
        $this->name = null;
        $this->sellPriceMonthly = null;
        $this->sellPriceYearly = null;
        $this->priority = Verification::max('priority') + 1;
        $this->status = 1;
        $this->ribbon = 0;
        $this->ribbonText = null;
        $this->features = [];
        $this->deleteVerifyId = null;
        $this->verifyNameToDelete = '';
    }

    public function render()
    {
        $this->activeCount = Verification::where('status', 1)->count();
        $this->nonActiveCount = Verification::where('status', 0)->count();

        $query = Verification::query();

        if ($this->statusFilter === 'active') {
            $query->where('status', 1);
        } elseif ($this->statusFilter === 'non-active') {
            $query->where('status', 0);
        }

        if (!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        $tableData = $query->orderBy('priority')->paginate(10)->withQueryString();

        return view('admin.pages.verify.verify-table', [
            'tableData' => $tableData,
        ]);
    }
}
