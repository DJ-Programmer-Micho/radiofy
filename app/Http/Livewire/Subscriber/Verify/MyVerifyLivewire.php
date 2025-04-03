<?php

namespace App\Http\Livewire\Subscriber\Verify;

use Carbon\Carbon;
use App\Models\RadioVerification;
use Livewire\Component;
use Livewire\WithPagination;

class MyVerifyLivewire extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    
    public $search = '';
    public $page = 1;
    
    public function mount(){
        $this->page = request()->query('page', 1);
    }

    public function render()
    {
        // Restrict to verifications for the current subscriber
        $subscriberId = auth()->guard('subscriber')->id();
        $query = RadioVerification::where('subscriber_id', $subscriberId);

        if (!empty($this->search)) {
            // Assuming that the related radio has a "radio_name" field.
            $query->whereHas('radioable', function($q) {
                $q->where('radio_name', 'like', '%' . $this->search . '%');
            });
        }

        $tableData = $query->orderBy('created_at', 'ASC')->paginate(10)->withQueryString();

        return view('subscriber.pages.my-verify.verify-table', [
            'tableData' => $tableData,
        ]);
    }
}
