<?php

namespace App\Http\Livewire\Admin;

use App\Models\Promotion;
use Livewire\Component;
use Livewire\WithPagination;

class PromotionLivewire extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['statusFilter', 'page'];

    public $promoId;
    public $name;
    public $subName;
    public $sell;
    public $duration;
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

    public $deletepromoId;
    public $promoNameToDelete = '';

    public function mount()
    {
        $this->status = 1;
        $this->statusFilter = request()->query('statusFilter', 'all');
        $this->priority = Promotion::max('priority') + 1;
    }

    
    public function addFeature()
    {
        // Add a new feature item with default values.
        $this->features[] = ['key' => '', 'value' => '', 'check' => 1];
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
            'subName'              => ['required', 'string'],
            'sell'  => ['required', 'numeric'],
            'duration'   => ['required', 'integer'],
            'priority'          => ['required', 'integer'],
            'ribbon'            => ['required', 'in:0,1'],
            'status'            => ['required', 'in:0,1'],
            'ribbonText'        => ['nullable', 'string'],
            'features'          => ['nullable', 'array'],
            'features.*.key'   => ['required', 'string'],
            'features.*.value'   => ['required', 'string'],
            'features.*.check'  => ['required', 'in:0,1'],
        ];
    }

    protected function rulesForUpdate()
    {
        return $this->rulesForSave();
    }

    public function savePromotion()
    {
        $validated = $this->validate($this->rulesForSave());

        Promotion::create([
            'name' => $validated['name'],
            'sub_name' => $validated['subName'],
            'sell' => $validated['sell'],
            'duration' => $validated['duration'],
            'priority' => $validated['priority'],
            'ribbon' => $validated['ribbon'],
            'status' => $validated['status'],
            'rib_text' => $validated['ribbonText'],
            'features' => $validated['features'] ?? [],
        ]);

        $this->resetInput();
        $this->dispatchBrowserEvent('alert', ['type' => 'success', 'message' => __('Promotion plan created successfully.')]);
    }

    public function editPromotion($id)
    {
        $promo = Promotion::findOrFail($id);

        $this->promoId = $promo->id;
        $this->name = $promo->name;
        $this->subName = $promo->sub_name;
        $this->sell = $promo->sell;
        $this->duration = $promo->duration;
        $this->priority = $promo->priority;
        $this->status = $promo->status;
        $this->ribbon = $promo->ribbon;
        $this->ribbonText = $promo->rib_text;
        $this->features = $promo->features;
    }

    public function updatePromotion()
    {
        $validated = $this->validate($this->rulesForUpdate());

        if ($this->promoId) {
            $promo = Promotion::findOrFail($this->promoId);
            $promo->update([
                'name' => $validated['name'],
                'sub_name' => $validated['subName'],
                'sell' => $validated['sell'],
                'duration' => $validated['duration'],
                'priority' => $validated['priority'],
                'ribbon' => $validated['ribbon'],
                'status' => $validated['status'],
                'rib_text' => $validated['ribbonText'],
                'features' => $validated['features'] ?? [],
            ]);

            $this->resetInput();
            $this->dispatchBrowserEvent('alert', ['type' => 'success', 'message' => __('Promotion plan updated successfully.')]);
        }
    }

    public function confirmDeletePromotion($id)
    {
        $this->deletepromoId = $id;
        $this->promoNameToDelete = '';
        $this->dispatchBrowserEvent('open-delete-Promotion-modal');
    }

    public function destroyPromotion()
    {
        if ($this->promoNameToDelete !== 'DELETE PROMOTION') {
            return;
        }

        $promo = Promotion::findOrFail($this->deletepromoId);
        $promo->delete();

        $this->dispatchBrowserEvent('alert', ['type' => 'success', 'message' => __('Promotion plan deleted.')]);
        $this->resetInput();
    }

    public function updateStatus($id)
    {
        $promo = Promotion::findOrFail($id);
        $promo->status = !$promo->status;
        $promo->save();

        $this->dispatchBrowserEvent('alert', ['type' => 'success', 'message' => __('Status updated.')]);
    }

    public function updatePriority($id, $priority)
    {
        if (!is_numeric($priority)) {
            $this->dispatchBrowserEvent('alert', ['type' => 'error', 'message' => __('Invalid priority.')]);
            return;
        }

        $promo = Promotion::findOrFail($id);
        $promo->priority = $priority;
        $promo->save();

        $this->dispatchBrowserEvent('alert', ['type' => 'success', 'message' => __('Priority updated.')]);
    }

    public function resetInput()
    {
        $this->promoId = null;
        $this->name = null;
        $this->subName = null;
        $this->sell = null;
        $this->duration = null;
        $this->priority = Promotion::max('priority') + 1;
        $this->status = 1;
        $this->ribbon = 0;
        $this->ribbonText = null;
        $this->features = [];
        $this->deletepromoId = null;
        $this->promoNameToDelete = '';
    }

    public function closeModal()
    {
        $this->dispatchBrowserEvent('close-modal');
        $this->resetInput();
    }
    
    public function render()
    {
        $this->activeCount = Promotion::where('status', 1)->count();
        $this->nonActiveCount = Promotion::where('status', 0)->count();

        $query = Promotion::query();

        if ($this->statusFilter === 'active') {
            $query->where('status', 1);
        } elseif ($this->statusFilter === 'non-active') {
            $query->where('status', 0);
        }

        if (!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        $tableData = $query->orderBy('priority')->paginate(10)->withQueryString();

        return view('admin.pages.promotion.promotion-table', [
            'tableData' => $tableData,
        ]);
    }
}
