{{-- resources/views/components/sidebar-nav-link.blade.php --}}
@props(['active'])

@php
    $classes =
        $active ?? false
            ? 'flex items-center px-3 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md transition-colors duration-150'
            : 'flex items-center px-3 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition-colors duration-150';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
