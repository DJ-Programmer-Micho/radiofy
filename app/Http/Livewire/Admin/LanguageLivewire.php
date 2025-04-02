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
    use WithFileUploads;

    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['statusFilter', 'page'];

    // Language fields
    public $languageId;
    public $code;
    public $name;
    public $priority;
    public $status = 1;
    public $objectName;   // Landscape image file path
    public $objectNameSq; // Square image file path

    // Render/search parameters
    public $search = '';
    public $statusFilter = 'all';
    public $page = 1;
    public $activeCount = 0;
    public $nonActiveCount = 0;

    public $deletelanguageId;
    public $languageNameToDelete = '';

    // Listen for the FilePond upload event
    protected $listeners = ['fileUploaded','fileUploadedSq'];

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
            'name'     => ['required', 'string', 'unique:languages,name'],
            'priority' => ['required', 'integer'],
            'status'   => ['required', 'in:0,1'],
        ];
    }

    // Validation rules for updating an existing language
    protected function rulesForUpdate()
    {
        return [
            'code'     => ['required', 'string', Rule::unique('languages', 'code')->ignore($this->languageId)],
            'name'     => ['required', 'string', Rule::unique('languages', 'name')->ignore($this->languageId)],
            'priority' => ['required', 'integer'],
            'status'   => ['required', 'in:0,1'],
        ];
    }


    // Saving a new Language
    public function saveLanguage()
    {
        $validatedData = $this->validate($this->rulesForSave());
        // Move landscape image from temporary to permanent storage
        if (strpos($this->objectName, 'tmp/lang') === 0) {
            $newPath = str_replace('tmp/lang', 'lang', $this->objectName);
            Storage::disk('public')->move($this->objectName, $newPath);
            $this->objectName = $newPath;
        }

        // Move square image from temporary to permanent storage
        if (strpos($this->objectNameSq, 'tmp/lang') === 0) {
            $newPathSq = str_replace('tmp/lang', 'lang', $this->objectNameSq);
            Storage::disk('public')->move($this->objectNameSq, $newPathSq);
            $this->objectNameSq = $newPathSq;
        }

        Language::create([
            'code' => $validatedData['code'],
            'name' => $validatedData['name'],
            'priority' => $validatedData['priority'],
            'status' => $validatedData['status'],
            'image'    => $this->objectName,
            'image_sq' => $this->objectNameSq,
        ]);
        
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
        $this->objectName = $language->image;
        $this->objectNameSq = $language->image_sq;
    
        // Emit events for FilePond preview
        if ($this->objectName) {
            $this->dispatchBrowserEvent('setFilePondFile', ['file' => asset('storage/' . $this->objectName)]);
        }
    
        if ($this->objectNameSq) {
            $this->dispatchBrowserEvent('setFilePondFileSq', ['file' => asset('storage/' . $this->objectNameSq)]);
        }
    }    
    
    // Updating an existing Language
    public function updateLanguage()
    {
        $validatedData = $this->validate($this->rulesForUpdate());

        if ($this->languageId) {
            $language = Language::findOrFail($this->languageId);

            // Move landscape image if newly uploaded
            if (strpos($this->objectName, 'tmp/lang') === 0) {
                $newPath = str_replace('tmp/lang', 'lang', $this->objectName);
                Storage::disk('public')->move($this->objectName, $newPath);
                $this->objectName = $newPath;
            }

            // Move square image if newly uploaded
            if (strpos($this->objectNameSq, 'tmp/lang') === 0) {
                $newPathSq = str_replace('tmp/lang', 'lang', $this->objectNameSq);
                Storage::disk('public')->move($this->objectNameSq, $newPathSq);
                $this->objectNameSq = $newPathSq;
            }

            $language->update([
                'code' => $validatedData['code'],
                'name' => $validatedData['name'],
                'priority' => $validatedData['priority'],
                'status' => $validatedData['status'],
                'image' => $this->objectName,
                'image_sq' => $this->objectNameSq,
            ]);

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
        $this->objectName = null;
        $this->objectNameSq = null;
        $this->deletelanguageId = null;
        $this->languageNameToDelete = '';
    
        $this->dispatchBrowserEvent('clearFilePondInputs');
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

    public function fileUploaded($serverId)
    {
        $cleaned = trim($serverId, '"');
        if ($this->objectName && $this->objectName !== $cleaned && strpos($this->objectName, 'tmp/lang') === 0) {
            Storage::disk('public')->delete($this->objectName);
        }
        $this->objectName = $cleaned;
    }

    public function fileUploadedSq($serverId)
    {
        $cleaned = trim($serverId, '"');
        if ($this->objectNameSq && $this->objectNameSq !== $cleaned && strpos($this->objectNameSq, 'tmp/lang') === 0) {
            Storage::disk('public')->delete($this->objectNameSq);
        }
        $this->objectNameSq = $cleaned;
    }
}
