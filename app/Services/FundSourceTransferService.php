<?php

namespace App\Services;

use App\Models\FundSource;
use App\Repositories\FundSourceTransferRepository;
use App\Repositories\TransactionRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FundSourceTransferService extends BaseService
{
    protected $repository;
    protected $transactionRepository;

    public function __construct(
        FundSourceTransferRepository $repository,
        TransactionRepository $transactionRepository
    ) {
        $this->repository = $repository;
        $this->transactionRepository = $transactionRepository;
    }

    public function createTransfer(array $data)
    {
        return DB::transaction(function () use ($data) {
            $fromFundSource = FundSource::find($data['from_fund_source_id']);
            $toFundSource = FundSource::find($data['to_fund_source_id']);
            $amount = $data['amount'];
            $fee = $data['fee'] ?? 0;

            // Kurangi saldo sumber
            $fromFundSource->decrement('balance', $amount + $fee);

            // Tambah saldo tujuan
            $toFundSource->increment('balance', $amount);

            // Catat transfer
            $transfer = $this->repository->create([
                'user_id' => Auth::id(),
                'from_fund_source_id' => $fromFundSource->id,
                'to_fund_source_id' => $toFundSource->id,
                'amount' => $amount,
                'fee' => $fee,
                'description' => $data['description'],
                'transfer_date' => $data['transfer_date'],
            ]);

            // Jika ada biaya, catat sebagai 'expense'
            if ($fee > 0) {
                $this->transactionRepository->create([
                    'user_id' => Auth::id(),
                    'fund_source_id' => $fromFundSource->id,
                    // Asumsi ada kategori 'Biaya Transfer' atau sejenisnya
                    // Anda bisa membuatnya dinamis atau hardcode ID-nya
                    'category_id' => \App\Models\Category::firstOrCreate(
                        ['name' => 'Biaya Transfer', 'type' => 'expense', 'user_id' => Auth::id()]
                    )->id,
                    'type' => 'expense',
                    'amount' => $fee,
                    'description' => 'Biaya transfer ke ' . $toFundSource->name,
                    'transaction_date' => $data['transfer_date'],
                ]);
            }

            return $transfer;
        });
    }
}
