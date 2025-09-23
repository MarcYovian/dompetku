<?php

namespace App\Livewire\Pages;

use App\Services\FundSourceService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class FundSources extends Component
{
    #[Layout('layouts.app')]
    public $fundSources;
    public $name;
    public $balance;
    public $editingFundSourceId = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'balance' => 'required|numeric|min:0',
    ];

    protected $fundSourceService;

    public function boot(FundSourceService $fundSourceService)
    {
        $this->fundSourceService = $fundSourceService;
    }

    public function mount()
    {
        $this->loadFundSources();
    }

    public function loadFundSources()
    {
        $this->fundSources = Auth::user()->fundSources;
    }

    public function saveFundSource()
    {
        $this->validate();

        if ($this->editingFundSourceId) {
            $this->fundSourceService->updateFundSource($this->editingFundSourceId, [
                'name' => $this->name,
                'balance' => $this->balance,
            ]);
            $this->editingFundSourceId = null;
        } else {
            $this->fundSourceService->createFundSource([
                'user_id' => Auth::id(),
                'name' => $this->name,
                'balance' => $this->balance,
            ]);
        }

        $this->reset(['name', 'balance']);
        $this->loadFundSources();
        session()->flash('message', 'Fund source saved successfully.');
    }

    public function resetForm()
    {
        $this->reset(['name', 'balance']);
        $this->editingFundSourceId = null;
    }

    public function editFundSource($id)
    {
        $fundSource = $this->fundSourceService->getFundSourceById($id);
        $this->editingFundSourceId = $fundSource->id;
        $this->name = $fundSource->name;
        $this->balance = $fundSource->balance;
    }

    public function deleteFundSource($id)
    {
        $this->fundSourceService->deleteFundSource($id);
        $this->loadFundSources();
        session()->flash('message', 'Fund source deleted successfully.');
    }

    public function render()
    {
        return view('livewire.pages.fund-sources');
    }
}
