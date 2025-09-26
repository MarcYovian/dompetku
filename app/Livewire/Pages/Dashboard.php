<?php

namespace App\Livewire\Pages;

use App\Models\FundSourceTransfer;
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

    public $latestActivities;

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

        $fundSources = $this->fundSourceService->getAllFundSources()->where('user_id', $userId);
        $this->totalBalance = $fundSources->sum('balance');

        $monthlyData = $this->transactionService->getMonthlyIncomeExpense($userId, $currentYear, $currentMonth);
        $this->monthlyIncome = $monthlyData['income'];
        $this->monthlyExpense = $monthlyData['expense'];

        $this->expenseDistribution = $this->transactionService->getExpenseDistributionByCategory($userId, $currentYear, $currentMonth);

        $transactions = $this->transactionService->getLatestTransactions($userId, 5);

        $transfers = FundSourceTransfer::where('user_id', $userId)
            ->with(['fromFundSource', 'toFundSource'])
            ->latest('transfer_date')
            ->latest('id')
            ->take(5)
            ->get();

        $this->latestActivities = $transactions->map(function ($item) {
            return (object) [
                'activity_date' => $item->transaction_date,
                'activity_type' => 'transaction',
                'sortable_timestamp' => $item->created_at,
                'data' => $item, // simpan model aslinya kalau masih butuh
            ];
        })->concat($transfers->map(function ($item) {
            return (object) [
                'activity_date' => $item->transfer_date,
                'activity_type' => 'transfer',
                'sortable_timestamp' => $item->created_at,
                'data' => $item,
            ];
        }))
            ->sortByDesc('activity_date')
            ->sortByDesc('sortable_timestamp')
            ->take(5);
    }

    public function render()
    {
        return view('livewire.pages.dashboard');
    }
}
