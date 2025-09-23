<?php

namespace App\Livewire\Pages;

use App\Services\FundSourceService;
use App\Services\TransactionService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Dashboard extends Component
{
    #[Layout('layouts.app')]
    public $totalBalance = 0;
    public $monthlyIncome = 0;
    public $monthlyExpense = 0;
    public $latestTransactions;
    public $expenseDistribution;

    protected $fundSourceService;
    protected $transactionService;

    public function boot(FundSourceService $fundSourceService, TransactionService $transactionService)
    {
        $this->fundSourceService = $fundSourceService;
        $this->transactionService = $transactionService;
    }

    public function mount()
    {
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        $userId = Auth::id();
        $currentMonth = now()->month;
        $currentYear = now()->year;

        // Total Balance
        $fundSources = $this->fundSourceService->getAllFundSources()->where('user_id', $userId);
        $this->totalBalance = $fundSources->sum('balance');

        // Monthly Income and Expense
        $monthlyData = $this->transactionService->getMonthlyIncomeExpense($userId, $currentYear, $currentMonth);
        $this->monthlyIncome = $monthlyData['income'];
        $this->monthlyExpense = $monthlyData['expense'];

        // Latest Transactions
        $this->latestTransactions = $this->transactionService->getLatestTransactions($userId, 5);

        // Expense Distribution by Category
        $this->expenseDistribution = $this->transactionService->getExpenseDistributionByCategory($userId, $currentYear, $currentMonth);
    }

    public function render()
    {
        return view('livewire.pages.dashboard');
    }
}
