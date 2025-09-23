{{-- resources/views/livewire/categories.blade.php --}}

<div x-data="{ showConfirmModal: false, deleteId: null }">
    <div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100">

        {{-- Header --}}
        <h2 class="text-2xl font-semibold mb-6">Manage Categories</h2>

        {{-- Session Message --}}
        @if (session()->has('message'))
            <div class="bg-green-100 dark:bg-green-900/50 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg relative mb-6"
                role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif

        <form wire:submit.prevent="saveCategory" class="mb-8 p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md">
            <h3 class="text-lg font-medium mb-4">{{ $editingCategoryId ? 'Edit Category' : 'Create New Category' }}</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Input Name --}}
                <div>
                    <label for="name"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                    <input type="text" id="name" wire:model="name" placeholder="e.g., Salary, Groceries"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                    @error('name')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                {{-- Input Type --}}
                <div>
                    <label for="type"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
                    <select id="type" wire:model="type"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                        <option value="expense">Expense</option>
                        <option value="income">Income</option>
                    </select>
                    @error('type')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            {{-- Action Buttons --}}
            <div class="mt-6 flex items-center justify-end space-x-4">
                @if ($editingCategoryId)
                    <button type="button" wire:click="resetForm"
                        class="text-sm text-gray-600 dark:text-gray-400 hover:underline">
                        Cancel
                    </button>
                @endif
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                    {{ $editingCategoryId ? 'Update Category' : 'Add Category' }}
                </button>
            </div>
        </form>

        <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl overflow-hidden">
            <h3 class="text-lg font-medium p-6">Your Categories</h3>

            {{-- Mobile View (Card List) --}}
            <div class="md:hidden">
                <div class="px-4 pb-4 space-y-3">
                    @forelse ($categories as $category)
                        <div class="p-4 border dark:border-gray-700 rounded-lg">
                            <div class="flex justify-between items-center">
                                <p class="font-medium text-gray-900 dark:text-gray-100">{{ $category->name }}</p>
                                <div class="flex items-center space-x-2">
                                    <button wire:click="editCategory({{ $category->id }})"
                                        class="p-2 text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.5L16.732 3.732z">
                                            </path>
                                        </svg>
                                    </button>
                                    <button @click="showConfirmModal = true; deleteId = {{ $category->id }}"
                                        class="p-2 text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="mt-2">
                                @if ($category->type == 'income')
                                    <span
                                        class="px-2 py-1 text-xs font-semibold leading-tight text-green-700 bg-green-100 dark:bg-green-700 dark:text-green-100 rounded-full">Income</span>
                                @else
                                    <span
                                        class="px-2 py-1 text-xs font-semibold leading-tight text-red-700 bg-red-100 dark:bg-red-700 dark:text-red-100 rounded-full">Expense</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 dark:text-gray-400 py-8">No categories found.</p>
                    @endforelse
                </div>
            </div>

            {{-- Desktop View (Table) --}}
            <div class="hidden md:block">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Name</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Type</th>
                            <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($categories as $category)
                            <tr>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $category->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if ($category->type == 'income')
                                        <span
                                            class="px-2 py-1 text-xs font-semibold leading-tight text-green-700 bg-green-100 dark:bg-green-700 dark:text-green-100 rounded-full">Income</span>
                                    @else
                                        <span
                                            class="px-2 py-1 text-xs font-semibold leading-tight text-red-700 bg-red-100 dark:bg-red-700 dark:text-red-100 rounded-full">Expense</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-4">
                                    <button wire:click="editCategory({{ $category->id }})"
                                        class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">Edit</button>
                                    <button @click="showConfirmModal = true; deleteId = {{ $category->id }}"
                                        class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3"
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No categories
                                    found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div x-show="showConfirmModal" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-60"
        @keydown.escape.window="showConfirmModal = false">
        <div x-show="showConfirmModal" x-transition
            class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-md p-6"
            @click.outside="showConfirmModal = false">

            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Confirm Deletion</h3>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Are you sure you want to delete this category? This action cannot be undone.
            </p>

            <div class="mt-6 flex justify-end space-x-4">
                <button @click="showConfirmModal = false"
                    class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none">
                    Cancel
                </button>
                <button @click="$wire.deleteCategory(deleteId); showConfirmModal = false;"
                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none">
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>
