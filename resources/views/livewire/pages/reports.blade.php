{{-- resources/views/livewire/reports.blade.php --}}

<div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100">
    <h2 class="text-2xl font-semibold mb-6">Reports & Analysis</h2>

    <div class="mb-8 p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md">
        <h3 class="text-lg font-medium mb-4">Filter Report</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Start Date --}}
            <div>
                <label for="startDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start
                    Date</label>
                <input type="date" id="startDate" wire:model="startDate"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
            </div>
            {{-- End Date --}}
            <div>
                <label for="endDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">End
                    Date</label>
                <input type="date" id="endDate" wire:model="endDate"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
            </div>
            {{-- Type --}}
            <div>
                <label for="selectedType"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
                <select id="selectedType" wire:model.live="selectedType"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                    <option value="">All Types</option>
                    <option value="income">Income</option>
                    <option value="expense">Expense</option>
                </select>
            </div>
            {{-- Fund Source --}}
            <div>
                <label for="selectedFundSource" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fund
                    Source</label>
                <select id="selectedFundSource" wire:model.live="selectedFundSource"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                    <option value="">All Sources</option>
                    @foreach ($fundSources as $fundSource)
                        <option value="{{ $fundSource->id }}">{{ $fundSource->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6 flex justify-end">
            <button wire:click="generateReport" wire:loading.attr="disabled"
                class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 disabled:opacity-50 disabled:cursor-wait">
                <svg wire:loading wire:target="generateReport" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                    fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                <span>Generate Report</span>
            </button>
        </div>
    </div>

    <div wire:loading.remove wire:target="generateReport">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            {{-- Total Income --}}
            <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md">
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Income</h4>
                <p class="mt-2 text-3xl font-semibold text-green-600 dark:text-green-400">Rp
                    {{ number_format($totalIncome, 0, ',', '.') }}</p>
            </div>
            {{-- Total Expense --}}
            <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md">
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Expense</h4>
                <p class="mt-2 text-3xl font-semibold text-red-600 dark:text-red-400">Rp
                    {{ number_format($totalExpense, 0, ',', '.') }}</p>
            </div>
            {{-- Net Difference --}}
            <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md">
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Net Result</h4>
                <p
                    class="mt-2 text-3xl font-semibold {{ $netDifference >= 0 ? 'text-gray-800 dark:text-gray-200' : 'text-red-600 dark:text-red-400' }}">
                    Rp {{ number_format($netDifference, 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
        <div class="lg:col-span-3 bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md">
            <h3 class="text-lg font-medium mb-4">Filtered Transactions</h3>
            <div class="space-y-4">
                @if ($transactions->isEmpty())
                    <p class="text-center text-gray-500 dark:text-gray-400 py-8">No transactions found for the selected
                        criteria.</p>
                @else
                    @foreach ($transactions as $transaction)
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
                                    {{ $transaction->transaction_date->format('d M Y') }} -
                                    {{ $transaction->fundSource->name }}</p>
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
            <h3 class="text-lg font-medium mb-4">Expense Distribution</h3>
            <div class="space-y-4">
                @if ($expenseDistribution->isEmpty())
                    <p class="text-center text-gray-500 dark:text-gray-400 py-8">No expenses to display.</p>
                @else
                    {{-- Pastikan total expense > 0 untuk menghindari division by zero --}}
                    @php $totalExpenseForDistribution = max($expenseDistribution->sum('total_amount'), 1); @endphp
                    @foreach ($expenseDistribution as $distribution)
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $distribution->category_name }}</span>
                                <span class="text-sm font-semibold text-red-600 dark:text-red-400">Rp
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
