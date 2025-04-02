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
    
    // Render / search parameters
    public $search = '';
    public $page = 1;
    
    public function mount(){
        $this->page = request()->query('page', 1);
    }

    public function render()
    {
        // Query RadioVerification records for the current subscriber,
        // eager load the associated radio (internal or external)
        $query = RadioVerification::where('subscriber_id', auth()->guard('subscriber')->id());

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
