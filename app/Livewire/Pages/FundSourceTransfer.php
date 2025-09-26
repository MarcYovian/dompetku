<?php

namespace App\Livewire\Pages;

use App\Services\FundSourceService;
use App\Services\FundSourceTransferService;
use Livewire\Attributes\Layout;
use Livewire\Component;

class FundSourceTransfer extends Component
{
    #[Layout('layouts.app')]
    public $from_fund_source_id;
    public $to_fund_source_id;
    public $amount;
    public $fee = 0;
    public $description;
    public $transfer_date;

    public $fundSources = [];

    protected $fundSourceTransferService;
    protected $fundSourceService;

    public function boot(FundSourceTransferService $fundSourceTransferService, FundSourceService $fundSourceService)
    {
        $this->fundSourceTransferService = $fundSourceTransferService;
        $this->fundSourceService = $fundSourceService;
    }
    public function mount()
    {
        $this->fundSources = $this->fundSourceService->getAllFundSources();
        $this->transfer_date = now()->format('Y-m-d');
    }

    public function save()
    {
        $this->validate([
            'from_fund_source_id' => 'required|exists:fund_sources,id',
            'to_fund_source_id' => 'required|exists:fund_sources,id|different:from_fund_source_id',
            'amount' => 'required|numeric|min:1',
            'fee' => 'nullable|numeric|min:0',
            'transfer_date' => 'required|date',
        ]);

        $fromFundSource = \App\Models\FundSource::find($this->from_fund_source_id);
        if ($fromFundSource->balance < ($this->amount + $this->fee)) {
            $this->addError('amount', 'Saldo tidak mencukupi.');
            return;
        }

        $this->fundSourceTransferService->createTransfer($this->only([
            'from_fund_source_id',
            'to_fund_source_id',
            'amount',
            'fee',
            'description',
            'transfer_date',
        ]));

        session()->flash('success', 'Transfer berhasil dicatat.');
        return $this->redirect('/fund-sources', navigate: true);
    }

    public function render()
    {
        return view('livewire.pages.fund-source-transfer');
    }
}
