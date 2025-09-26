<?php

namespace App\Livewire\Pages;

use App\Models\FundSourceTransfer;
use App\Services\FundSourceService;
use App\Services\FundSourceTransferService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class EditTransfer extends Component
{
    #[Layout('layouts.app')]
    public FundSourceTransfer $transfer;

    // Properti form
    public $from_fund_source_id;
    public $to_fund_source_id;
    public $amount;
    public $fee;
    public $description;
    public $transfer_date;

    public $fundSources = [];

    public function mount(FundSourceTransfer $transfer, FundSourceService $fundSourceService)
    {
        abort_if($transfer->user_id !== Auth::id(), 403);

        $this->transfer = $transfer;

        // Isi form
        $this->from_fund_source_id = $transfer->from_fund_source_id;
        $this->to_fund_source_id = $transfer->to_fund_source_id;
        $this->amount = $transfer->amount;
        $this->fee = $transfer->fee;
        $this->description = $transfer->description;
        $this->transfer_date = $transfer->transfer_date;

        $this->fundSources = $fundSourceService->getAllFundSources();
    }

    public function updateTransfer(FundSourceTransferService $fundSourceTransferService)
    {
        $validated = $this->validate([
            'from_fund_source_id' => 'required|exists:fund_sources,id',
            'to_fund_source_id' => 'required|exists:fund_sources,id|different:from_fund_source_id',
            'amount' => 'required|numeric|min:1',
            'fee' => 'nullable|numeric|min:0',
            'transfer_date' => 'required|date',
            'description' => 'nullable|string|max:255',
        ]);

        try {
            $fundSourceTransferService->updateTransfer($this->transfer, $validated);
            session()->flash('success', 'Transfer berhasil diperbarui.');
            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memperbarui transfer: ' . $e->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.pages.edit-transfer');
    }
}
