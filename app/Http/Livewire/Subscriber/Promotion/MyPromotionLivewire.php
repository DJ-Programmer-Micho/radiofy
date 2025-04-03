<?php

namespace App\Http\Livewire\Subscriber\Promotion;

use App\Models\RadioPromotion;
use Livewire\Component;
use Livewire\WithPagination;

class MyPromotionLivewire extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $page = 1;
    
    // Properties for editing
    public $editingPromotionId;
    public $editingTargetGenders = [];  // new filters as arrays
    public $editingTargetAgeRanges = [];
    public $editingSponserText;  // if you want to update the promotion text

    public function mount(){
        $this->page = request()->query('page', 1);
    }

    public function editPromotion($id)
    {
        // Load the promotion record to be edited
        $promotionCampaign = RadioPromotion::findOrFail($id);
        // Set properties for the modal (assuming target_gender and target_age_range are JSON arrays)
        $this->editingPromotionId = $promotionCampaign->id;
        $this->editingTargetGenders = $promotionCampaign->target_gender ?? [];
        $this->editingTargetAgeRanges = $promotionCampaign->target_age_range ?? [];
        $this->editingSponserText = $promotionCampaign->promotion_text;
        // Dispatch event to open the edit modal via JS if needed
        $this->dispatchBrowserEvent('open-edit-promotion-modal');
    }

    public function updatePromotion()
    {
        // Validate the edited inputs; adjust rules as needed
        $this->validate([
            'editingTargetGenders' => 'required|array',
            'editingTargetGenders.*' => 'integer|in:1,2,3',
            'editingTargetAgeRanges' => 'required|array',
            'editingTargetAgeRanges.*' => 'in:13-17,18-25,26-32,33-40,41-64,65+',
            'editingSponserText' => 'required|string|max:50',
        ]);

        $promotionCampaign = RadioPromotion::findOrFail($this->editingPromotionId);
        $promotionCampaign->update([
            'promotion_text'   => $this->editingSponserText,
            'target_gender'    => $this->editingTargetGenders,
            'target_age_range' => $this->editingTargetAgeRanges,
        ]);

        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => __('Campaign updated successfully.')
        ]);

        $this->dispatchBrowserEvent('close-edit-modal');
        $this->reset(['editingPromotionId', 'editingTargetGenders', 'editingTargetAgeRanges', 'editingSponserText']);
    }

    public function updateStatus(int $id)
    {
        $dataStatus = RadioPromotion::find($id);

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
    
    public function closeModal()
    {
        $this->dispatchBrowserEvent('close-modal');
    }
    
    public function render()
    {
        // Restrict to promotions belonging to the current subscriber
        $subscriberId = auth()->guard('subscriber')->id();
        $query = RadioPromotion::where('subscriber_id', $subscriberId);

        if (!empty($this->search)) {
            $query->where(function ($query) {
                $query->whereHas('promotion', function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })->orWhereHas('radioable', function($q) {
                    $q->where('radio_name', 'like', '%' . $this->search . '%');
                });
            });
        }

        $tableData = $query->orderBy('created_at', 'ASC')->paginate(10)->withQueryString();

        return view('subscriber.pages.my-promotion.promotion-table', [
            'tableData' => $tableData,
        ]);
    }
}
