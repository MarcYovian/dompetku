{{-- resources/views/livewire/pages/fund-source-transfer.blade.php --}}

<div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100">

    {{-- Header --}}
    <h2 class="text-2xl font-semibold mb-6">{{ __('Edit Transfer From Fund Source') }}</h2>

    {{-- Session Messages --}}
    @if (session()->has('success'))
        <div class="bg-green-100 dark:bg-green-900/50 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg relative mb-6"
            role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="bg-red-100 dark:bg-red-900/50 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg relative mb-6"
            role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <form wire:submit.prevent="updateTransfer" class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md">
        {{-- Grid Container --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- From Fund Source --}}
            <div class="lg:col-span-1">
                <label for="from_fund_source_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dari
                    Sumber Dana</label>
                <select wire:model.live="from_fund_source_id" id="from_fund_source_id"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                    <option value="">Pilih Sumber Dana</option>
                    @foreach ($fundSources as $source)
                        <option value="{{ $source->id }}">{{ $source->name }} (Rp
                            {{ number_format($source->balance, 0, ',', '.') }})</option>
                    @endforeach
                </select>
                @error('from_fund_source_id')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- To Fund Source --}}
            <div class="lg:col-span-1">
                <label for="to_fund_source_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ke
                    Sumber Dana</label>
                <select wire:model="to_fund_source_id" id="to_fund_source_id"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                    <option value="">Pilih Sumber Dana</option>
                    @foreach ($fundSources as $source)
                        <option value="{{ $source->id }}">{{ $source->name }}</option>
                    @endforeach
                </select>
                @error('to_fund_source_id')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- Amount Input --}}
            <div class="lg:col-span-1">
                <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jumlah</label>
                <input type="number" id="amount" wire:model="amount" placeholder="50000"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50"
                    step="0.01">
                @error('amount')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- Fee Input --}}
            <div class="lg:col-span-1">
                <label for="fee" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Biaya Admin
                    (Opsional)</label>
                <input type="number" id="fee" wire:model="fee" placeholder="0"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50"
                    step="0.01">
                @error('fee')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- Transfer Date --}}
            <div class="lg:col-span-2">
                <label for="transfer_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal
                    Transfer</label>
                <input type="date" id="transfer_date" wire:model="transfer_date"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                @error('transfer_date')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- Description Textarea --}}
            <div class="lg:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi
                    (Opsional)</label>
                <textarea id="description" wire:model="description" rows="3" placeholder="e.g., Pindah dana ke e-wallet"
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
                <svg wire:loading wire:target="updateTransfer" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>

                <span>Update Transfer</span>
            </button>
        </div>
    </form>
</div>
