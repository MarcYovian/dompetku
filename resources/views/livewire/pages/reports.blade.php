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
            <h3 class="text-lg font-medium mb-4">Filtered Activities</h3>
            <div class="space-y-4">

                @forelse ($activities as $activity)

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
                                    {{ $activity->data->category->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $activity->data->fundSource->name }}</p>
                            </div>
                            <div class="text-right">
                                <p
                                    class="text-base font-semibold {{ $activity->data->type === 'income' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                    {{ $activity->data->type === 'income' ? '+' : '-' }} Rp
                                    {{ number_format($activity->data->amount, 0, ',', '.') }}
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($activity->activity_date)->format('d M Y') }}</p>
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
                            <button wire:click="confirmDeletion({{ $activity->data->id }}, 'transaction')"
                                type="button" class="hover:text-red-600 dark:hover:text-red-400"
                                title="Hapus Transaksi">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.58.22-2.365.468a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>

                        {{-- Kondisi 2: Jika aktivitas adalah Transfer Dana --}}
                    @else
                        <div class="flex items-center space-x-4">
                            <div
                                class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-full bg-blue-100 dark:bg-blue-800/50">
                                {{-- Ikon Transfer --}}
                                <svg class="h-6 w-6 text-blue-600 dark:text-blue-400"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900 dark:text-gray-100">
                                    Transfer Dana
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Dari <span class="font-medium">{{ $activity->data->fromFundSource->name }}</span>
                                    ke <span class="font-medium">{{ $activity->data->toFundSource->name }}</span>
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-base font-semibold text-gray-700 dark:text-gray-300">
                                    Rp {{ number_format($activity->data->amount, 0, ',', '.') }}
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($activity->activity_date)->format('d M Y') }}
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
                            <button wire:click="confirmDeletion({{ $activity->data->id }}, 'transfer')"
                                type="button" class="hover:text-red-600 dark:hover:text-red-400"
                                title="Hapus Transaksi">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.58.22-2.365.468a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    @endif

                @empty
                    <p class="text-center text-gray-500 dark:text-gray-400 py-8">No activities found for the selected
                        criteria.</p>
                @endforelse

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

    <div x-data="{ show: @entangle('showDeleteModal') }" x-show="show" x-cloak class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            {{-- Latar belakang overlay --}}
            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>

            {{-- Centering trick --}}
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            {{-- Panel Modal --}}
            <div x-show="show" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-xl rounded-2xl">
                <div class="sm:flex sm:items-start">
                    <div
                        class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 dark:bg-red-800/50 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-white" id="modal-title">
                            Hapus Data</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Apakah Anda yakin ingin menghapus data ini? Saldo pada sumber dana terkait akan
                                dikembalikan. Tindakan ini tidak dapat dibatalkan.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button wire:click="deleteItem" type="button"
                        class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 sm:ml-3 sm:w-auto sm:text-sm">
                        Ya, Hapus
                    </button>
                    <button @click="show = false" type="button"
                        class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 sm:mt-0 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
