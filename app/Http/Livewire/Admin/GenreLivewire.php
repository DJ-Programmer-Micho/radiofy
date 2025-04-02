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
    public $objectName;   // Landscape image file path
    public $objectNameSq; // Square image file path

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

    // Listen for FilePond upload events for both images
    protected $listeners = ['fileUploaded', 'fileUploadedSq'];

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

    protected function rulesForSave()
    {
        $rules = [];
        foreach ($this->filteredLocales as $locale) {
            $rules['genres.' . $locale] = [
                'required',
                'string',
                'min:1',
                Rule::unique('genre_translations', 'name')->where('locale', $locale),
                // Ensure uniqueness across locales in the form
                function ($attribute, $value, $fail) use ($locale) {
                    $occurrences = collect($this->genres)->filter(function ($v) use ($value) {
                        return $v === $value;
                    })->count();
                    if ($occurrences > 1) {
                        $fail("The genre name '$value' must be unique across locales.");
                    }
                }
            ];
        }
        $rules['priority'] = ['required', 'integer'];
        $rules['status'] = ['required', 'in:0,1'];
        $rules['objectName'] = ['required', 'string'];
        $rules['objectNameSq'] = ['required', 'string'];
        return $rules;
    }

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
                    ->ignore($this->genreId, 'genre_id'),
                // Custom rule for uniqueness among the form inputs
                function ($attribute, $value, $fail) use ($locale) {
                    $occurrences = collect($this->genres)->filter(function ($v) use ($value) {
                        return $v === $value;
                    })->count();
                    if ($occurrences > 1) {
                        $fail("The genre name '$value' must be unique across locales.");
                    }
                }
            ];
        }
        $rules['priority'] = ['required', 'integer'];
        $rules['status'] = ['required', 'in:0,1'];
        $rules['objectName'] = ['required', 'string'];
        $rules['objectNameSq'] = ['required', 'string'];
        return $rules;
    }

    public function updated($propertyName)
    {
        $rules = $this->genreId ? $this->rulesForUpdate() : $this->rulesForSave();
        $this->validateOnly($propertyName, $rules);
    }

    // Saving a new genre
    public function saveGenre()
    {
        try {
            $validatedData = $this->validate($this->rulesForSave());

            // Move landscape image from temporary to permanent storage
            if (strpos($this->objectName, 'tmp/genre') === 0) {
                $newPath = str_replace('tmp/genre', 'genre', $this->objectName);
                Storage::disk('public')->move($this->objectName, $newPath);
                $this->objectName = $newPath;
            }
    
            // Move square image from temporary to permanent storage
            if (strpos($this->objectNameSq, 'tmp/genre') === 0) {
                $newPathSq = str_replace('tmp/genre', 'genre', $this->objectNameSq);
                Storage::disk('public')->move($this->objectNameSq, $newPathSq);
                $this->objectNameSq = $newPathSq;
            }
    
            $genre = Genre::create([
                'priority' => $validatedData['priority'],
                'status'   => $validatedData['status'],
                'image'    => $this->objectName,
                'image_sq' => $this->objectNameSq,
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
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' => __('Err: '. $e)
            ]);
        }
    }

    // Editing an existing genre
    public function editGenre($id)
    {
        $genre = Genre::with('genreTranslation')->findOrFail($id);
        $this->genreId = $genre->id;
        $this->priority = $genre->priority;
        $this->status = $genre->status;
        $this->objectName = $genre->image;
        $this->objectNameSq = $genre->image_sq;
        foreach ($this->filteredLocales as $locale) {
            $translation = $genre->genreTranslation()->where('locale', $locale)->first();
            $this->genres[$locale] = $translation ? $translation->name : '';
        }
        // Dispatch event to preload FilePond in update modal with the existing landscape image.
        $fileUrl = asset('storage/' . $this->objectName);
        $this->dispatchBrowserEvent('setFilePondFile', ['file' => $fileUrl]);

        // Dispatch event to preload FilePond in update modal with the existing square image.
        $fileUrlSq = asset('storage/' . $this->objectNameSq);
        $this->dispatchBrowserEvent('setFilePondFileSq', ['file' => $fileUrlSq]);
    }

    // Updating an existing genre
    public function updateGenre()
    {
        $validatedData = $this->validate($this->rulesForUpdate());

        // Update landscape image if a new file is uploaded
        if (strpos($this->objectName, 'tmp/genre') === 0) {
            $newPath = str_replace('tmp/genre', 'genre', $this->objectName);
            Storage::disk('public')->move($this->objectName, $newPath);
            if ($this->genreId) {
                $genre = Genre::findOrFail($this->genreId);
                if ($genre->image && $genre->image !== $newPath && Storage::disk('public')->exists($genre->image)) {
                    Storage::disk('public')->delete($genre->image);
                }
            }
            $this->objectName = $newPath;
        }

        // Update square image if a new file is uploaded
        if (strpos($this->objectNameSq, 'tmp/genre') === 0) {
            $newPathSq = str_replace('tmp/genre', 'genre', $this->objectNameSq);
            Storage::disk('public')->move($this->objectNameSq, $newPathSq);
            if ($this->genreId) {
                $genre = Genre::findOrFail($this->genreId);
                if ($genre->image_sq && $genre->image_sq !== $newPathSq && Storage::disk('public')->exists($genre->image_sq)) {
                    Storage::disk('public')->delete($genre->image_sq);
                }
            }
            $this->objectNameSq = $newPathSq;
        }

        if ($this->genreId) {
            $genre = Genre::findOrFail($this->genreId);
            $genre->update([
                'priority' => $validatedData['priority'],
                'status'   => $validatedData['status'],
                'image'    => $this->objectName,
                'image_sq' => $this->objectNameSq,
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

    // Delete the genre and remove its images
    public function destroyGenre()
    {
        if ($this->genreNameToDelete !== 'DELETE GENRE') {
            return;
        }

        $genre = Genre::findOrFail($this->deleteGenreId);
        if ($genre->image && Storage::disk('public')->exists($genre->image)) {
            Storage::disk('public')->delete($genre->image);
        }
        if ($genre->image_sq && Storage::disk('public')->exists($genre->image_sq)) {
            Storage::disk('public')->delete($genre->image_sq);
        }
        
        $genre->delete();

        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => __('Genre deleted successfully.')
        ]);
        $this->resetInput();
        $this->closeModal();
    }

    public function updateStatus(int $id)
    {
        $dataStatus = Genre::find($id);

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
        // Delete any temporary files if they exist.
        if ($this->objectName && strpos($this->objectName, 'tmp/genre') === 0) {
            Storage::disk('public')->delete($this->objectName);
        }
        if ($this->objectNameSq && strpos($this->objectNameSq, 'tmp/genre') === 0) {
            Storage::disk('public')->delete($this->objectNameSq);
        }
        $this->genreId = null;
        $this->status = 1;
        $this->objectName = null;
        $this->objectNameSq = null;
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
        $cleaned = trim($serverId, '"');
        if ($this->objectName && $this->objectName !== $cleaned && strpos($this->objectName, 'tmp/genre') === 0) {
            Storage::disk('public')->delete($this->objectName);
        }
        $this->objectName = $cleaned;
    }

    public function fileUploadedSq($serverId)
    {
        $cleaned = trim($serverId, '"');
        if ($this->objectNameSq && $this->objectNameSq !== $cleaned && strpos($this->objectNameSq, 'tmp/genre') === 0) {
            Storage::disk('public')->delete($this->objectNameSq);
        }
        $this->objectNameSq = $cleaned;
    }
    
}
