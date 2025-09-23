<?php

namespace App\Livewire\Pages;

use App\Services\CategoryService;
use App\Services\FundSourceService;
use App\Services\TransactionService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Reports extends Component
{
    #[Layout('layouts.app')]
    public $startDate;
    public $endDate;
    public $selectedType = ''; // income, expense, or empty for all
    public $selectedFundSource = ''; // fund_source_id or empty for all

    public $transactions;
    public $categories;
    public $fundSources;
    public $expenseDistribution;
    public $totalIncome = 0;
    public $totalExpense = 0;
    public $netDifference = 0;

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
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->endOfMonth()->format('Y-m-d');
        $this->categories = Auth::user()->categories;
        $this->fundSources = Auth::user()->fundSources;
        $this->generateReport();
    }

    public function generateReport()
    {
        $userId = Auth::id();

        $fundSourceId = $this->selectedFundSource === '' ? null : (int) $this->selectedFundSource;

        // Fetch transactions using TransactionService
        $this->transactions = $this->transactionService->getFilteredTransactions(
            $userId,
            $this->startDate,
            $this->endDate,
            $this->selectedType,
            $fundSourceId
        );

        // Fetch expense distribution using TransactionService
        $this->expenseDistribution = $this->transactionService->getFilteredExpenseDistributionByCategory(
            $userId,
            $this->startDate,
            $this->endDate,
            $fundSourceId
        );

        $this->totalIncome = $this->transactions->where('type', 'income')->sum('amount');
        $this->totalExpense = $this->transactions->where('type', 'expense')->sum('amount');
        $this->netDifference = $this->totalIncome - $this->totalExpense;
    }
    public function render()
    {
        return view('livewire.pages.reports');
    }
}
