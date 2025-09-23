<?php

namespace App\Repositories;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class TransactionRepository extends BaseRepository
{
    /**
     * TransactionRepository constructor.
     *
     * @param Transaction $model
     */
    public function __construct(Transaction $model)
    {
        parent::__construct($model);
    }

    /**
     * Get the latest transactions for a specific user.
     *
     * @param int $userId
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getLatestTransactionsForUser(int $userId, int $limit = 10)
    {
        return $this->model->where('user_id', $userId)
                           ->orderBy('transaction_date', 'desc')
                           ->orderBy('created_at', 'desc')
                           ->limit($limit)
                           ->get();
    }

    /**
     * Get monthly income and expense for a specific user.
     *
     * @param int $userId
     * @param int $year
     * @param int $month
     * @return array
     */
    public function getMonthlyIncomeExpenseForUser(int $userId, int $year, int $month)
    {
        $income = $this->model
            ->where('user_id', $userId)
            ->whereYear('transaction_date', $year)
            ->whereMonth('transaction_date', $month)
            ->where('type', 'income')
            ->sum('amount');

        $expense = $this->model
            ->where('user_id', $userId)
            ->whereYear('transaction_date', $year)
            ->whereMonth('transaction_date', $month)
            ->where('type', 'expense')
            ->sum('amount');

        return [
            'income' => $income,
            'expense' => $expense,
        ];
    }

    /**
     * Get expense distribution by category for a specific user.
     *
     * @param int $userId
     * @param int $year
     * @param int $month
     * @return \Illuminate\Support\Collection
     */
    public function getExpenseDistributionByCategoryForUser(int $userId, int $year, int $month)
    {
        return $this->model
            ->select(DB::raw('categories.name as category_name'), DB::raw('SUM(transactions.amount) as total_amount'))
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->where('transactions.user_id', $userId)
            ->whereYear('transactions.transaction_date', $year)
            ->whereMonth('transactions.transaction_date', $month)
            ->where('transactions.type', 'expense')
            ->groupBy('categories.name')
            ->get();
    }

    /**
     * Get filtered transactions for a specific user.
     *
     * @param int $userId
     * @param string $startDate
     * @param string $endDate
     * @param string|null $type
     * @param int|null $fundSourceId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getFilteredTransactionsForUser(int $userId, string $startDate, string $endDate, ?string $type, ?int $fundSourceId)
    {
        $query = $this->model->where('user_id', $userId)
                           ->whereBetween('transaction_date', [$startDate, $endDate]);

        if ($type) {
            $query->where('type', $type);
        }

        if ($fundSourceId) {
            $query->where('fund_source_id', $fundSourceId);
        }

        return $query->orderBy('transaction_date', 'desc')->get();
    }

    /**
     * Get filtered expense distribution by category for a specific user.
     *
     * @param int $userId
     * @param string $startDate
     * @param string $endDate
     * @param int|null $fundSourceId
     * @return \Illuminate\Support\Collection
     */
    public function getFilteredExpenseDistributionByCategoryForUser(int $userId, string $startDate, string $endDate, ?int $fundSourceId)
    {
        $query = $this->model
            ->select(DB::raw('categories.name as category_name'), DB::raw('SUM(transactions.amount) as total_amount'))
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->where('transactions.user_id', $userId)
            ->whereBetween('transactions.transaction_date', [$startDate, $endDate])
            ->where('transactions.type', 'expense');

        if ($fundSourceId) {
            $query->where('transactions.fund_source_id', $fundSourceId);
        }

        return $query->groupBy('categories.name')->get();
    }
}
