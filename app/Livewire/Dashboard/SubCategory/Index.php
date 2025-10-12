<?php

namespace App\Livewire\Dashboard\SubCategory;

use App\Models\Category;
use App\Models\SubCategory;
use App\Traits\WithCustomPagination;
use Exception;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithCustomPagination, WithPagination;

    public $search = '';

    public $perPage = 10;

    public $sortBy = 'latest';

    public $filterStatus = '';

    public $filterDate = '';

    public $showFilters = false;

    public $showModal = false;

    public $name;

    public $description;

    public $status = 'active';

    public $subCategoryId;

    public $category_id;

    protected $listeners = ['subCategorySaved' => '$refresh'];

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'status' => 'required|in:active,inactive',
        'category_id' => 'required|exists:categories,id',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function resetInput()
    {
        $this->name = '';
        $this->description = '';
        $this->status = 'active';
        $this->category_id = null;
        $this->subCategoryId = null;
        $this->resetValidation();
    }

    public function openModal($id = null)
    {
        $this->resetInput();
        if ($id) {
            $this->edit($id);
        } else {
            $this->showModal = true;
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetInput();
    }

    public function store()
    {
        $this->validate();

        SubCategory::create([
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'category_id' => $this->category_id,
        ]);

        $this->resetInput();
        $this->showModal = false;
        session()->flash('message', 'Category created successfully.');
    }

    public function edit($id)
    {
        try {
            $subCategory = SubCategory::findOrFail($id);
            $this->subCategoryId = $subCategory->id;
            $this->name = $subCategory->name;
            $this->description = $subCategory->description;
            $this->status = $subCategory->status;
            $this->category_id = $subCategory->category_id;
            $this->showModal = true;
            Log::info("Edit method called for SubCategory ID: $id, showModal set to true");
        } catch (Exception $e) {
            Log::error('Error in edit method: '.$e->getMessage());
            session()->flash('error', 'Failed to load subcategory for editing.');
        }
    }

    public function update()
    {
        $this->validate();

        $subCategory = SubCategory::findOrFail($this->subCategoryId);
        $subCategory->update([
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'category_id' => $this->category_id,
        ]);

        $this->resetInput();
        $this->showModal = false;
        session()->flash('message', 'Category updated successfully.');
    }

    public function delete($id)
    {
        SubCategory::findOrFail($id)->delete();
        session()->flash('message', 'Category deleted successfully.');
    }

    public function clearFilters()
    {
        $this->filterStatus = '';
        $this->filterDate = '';
        $this->showFilters = false;
    }

    public function render()
    {
        $query = SubCategory::query();

        if ($this->search) {
            $query->where('name', 'like', "%{$this->search}%")
                ->orWhere('description', 'like', "%{$this->search}%");
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterDate) {
            $query->whereDate('created_at', $this->filterDate);
        }

        switch ($this->sortBy) {
            case 'latest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
        }

        $subCategories = $query->paginate($this->perPage);
        $categories = Category::all();

        return view('livewire.dashboard.sub-category.index', [
            'subCategories' => $subCategories,
            'categories' => $categories,
            'pageRange' => $this->getPageRange($subCategories),
        ])->layout('layouts.app');
    }
}
