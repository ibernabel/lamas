{{-- resources/views/components/button.blade.php --}}
@props([
    'type' => 'button',
    'variant' => 'primary'
])

@php
    $variantClasses = [
        'primary' => 'bg-primary-600 dark:bg-primary-500 hover:bg-primary-700 dark:hover:bg-primary-600 focus:ring-primary-500 dark:focus:ring-primary-400 text-white',
        'secondary' => 'bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:ring-primary-500 dark:focus:ring-primary-400 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600',
        'danger' => 'bg-red-600 dark:bg-red-500 hover:bg-red-700 dark:hover:bg-red-600 focus:ring-red-500 dark:focus:ring-red-400 text-white',
    ][$variant];
@endphp

<button
    type="{{ $type }}"
    {{ $attributes->merge([
        'class' => 'inline-flex items-center px-4 py-2 border border-transparent 
                   text-sm font-medium rounded-md shadow-sm 
                   focus:outline-none focus:ring-2 focus:ring-offset-2
                   dark:focus:ring-offset-gray-900 ' . $variantClasses
    ]) }}
>
    {{ $slot }}
</button>