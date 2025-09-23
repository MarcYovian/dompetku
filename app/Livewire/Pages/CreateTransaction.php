<?php

namespace App\Livewire\Pages;

use App\Services\CategoryService;
use App\Services\FundSourceService;
use App\Services\TransactionService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class CreateTransaction extends Component
{
    #[Layout('layouts.app')]
    public $amount;
    public $type = 'expense';
    public $description;
    public $transaction_date;
    public $category_id;
    public $fund_source_id;

    public $categories;
    public $filteredCategories;
    public $fundSources;

    protected $rules = [
        'amount' => 'required|numeric|min:0.01',
        'type' => 'required|in:income,expense',
        'description' => 'nullable|string|max:255',
        'transaction_date' => 'required|date',
        'category_id' => 'required|exists:categories,id',
        'fund_source_id' => 'required|exists:fund_sources,id',
    ];

    protected $transactionService;
    protected $categoryService;
    protected $fundSourceService;

    public function boot(TransactionService $transactionService, CategoryService $categoryService, FundSourceService $fundSourceService)
    {
        $this->transactionService = $transactionService;
        $this->categoryService = $categoryService;
        $this->fundSourceService = $fundSourceService;
    }

    public function mount()
    {
        $this->transaction_date = now()->format('Y-m-d');
        $this->loadCategoriesAndFundSources();
        $this->filterCategories();
    }

    public function updatedType()
    {
        $this->filterCategories();
    }

    public function loadCategoriesAndFundSources()
    {
        $this->categories = Auth::user()->categories;
        $this->fundSources = Auth::user()->fundSources;

        // Set default if available
        if ($this->fundSources->isNotEmpty()) {
            $this->fund_source_id = $this->fundSources->first()->id;
        }
    }

    public function filterCategories()
    {
        $this->filteredCategories = $this->categories->where('type', $this->type);

        // If the currently selected category is not in the filtered list, reset it
        if ($this->filteredCategories->isEmpty()) {
            $this->category_id = null;
        } elseif (!$this->filteredCategories->contains('id', $this->category_id)) {
            $this->category_id = $this->filteredCategories->first()->id;
        }
    }

    public function saveTransaction()
    {
        $this->validate();

        try {
            $this->transactionService->createTransaction([
                'user_id' => Auth::id(),
                'amount' => $this->amount,
                'type' => $this->type,
                'description' => $this->description,
                'transaction_date' => $this->transaction_date,
                'category_id' => $this->category_id,
                'fund_source_id' => $this->fund_source_id,
            ]);

            $this->reset(['amount', 'description']);
            $this->transaction_date = now()->format('Y-m-d');
            $this->loadCategoriesAndFundSources(); // Reload fund sources to reflect balance change
            $this->filterCategories(); // Re-filter categories after reset
            session()->flash('message', 'Transaction recorded successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error recording transaction: ' . $e->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.pages.create-transaction');
    }
}
