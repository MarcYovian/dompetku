<?php

namespace App\Livewire\Pages;

use App\Services\CategoryService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Categories extends Component
{
    #[Layout('layouts.app')]
    public $categories;
    public $name;
    public $type = 'expense'; // Default to expense
    public $editingCategoryId = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'type' => 'required|in:income,expense',
    ];

    protected $categoryService;

    public function boot(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function mount()
    {
        $this->loadCategories();
    }

    public function loadCategories()
    {
        $this->categories = Auth::user()->categories;
    }

    public function saveCategory()
    {
        $this->validate();

        if ($this->editingCategoryId) {
            $this->categoryService->updateCategory($this->editingCategoryId, [
                'name' => $this->name,
                'type' => $this->type,
            ]);
            $this->editingCategoryId = null;
        } else {
            $this->categoryService->createCategory([
                'user_id' => Auth::id(),
                'name' => $this->name,
                'type' => $this->type,
            ]);
        }

        $this->reset(['name', 'type']);
        $this->type = 'expense'; // Reset to default
        $this->loadCategories();
        session()->flash('message', 'Category saved successfully.');
    }

    public function resetForm()
    {
        $this->reset(['name', 'type']);
        $this->type = 'expense';
        $this->editingCategoryId = null;
    }

    public function editCategory($id)
    {
        $category = $this->categoryService->getCategoryById($id);
        $this->editingCategoryId = $category->id;
        $this->name = $category->name;
        $this->type = $category->type;
    }

    public function deleteCategory($id)
    {
        $this->categoryService->deleteCategory($id);
        $this->loadCategories();
        session()->flash('message', 'Category deleted successfully.');
    }
    public function render()
    {
        return view('livewire.pages.categories');
    }
}
