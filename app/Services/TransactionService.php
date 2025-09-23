<?php

namespace App\Services;

use App\Repositories\TransactionRepository;
use App\Repositories\FundSourceRepository;
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

    public function updateTransaction(int $id, array $data)
    {
        // This is more complex as it involves reversing the old transaction's effect on fund source
        // and then applying the new one. For now, I'll keep it simple.
        return $this->transactionRepository->update($id, $data);
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
