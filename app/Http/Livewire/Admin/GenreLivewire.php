<?php

namespace App\Http\Livewire\Admin;

use App\Models\Genre;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\GenreTranslater;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class GenreLivewire extends Component
{
    use WithPagination;
    use WithFileUploads;

    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['statusFilter', 'page'];

    // Genre fields
    public $genreId;
    public $priority;
    public $status = 1;
    public $objectName; // Will store the file path returned from FilePond

    // Translation fields (one per locale)
    public $genres = []; // e.g., ['en' => 'Pop', 'ar' => 'بوب', 'ku' => 'Pop']
    public $filteredLocales;

    // Render/search parameters
    public $search = '';
    public $statusFilter = 'all';
    public $page = 1;
    public $activeCount = 0;
    public $nonActiveCount = 0;

    public $deleteGenreId;
    public $genreNameToDelete = '';

    // Listen for the FilePond upload event
    protected $listeners = ['fileUploaded'];

    public function mount()
    {
        $this->status = 1;
        $this->filteredLocales = ['en', 'ar', 'ku'];
        $this->statusFilter = request()->query('statusFilter', 'all');
        $this->priority = Genre::max('priority') + 1;
    }

    public function changeTab($status)
    {
        $this->statusFilter = $status;
        $this->page = 1;
        $this->emitSelf('refresh');
    }

    // Rules for saving a new Genre
    protected function rulesForSave()
    {
        $rules = [];
        foreach ($this->filteredLocales as $locale) {
            $rules['genres.' . $locale] = [
                'required',
                'string',
                'min:1',
                Rule::unique('genre_translations', 'name')->where('locale', $locale)
            ];
        }
        $rules['priority'] = ['required', 'integer'];
        $rules['status'] = ['required', 'in:0,1'];
        $rules['objectName'] = ['required', 'string'];
        return $rules;
    }

    // Rules for updating an existing Genre
    protected function rulesForUpdate()
    {
        $rules = [];
        foreach ($this->filteredLocales as $locale) {
            $rules['genres.' . $locale] = [
                'required',
                'string',
                'min:1',
                Rule::unique('genre_translations', 'name')
                    ->where('locale', $locale)
                    ->ignore($this->genreId, 'genre_id')
            ];
        }
        $rules['priority'] = ['required', 'integer'];
        $rules['status'] = ['required', 'in:0,1'];
        $rules['objectName'] = ['required', 'string'];
        return $rules;
    }

    // Saving a new genre
    public function saveGenre()
    {
        $validatedData = $this->validate($this->rulesForSave());
    
        // If file is in temporary storage, move it to permanent storage
        if (strpos($this->objectName, 'tmp/genre') === 0) {
            $newPath = str_replace('tmp/genre', 'genre', $this->objectName);
            Storage::disk('public')->move($this->objectName, $newPath);
            $this->objectName = $newPath;
        }
    
        $genre = Genre::create([
            'priority' => $validatedData['priority'],
            'status'   => $validatedData['status'],
            'image'    => $this->objectName,
        ]);
    
        foreach ($this->filteredLocales as $locale) {
            GenreTranslater::create([
                'genre_id' => $genre->id,
                'locale'   => $locale,
                'name'     => $this->genres[$locale],
                'slug'     => Str::slug($this->genres[$locale], '-', ''),
            ]);
        }
    
        $this->resetInput();
        $this->closeModal();
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => __('New Genre Added Successfully')
        ]);
    }

    // Editing an existing genre
    public function editGenre($id)
    {
        $genre = Genre::with('genreTranslation')->findOrFail($id);
        $this->genreId = $genre->id;
        $this->priority = $genre->priority;
        $this->status = $genre->status;
        $this->objectName = $genre->image; // e.g., "genre/somefile.png"
        foreach ($this->filteredLocales as $locale) {
            $translation = $genre->genreTranslation()->where('locale', $locale)->first();
            $this->genres[$locale] = $translation ? $translation->name : '';
        }
        // Dispatch event to preload FilePond in update modal with the existing image.
        $fileUrl = asset('storage/' . $this->objectName);
        $this->dispatchBrowserEvent('setFilePondFile', ['file' => $fileUrl]);
    }
    
    // Updating an existing genre
    public function updateGenre()
    {
        $validatedData = $this->validate($this->rulesForUpdate());
    
        // If a new file is uploaded from tmp, move it to permanent storage
        if (strpos($this->objectName, 'tmp/genre') === 0) {
            $newPath = str_replace('tmp/genre', 'genre', $this->objectName);
            Storage::disk('public')->move($this->objectName, $newPath);
            $this->objectName = $newPath;
        }
    
        if ($this->genreId) {
            $genre = Genre::findOrFail($this->genreId);
            // If a new image was uploaded and it's different from the old image,
            // remove the old image from storage.
            if ($this->objectName && $this->objectName !== $genre->image) {
                if ($genre->image && Storage::disk('public')->exists($genre->image)) {
                    Storage::disk('public')->delete($genre->image);
                }
            }
    
            $genre->update([
                'priority' => $validatedData['priority'],
                'status'   => $validatedData['status'],
                'image'    => $this->objectName,
            ]);
    
            foreach ($this->filteredLocales as $locale) {
                $translation = $genre->genreTranslation()->where('locale', $locale)->first();
                if ($translation) {
                    $translation->update([
                        'name' => $this->genres[$locale],
                        'slug' => Str::slug($this->genres[$locale]),
                    ]);
                } else {
                    GenreTranslater::create([
                        'genre_id' => $genre->id,
                        'locale'   => $locale,
                        'name'     => $this->genres[$locale],
                        'slug'     => Str::slug($this->genres[$locale]),
                    ]);
                }
            }
    
            $this->resetInput();
            $this->closeModal();
            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => __('Genre updated successfully.')
            ]);
        }
    }

    public function confirmDeleteGenre($id)
    {
        $this->deleteGenreId = $id;
        $this->genreNameToDelete = '';
    }

    // Delete the genre record and its image if confirmation text matches.
    public function destroyGenre()
    {
        if ($this->genreNameToDelete !== 'DELETE GENRE') {
            return;
        }

        $genre = Genre::findOrFail($this->deleteGenreId);
        
        // Delete the image from storage if it exists.
        if ($genre->image && Storage::disk('public')->exists($genre->image)) {
            Storage::disk('public')->delete($genre->image);
        }
        
        $genre->delete();

        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => __('Genre deleted successfully.')
        ]);
        $this->resetInput();
        $this->closeModal();
        $this->closeDeleteModal();
    }

    public function updateStatus(int $id)
    {
        // Find the brand by ID, if not found return an error
        $dataStatus = Genre::find($id);
    
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
        $brand = Genre::find($p_id);
        
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
        // Delete temporary file for this user if exists.
        if ($this->objectName && strpos($this->objectName, 'tmp/genre') === 0) {
            Storage::disk('public')->delete($this->objectName);
        }
        $this->genreId = null;
        $this->status = 1;
        $this->objectName = null;
        $this->genres = [];
        $this->priority = Genre::max('priority') + 1;
        $this->deleteGenreId = null;
        $this->genreNameToDelete = '';
    }
    
    public function closeModal()
    {
        $this->dispatchBrowserEvent('close-modal');
        $this->resetInput();
    }
    
    public function render()
    {
        $this->activeCount = Genre::where('status', 1)->count();
        $this->nonActiveCount = Genre::where('status', 0)->count();
    
        $query = Genre::with(['genreTranslation' => function ($query) {
            $query->where('locale', app()->getLocale() ?? 'en');
        }]);
    
        if ($this->statusFilter === 'active') {
            $query->where('status', 1);
        } elseif ($this->statusFilter === 'non-active') {
            $query->where('status', 0);
        }
    
        if (!empty($this->search)) {
            $query->whereHas('genreTranslation', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            });
        }
    
        $tableData = $query->orderBy('priority', 'ASC')->paginate(10)->withQueryString();
    
        return view('admin.pages.genre.genre-table', [
            'tableData' => $tableData,
        ]);
    }
    
    public function fileUploaded($serverId)
    {
        // Remove extra quotes if present
        $cleaned = trim($serverId, '"');
    
        // If there is an existing temporary file and it's different, remove it.
        if ($this->objectName && $this->objectName !== $cleaned && strpos($this->objectName, 'tmp/genre') === 0) {
            Storage::disk('public')->delete($this->objectName);
        }
        
        $this->objectName = $cleaned;
    }
}
