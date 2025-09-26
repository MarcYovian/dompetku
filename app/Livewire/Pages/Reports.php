<?php

namespace App\Livewire\Pages;

use App\Models\FundSourceTransfer;
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
    public $selectedType = '';
    public $selectedFundSource = '';

    // 2. Ganti properti $transactions menjadi $activities
    public $activities;
    public $fundSources;
    public $expenseDistribution;
    public $totalIncome = 0;
    public $totalExpense = 0;
    public $netDifference = 0;

    protected $transactionService;
    protected $fundSourceService;

    // 3. Kita tidak lagi butuh CategoryService di sini
    public function boot(TransactionService $transactionService, FundSourceService $fundSourceService)
    {
        $this->transactionService = $transactionService;
        $this->fundSourceService = $fundSourceService;
    }

    public function mount()
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->endOfMonth()->format('Y-m-d');
        $this->fundSources = Auth::user()->fundSources;
        $this->generateReport();
    }

    public function generateReport()
    {
        $userId = Auth::id();
        $fundSourceId = $this->selectedFundSource === '' ? null : (int) $this->selectedFundSource;

        $transactions = $this->transactionService->getFilteredTransactions(
            $userId,
            $this->startDate,
            $this->endDate,
            $this->selectedType,
            $fundSourceId
        );

        $transfersQuery = FundSourceTransfer::where('user_id', $userId)
            ->with(['fromFundSource', 'toFundSource'])
            ->whereBetween('transfer_date', [$this->startDate, $this->endDate]);

        if ($fundSourceId) {
            $transfersQuery->where(function ($query) use ($fundSourceId) {
                $query->where('from_fund_source_id', $fundSourceId)
                    ->orWhere('to_fund_source_id', $fundSourceId);
            });
        }

        $transfers = $this->selectedType === '' ? $transfersQuery->get() : collect();

        $this->activities = $transactions->map(function ($item) {
            return (object) [
                'activity_date' => $item->transaction_date,
                'activity_type' => 'transaction',
                'sortable_timestamp' => $item->created_at,
                'data' => $item,
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
            ->sortByDesc('sortable_timestamp');


        $this->totalIncome = $transactions->where('type', 'income')->sum('amount');
        $this->totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $this->netDifference = $this->totalIncome - $this->totalExpense;

        $this->expenseDistribution = $this->transactionService->getFilteredExpenseDistributionByCategory(
            $userId,
            $this->startDate,
            $this->endDate,
            $fundSourceId
        );
    }

    public function render()
    {
        return view('livewire.pages.reports');
    }
}
