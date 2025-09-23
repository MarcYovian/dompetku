{{-- resources/views/livewire/create-transaction.blade.php --}}

<div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100">

    {{-- Header --}}
    <h2 class="text-2xl font-semibold mb-6">Record New Transaction</h2>

    {{-- Session Messages --}}
    @if (session()->has('message'))
        <div class="bg-green-100 dark:bg-green-900/50 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg relative mb-6"
            role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="bg-red-100 dark:bg-red-900/50 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg relative mb-6"
            role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <form wire:submit.prevent="saveTransaction" class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md">
        {{-- Grid Container --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Amount Input --}}
            <div class="lg:col-span-1">
                <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Amount</label>
                <input type="number" id="amount" wire:model="amount" placeholder="50000"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50"
                    step="0.01">
                @error('amount')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- Type Select --}}
            <div class="lg:col-span-1">
                <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
                <select id="type" wire:model.live="type"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                    <option value="expense">Expense</option>
                    <option value="income">Income</option>
                </select>
                @error('type')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- Date Input --}}
            <div class="lg:col-span-1">
                <label for="transaction_date"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date</label>
                <input type="date" id="transaction_date" wire:model="transaction_date"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                @error('transaction_date')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- Category Select --}}
            <div class="lg:col-span-1">
                <label for="category_id"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                <select id="category_id" wire:model="category_id"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                    <option value="">-- Select Category --</option>
                    @forelse($filteredCategories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @empty
                        <option value="" disabled>No categories for this type</option>
                    @endforelse
                </select>
                @error('category_id')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- Fund Source Select --}}
            <div class="lg:col-span-2">
                <label for="fund_source_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fund
                    Source</label>
                <select id="fund_source_id" wire:model="fund_source_id"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                    <option value="">-- Select Fund Source --</option>
                    @foreach ($fundSources as $fundSource)
                        <option value="{{ $fundSource->id }}">{{ $fundSource->name }} (Rp
                            {{ number_format($fundSource->balance, 0, ',', '.') }})</option>
                    @endforeach
                </select>
                @error('fund_source_id')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- Description Textarea --}}
            <div class="lg:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description
                    (Optional)</label>
                <textarea id="description" wire:model="description" rows="3" placeholder="e.g., Monthly electricity bill"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50"></textarea>
                @error('description')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- Action Button --}}
        <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-6 flex justify-end">
            <button type="submit" wire:loading.attr="disabled"
                class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 disabled:opacity-50 disabled:cursor-wait">

                {{-- Loading Spinner --}}
                <svg wire:loading wire:target="saveTransaction" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>

                <span>Record Transaction</span>
            </button>
        </div>
    </form>
</div>
