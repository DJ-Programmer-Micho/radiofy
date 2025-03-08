<?php

namespace App\Http\Livewire\Admin;


use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Language;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class LanguageLivewire extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['statusFilter', 'page'];

    // Language fields
    public $languageId;
    public $code;
    public $name;
    public $priority;
    public $status = 1;

    // Render/search parameters
    public $search = '';
    public $statusFilter = 'all';
    public $page = 1;
    public $activeCount = 0;
    public $nonActiveCount = 0;

    public $deletelanguageId;
    public $languageNameToDelete = '';

    // Listen for the FilePond upload event
    protected $listeners = ['fileUploaded'];

    public function mount()
    {
        $this->status = 1;
        $this->statusFilter = request()->query('statusFilter', 'all');
        $this->priority = Language::max('priority') + 1;
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
            'code'     => ['required', 'string', 'unique:languages,code'],
            'name'     => ['required', 'string'],
            'priority' => ['required', 'integer'],
            'status'   => ['required', 'in:0,1'],
        ];
    }

    // Validation rules for updating an existing language
    protected function rulesForUpdate()
    {
        return [
            'code'     => ['required', 'string', Rule::unique('languages', 'code')->ignore($this->languageId)],
            'name'     => ['required', 'string'],
            'priority' => ['required', 'integer'],
            'status'   => ['required', 'in:0,1'],
        ];
    }


    // Saving a new Language
    public function saveLanguage()
    {
        $validatedData = $this->validate($this->rulesForSave());
        Language::create($validatedData);
    
        $this->resetInput();
        $this->closeModal();
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => __('New Language Added Successfully')
        ]);
    }

    // Editing an existing Language
    public function editLanguage($id)
    {
        $language = Language::findOrFail($id);
        $this->languageId = $language->id;
        $this->code       = $language->code;
        $this->name       = $language->name;
        $this->priority   = $language->priority;
        $this->status     = $language->status;
    }
    
    // Updating an existing Language
    public function updateLanguage()
    {
        $validatedData = $this->validate($this->rulesForUpdate());
        if ($this->languageId) {
            $language = Language::findOrFail($this->languageId);
            $language->update($validatedData);

            $this->resetInput();
            $this->closeModal();
            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => __('Language updated successfully.')
            ]);
        }
    }

    public function confirmDeleteLanguage($id)
    {
        $this->deletelanguageId = $id;
        $this->languageNameToDelete = '';
    }

    // Delete the Language record and its image if confirmation text matches.
    public function destroyLanguage()
    {
        if ($this->languageNameToDelete !== 'DELETE LANGUAGE') {
            return;
        }

        $lang = Language::findOrFail($this->deletelanguageId);
        $lang->delete();

        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => __('Language deleted successfully.')
        ]);
        $this->resetInput();
        $this->closeModal();
    }

    public function updateStatus(int $id)
    {
        // Find the brand by ID, if not found return an error
        $dataStatus = Language::find($id);
    
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
        $brand = Language::find($p_id);
        
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
        $this->languageId = null;
        $this->status = 1;
        $this->code = null;
        $this->name = null;
        $this->priority = Language::max('priority') + 1;
        $this->deletelanguageId = null;
        $this->languageNameToDelete = '';
    }
    
    public function closeModal()
    {
        $this->dispatchBrowserEvent('close-modal');
        $this->resetInput();
    }
    
    public function render()
    {
        $this->activeCount = Language::where('status', 1)->count();
        $this->nonActiveCount = Language::where('status', 0)->count();

        $query = Language::query();

        if ($this->statusFilter === 'active') {
            $query->where('status', 1);
        } elseif ($this->statusFilter === 'non-active') {
            $query->where('status', 0);
        }

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('code', 'like', '%' . $this->search . '%')
                  ->orWhere('name', 'like', '%' . $this->search . '%');
            });
        }

        $tableData = $query->orderBy('priority', 'ASC')->paginate(10)->withQueryString();

        return view('admin.pages.language.language-table', [
            'tableData' => $tableData,
        ]);
    }
}
