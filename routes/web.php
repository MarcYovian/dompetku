<?php

use App\Livewire\Pages\Categories;
use App\Livewire\Pages\CreateTransaction;
use App\Livewire\Pages\Dashboard;
use App\Livewire\Pages\EditTransaction;
use App\Livewire\Pages\EditTransfer;
use App\Livewire\Pages\FundSources;
use App\Livewire\Pages\FundSourceTransfer;
use App\Livewire\Pages\Home;
use App\Livewire\Pages\Reports;
use Illuminate\Support\Facades\Route;

Route::get('/', Home::class)->name('home');
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::view('profile', 'profile')->name('profile');

    Route::get('/fund-sources', FundSources::class)->name('fund-sources');
    Route::get('/categories', Categories::class)->name('categories');
    Route::get('/transactions/create', CreateTransaction::class)->name('transactions.create');
    Route::get('/transactions/{transaction}/edit', EditTransaction::class)->name('transactions.edit');
    Route::get('/transactions', CreateTransaction::class)->name('transactions');
    Route::get('/reports', Reports::class)->name('reports');
    Route::get('/transfer', FundSourceTransfer::class)->name('transfer');
    Route::get('/transfers/{transfer}/edit', EditTransfer::class)->name('transfers.edit');
});

require __DIR__ . '/auth.php';
