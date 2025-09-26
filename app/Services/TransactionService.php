<?php

namespace App\Services;

use App\Models\Transaction;
use App\Repositories\FundSourceRepository;
use App\Repositories\TransactionRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TransactionService extends BaseService
{
    protected $transactionRepository;
    protected $fundSourceRepository;

    public function __construct(TransactionRepository $transactionRepository, FundSourceRepository $fundSourceRepository)
    {
        $this->transactionRepository = $transactionRepository;
        $this->fundSourceRepository = $fundSourceRepository;
    }

    public function getAllTransactions()
    {
        return $this->transactionRepository->all();
    }

    public function createTransaction(array $data)
    {
        DB::beginTransaction();
        try {
            $transaction = $this->transactionRepository->create($data);

            $fundSource = $this->fundSourceRepository->find($data['fund_source_id']);
            if ($fundSource) {
                if ($data['type'] === 'income') {
                    $fundSource->balance += $data['amount'];
                } else {
                    $fundSource->balance -= $data['amount'];
                }
                $fundSource->save();
            }

            DB::commit();
            return $transaction;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateTransaction(Transaction $transaction, array $data)
    {
        return DB::transaction(function () use ($transaction, $data) {
            $originalFundSource = \App\Models\FundSource::find($data['originalFundSourceId']);
            $newFundSource = \App\Models\FundSource::find($data['fund_source_id']);

            // 1. Kembalikan saldo dari transaksi lama
            if ($data['originalType'] === 'income') {
                $originalFundSource->decrement('balance', $data['originalAmount']);
            } else {
                $originalFundSource->increment('balance', $data['originalAmount']);
            }

            // 2. Update data transaksi
            $transaction->update([
                'type' => $data['type'],
                'amount' => $data['amount'],
                'fund_source_id' => $data['fund_source_id'],
                'category_id' => $data['category_id'],
                'description' => $data['description'],
                'transaction_date' => $data['transaction_date'],
            ]);

            // 3. Terapkan saldo pada transaksi baru
            if ($data['type'] === 'income') {
                $newFundSource->increment('balance', $data['amount']);
            } else {
                $newFundSource->decrement('balance', $data['amount']);
            }

            return $transaction;
        });
    }

    public function deleteTransaction(int $transactionId)
    {
        return DB::transaction(function () use ($transactionId) {
            $transaction = $this->transactionRepository->find($transactionId);

            if (!$transaction) {
                return;
            }

            if ($transaction->fund_source_transfer_id) {
                // JIKA YA: Jangan hapus langsung. Panggil service yang tepat untuk
                // menangani pembatalan biaya.
                $fundSourceTransferService = app(FundSourceTransferService::class);
                $fundSourceTransferService->removeFeeFromTransfer($transaction->fund_source_transfer_id);
            } else {
                // JIKA TIDAK: Lanjutkan dengan logika penghapusan transaksi biasa.
                DB::transaction(function () use ($transaction) {
                    $fundSource = $transaction->fundSource;

                    // 1. Kembalikan saldo
                    if ($transaction->type === 'income') {
                        $fundSource->decrement('balance', $transaction->amount);
                    } else {
                        $fundSource->increment('balance', $transaction->amount);
                    }

                    // 2. Hapus transaksi
                    $transaction->delete();
                });
            }
        });
    }

    public function getTransactionById(int $id)
    {
        return $this->transactionRepository->find($id);
    }

    public function getLatestTransactions(int $userId, int $limit = 10)
    {
        return $this->transactionRepository->getLatestTransactionsForUser($userId, $limit);
    }

    public function getMonthlyIncomeExpense(int $userId, int $year, int $month)
    {
        return $this->transactionRepository->getMonthlyIncomeExpenseForUser($userId, $year, $month);
    }

    public function getExpenseDistributionByCategory(int $userId, int $year, int $month)
    {
        return $this->transactionRepository->getExpenseDistributionByCategoryForUser($userId, $year, $month);
    }

    public function getFilteredTransactions(int $userId, string $startDate, string $endDate, ?string $type, ?int $fundSourceId)
    {
        return $this->transactionRepository->getFilteredTransactionsForUser($userId, $startDate, $endDate, $type, $fundSourceId);
    }

    public function getFilteredExpenseDistributionByCategory(int $userId, string $startDate, string $endDate, ?int $fundSourceId)
    {
        return $this->transactionRepository->getFilteredExpenseDistributionByCategoryForUser($userId, $startDate, $endDate, $fundSourceId);
    }

    public function getMonthlyFinancialTrend(int $userId, int $numberOfMonths = 6): array
    {
        $endDate = Carbon::now()->endOfMonth();
        $startDate = Carbon::now()->subMonths($numberOfMonths - 1)->startOfMonth();

        $transactions = DB::table('transactions')
            ->select(
                DB::raw('YEAR(transaction_date) as year'),
                DB::raw('MONTH(transaction_date) as month'),
                DB::raw("SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as total_income"),
                DB::raw("SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as total_expense")
            )
            ->where('user_id', $userId)
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // Siapkan array untuk menampung hasil
        $labels = [];
        $incomeData = [];
        $expenseData = [];
        $date = $startDate->clone();

        // Inisialisasi data untuk setiap bulan dalam rentang
        $monthlyData = [];
        while ($date <= $endDate) {
            $key = $date->format('Y-n');
            $monthlyData[$key] = [
                'income' => 0,
                'expense' => 0,
            ];
            $date->addMonth();
        }

        // Isi data dari hasil query
        foreach ($transactions as $transaction) {
            $key = $transaction->year . '-' . $transaction->month;
            if (isset($monthlyData[$key])) {
                $monthlyData[$key]['income'] = $transaction->total_income;
                $monthlyData[$key]['expense'] = $transaction->total_expense;
            }
        }

        // Format data untuk digunakan oleh chart
        foreach ($monthlyData as $key => $values) {
            $labels[] = Carbon::createFromFormat('Y-n', $key)->format('M Y');
            $incomeData[] = $values['income'];
            $expenseData[] = $values['expense'];
        }

        return [
            'labels' => $labels,
            'income' => $incomeData,
            'expense' => $expenseData,
        ];
    }
}
