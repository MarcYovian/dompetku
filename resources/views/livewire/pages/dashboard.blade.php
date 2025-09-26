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
        {{-- Card untuk Aktivitas Terbaru --}}
        <div class="lg:col-span-3 bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md">
            <h3 class="text-lg font-medium mb-4">Latest Activities</h3>
            <div class="space-y-4">

                {{-- Gunakan @forelse untuk menangani jika tidak ada aktivitas --}}
                @forelse ($latestActivities as $activity)

                    {{-- Kondisi 1: Jika aktivitas adalah Transaksi (Pemasukan/Pengeluaran) --}}
                    @if ($activity->activity_type == 'transaction')
                        <div class="flex items-center space-x-4">
                            <div
                                class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-full {{ $activity->data->type === 'income' ? 'bg-green-100 dark:bg-green-800/50' : 'bg-red-100 dark:bg-red-800/50' }}">
                                {{-- Ikon Pemasukan/Pengeluaran --}}
                                <svg class="h-6 w-6 {{ $activity->data->type === 'income' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    @if ($activity->data->type === 'income')
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
                                    {{ $activity->data->category->name }}
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $activity->data->fundSource->name }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p
                                    class="text-base font-semibold {{ $activity->data->type === 'income' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                    {{ $activity->data->type === 'income' ? '+' : '-' }} Rp
                                    {{ number_format($activity->data->amount, 0, ',', '.') }}
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($activity->data->activity_date)->format('d M Y') }}</p>
                            </div>
                            @if ($activity->data->fund_source_transfer_id)
                                {{-- Jika ini adalah biaya transfer, arahkan ke edit transfer --}}
                                <a href="{{ route('transfers.edit', $activity->data->fund_source_transfer_id) }}"
                                    wire:navigate class="text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400"
                                    title="Edit Biaya di Transfer Induk">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path
                                            d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z" />
                                        <path
                                            d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z" />
                                    </svg>
                                </a>
                            @else
                                {{-- Jika ini transaksi biasa, arahkan ke edit transaction --}}
                                <a href="{{ route('transactions.edit', $activity->data) }}" wire:navigate
                                    class="text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400"
                                    title="Edit Transaksi">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path
                                            d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z" />
                                        <path
                                            d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z" />
                                    </svg>
                                </a>
                            @endif
                        </div>

                        {{-- Kondisi 2: Jika aktivitas adalah Transfer Dana --}}
                    @else
                        <div class="flex items-center space-x-4">
                            <div
                                class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-full bg-blue-100 dark:bg-blue-800/50">
                                {{-- Ikon Transfer --}}
                                <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                {{-- PERUBAHAN UTAMA DI SINI --}}
                                <p class="font-medium text-gray-900 dark:text-gray-100">
                                    Transfer Dana
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    <span class="font-medium">{{ $activity->data->fromFundSource->name }}</span>
                                    <svg class="inline h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                    <span class="font-medium">{{ $activity->data->toFundSource->name }}</span>
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-base font-semibold text-gray-700 dark:text-gray-300">
                                    Rp {{ number_format($activity->data->amount, 0, ',', '.') }}
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($activity->data->activity_date)->format('d M Y') }}
                                </p>
                            </div>
                            <a href="{{ route('transfers.edit', $activity->data) }}" wire:navigate
                                class="text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400"
                                title="Edit Transaksi">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path
                                        d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z" />
                                    <path
                                        d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z" />
                                </svg>
                            </a>
                        </div>
                    @endif

                    {{-- Tampilan jika tidak ada aktivitas sama sekali --}}
                @empty
                    <p class="text-center text-gray-500 dark:text-gray-400 py-8">No activities recorded yet.</p>
                @endforelse

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
