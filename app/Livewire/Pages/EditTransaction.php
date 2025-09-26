<?php

namespace App\Livewire\Pages;

use App\Models\Transaction;
use App\Services\CategoryService;
use App\Services\FundSourceService;
use App\Services\TransactionService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class EditTransaction extends Component
{
    #[Layout('layouts.app')]
    public Transaction $transaction;

    // Properti untuk form
    public $type;
    public $amount;
    public $fund_source_id;
    public $category_id;
    public $description;
    public $transaction_date;

    // Data untuk dropdown
    public $filteredCategories = [];
    public $fundSources = [];

    // Untuk menyimpan data asli
    public $originalAmount;
    public $originalFundSourceId;
    public $originalType;

    public function mount(Transaction $transaction, FundSourceService $fundSourceService)
    {
        // Pastikan pengguna hanya bisa mengedit transaksinya sendiri
        abort_if($transaction->user_id !== Auth::id(), 403);

        $this->transaction = $transaction;

        // Isi properti form dari data transaksi yang ada
        $this->type = $transaction->type;
        $this->amount = $transaction->amount;
        $this->fund_source_id = $transaction->fund_source_id;
        $this->category_id = $transaction->category_id;
        $this->description = $transaction->description;
        $this->transaction_date = $transaction->transaction_date->format('Y-m-d');

        // Simpan data asli untuk kalkulasi ulang saldo
        $this->originalAmount = $transaction->amount;
        $this->originalFundSourceId = $transaction->fund_source_id;
        $this->originalType = $transaction->type;

        // Load data untuk dropdown
        $this->fundSources = $fundSourceService->getAllFundSources();
        $this->loadCategories();
    }

    public function loadCategories()
    {
        $categoryService = app(CategoryService::class);
        $this->filteredCategories = $categoryService->getGroupedCategoriesByType($this->type);
    }

    public function updatedType()
    {
        $this->loadCategories();
        $this->category_id = null;
    }

    public function updateTransaction(TransactionService $transactionService)
    {
        $validated = $this->validate([
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0.01',
            'fund_source_id' => 'required|exists:fund_sources,id',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string|max:255',
            'transaction_date' => 'required|date',
        ]);

        $data = array_merge($validated, [
            'originalAmount' => $this->originalAmount,
            'originalFundSourceId' => $this->originalFundSourceId,
            'originalType' => $this->originalType,
        ]);

        try {
            $transactionService->updateTransaction($this->transaction, $data);
            session()->flash('success', 'Transaksi berhasil diperbarui.');
            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memperbarui transaksi: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.pages.edit-transaction');
    }
}
