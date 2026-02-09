<?php

namespace App\Livewire\Dashboard\Category;

use App\Models\Category;
use App\Traits\WithCustomPagination;
use Exception;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithCustomPagination,WithPagination;

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

    public $categoryId;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'status' => 'required|in:active,inactive',
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
        $this->categoryId = null;
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

        Category::create([
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
        ]);

        $this->resetInput();
        $this->showModal = false;
        session()->flash('message', 'Category created successfully.');
    }

    public function edit($id)
    {
        try {
            $category = Category::findOrFail($id);
            $this->categoryId = $category->id;
            $this->name = $category->name;
            $this->description = $category->description;
            $this->status = $category->status;
            $this->showModal = true;
            Log::info("Edit method called for Category ID: $id, showModal set to true");
        } catch (Exception $e) {
            Log::error('Error in edit method: '.$e->getMessage());
            session()->flash('error', 'Failed to load category for editing.');
        }
    }

    public function update()
    {
        $this->validate();

        $category = Category::findOrFail($this->categoryId);
        $category->update([
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
        ]);

        $this->resetInput();
        $this->showModal = false;
        session()->flash('message', 'Category updated successfully.');
    }

    public function delete($id)
    {
        Category::findOrFail($id)->delete();
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
        $query = Category::query();

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

        $categories = $query->paginate($this->perPage);

        return view('livewire.dashboard.category.index', [
            'categories' => $categories,
            'pageRange' => $this->getPageRange($categories),
        ])->layout('layouts.app');
    }
}
