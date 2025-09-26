<?php

namespace App\Services;

use App\Models\Transaction;
use App\Repositories\FundSourceRepository;
use App\Repositories\TransactionRepository;
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

    public function deleteTransaction(int $id)
    {
        // Similar complexity as update, needs to reverse the effect on fund source
        return $this->transactionRepository->delete($id);
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
}
