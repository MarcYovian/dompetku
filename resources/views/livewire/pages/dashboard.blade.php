{{-- resources/views/livewire/pages/dashboard.blade.php --}}

<div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h2 class="text-2xl font-semibold">Dashboard</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Welcome back, here's a summary of your finances.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('transactions.create') }}" wire:navigate
                class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-900">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                        clip-rule="evenodd" />
                </svg>
                Add Transaction
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        {{-- Total Balance --}}
        <div class="flex items-center p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md">
            <div
                class="flex-shrink-0 flex items-center justify-center h-16 w-16 rounded-full bg-indigo-100 dark:bg-indigo-800/50 text-indigo-600 dark:text-indigo-400">
                <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                </svg>
            </div>
            <div class="ml-6">
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Balance</h4>
                <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">Rp
                    {{ number_format($totalBalance, 0, ',', '.') }}</p>
            </div>
        </div>
        {{-- Monthly Income --}}
        <div class="flex items-center p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md">
            <div
                class="flex-shrink-0 flex items-center justify-center h-16 w-16 rounded-full bg-green-100 dark:bg-green-800/50 text-green-600 dark:text-green-400">
                <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v.01" />
                </svg>
            </div>
            <div class="ml-6">
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Monthly Income</h4>
                <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">Rp
                    {{ number_format($monthlyIncome, 0, ',', '.') }}</p>
            </div>
        </div>
        {{-- Monthly Expense --}}
        <div class="flex items-center p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md">
            <div
                class="flex-shrink-0 flex items-center justify-center h-16 w-16 rounded-full bg-red-100 dark:bg-red-800/50 text-red-600 dark:text-red-400">
                <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
            </div>
            <div class="ml-6">
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Monthly Expense</h4>
                <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">Rp
                    {{ number_format($monthlyExpense, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
        <div class="lg:col-span-3 bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md">
            <h3 class="text-lg font-medium mb-4">Latest Transactions</h3>
            <div class="space-y-4">
                @if ($latestTransactions->isEmpty())
                    <p class="text-center text-gray-500 dark:text-gray-400 py-8">No transactions recorded yet.</p>
                @else
                    @foreach ($latestTransactions as $transaction)
                        <div class="flex items-center space-x-4">
                            <div
                                class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-full {{ $transaction->type === 'income' ? 'bg-green-100 dark:bg-green-800/50' : 'bg-red-100 dark:bg-red-800/50' }}">
                                <svg class="h-6 w-6 {{ $transaction->type === 'income' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    @if ($transaction->type === 'income')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M18 12H6" />
                                    @endif
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900 dark:text-gray-100">
                                    {{ $transaction->category->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $transaction->transaction_date->format('d M Y') }}</p>
                            </div>
                            <p
                                class="text-base font-semibold {{ $transaction->type === 'income' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $transaction->type === 'income' ? '+' : '-' }} Rp
                                {{ number_format($transaction->amount, 0, ',', '.') }}
                            </p>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <div class="lg:col-span-2 bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md">
            <h3 class="text-lg font-medium mb-4">Expense Distribution (This Month)</h3>
            <div class="space-y-4">
                @if ($expenseDistribution->isEmpty())
                    <p class="text-center text-gray-500 dark:text-gray-400 py-8">No expenses to display.</p>
                @else
                    @php $totalExpenseForDistribution = max($monthlyExpense, 1); @endphp
                    @foreach ($expenseDistribution as $distribution)
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $distribution->category_name }}</span>
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Rp
                                    {{ number_format($distribution->total_amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                                <div class="bg-red-500 dark:bg-red-600 h-2.5 rounded-full"
                                    style="width: {{ ($distribution->total_amount / $totalExpenseForDistribution) * 100 }}%">
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
