<?php

namespace App\Livewire\Traits;

use App\Services\FundSourceTransferService;
use App\Services\TransactionService;

trait WithDeleteConfirmation
{
    public bool $showDeleteModal = false;
    public ?int $itemToDeleteId = null;
    public ?string $itemToDeleteType = null;

    /**
     * Metode ini harus diimplementasikan oleh setiap komponen
     * yang menggunakan trait ini. Tujuannya adalah untuk
     * mendefinisikan cara me-refresh data di komponen tersebut.
     */
    abstract protected function refreshData();

    public function confirmDeletion(int $id, string $type)
    {
        $this->itemToDeleteId = $id;
        $this->itemToDeleteType = $type;
        $this->showDeleteModal = true;
    }

    public function deleteItem(TransactionService $transactionService, FundSourceTransferService $fundSourceTransferService)
    {
        if ($this->itemToDeleteType === 'transaction') {
            $transactionService->deleteTransaction($this->itemToDeleteId);
        } elseif ($this->itemToDeleteType === 'transfer') {
            $fundSourceTransferService->deleteTransfer($this->itemToDeleteId);
        }

        $this->showDeleteModal = false;

        $this->refreshData();

        session()->flash('success', 'Data berhasil dihapus.');
    }
}
