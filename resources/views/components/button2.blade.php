{{-- resources/views/components/button.blade.php --}}
@props([
    'type' => 'button | submit | reset',
    'variant' => 'primary'
])

@php
    $variantClasses = [
        'primary' => 'bg-primary-600 dark:bg-primary-500 hover:bg-primary-700 dark:hover:bg-primary-600 focus:ring-primary-500 dark:focus:ring-primary-400 text-white',
        'secondary' => 'bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:ring-primary-500 dark:focus:ring-primary-400 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600',
        'danger' => 'bg-red-600 dark:bg-red-500 hover:bg-red-700 dark:hover:bg-red-600 focus:ring-red-500 dark:focus:ring-red-400 text-white',
        'success' => 'bg-green-600 dark:bg-green-500 hover:bg-green-700 dark:hover:bg-green-600 focus:ring-green-500 dark:focus:ring-green-400 text-white',
        'warning' => 'bg-yellow-600 dark:bg-yellow-500 hover:bg-yellow-700 dark:hover:bg-yellow-600 focus:ring-yellow-500 dark:focus:ring-yellow-400 text-white',
        'info' => 'bg-blue-600 dark:bg-blue-500 hover:bg-blue-700 dark:hover:bg-blue-600 focus:ring-blue-500 dark:focus:ring-blue-400 text-white',
        'light' => 'bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 focus:ring-gray-500 dark:focus:ring-gray-400 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600',
        'dark' => 'bg-gray-800 dark:bg-gray-900 hover:bg-gray-700 dark:hover:bg-gray-800 focus:ring-gray-500 dark:focus:ring-gray-400 text-white',
        'link' => 'bg-transparent dark:bg-transparent hover:bg-gray-100 dark:hover:bg-gray-700 focus:ring-gray-500 dark:focus:ring-gray-400 text-blue-600 dark:text-blue-500 border border-transparent',
        'outline' => 'bg-transparent dark:bg-transparent hover:bg-gray-100 dark:hover:bg-gray-700 focus:ring-gray-500 dark:focus:ring-gray-400 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600',
        'outline-primary' => 'bg-transparent dark:bg-transparent hover:bg-gray-100 dark:hover:bg-gray-700 focus:ring-gray-500 dark:focus:ring-gray-400 text-primary-600 dark:text-primary-500 border border-primary-300 dark:border-primary-600',
        'outline-secondary' => 'bg-transparent dark:bg-transparent hover:bg-gray-100 dark:hover:bg-gray-700 focus:ring-gray-500 dark:focus:ring-gray-400 text-secondary-600 dark:text-secondary-500 border border-secondary-300 dark:border-secondary-600',
        'outline-danger' => 'bg-transparent dark:bg-transparent hover:bg-gray-100 dark:hover:bg-gray-700 focus:ring-gray-500 dark:focus:ring-gray-400 text-danger-600 dark:text-danger-500 border border-danger-300 dark:border-danger-600',
        'outline-success' => 'bg-transparent dark:bg-transparent hover:bg-gray-100 dark:hover:bg-gray-700 focus:ring-gray-500 dark:focus:ring-gray-400 text-success-600 dark:text-success-500 border border-success-300 dark:border-success-600',
        'outline-warning' => 'bg-transparent dark:bg-transparent hover:bg-gray-100 dark:hover:bg-gray-700 focus:ring-gray-500 dark:focus:ring-gray-400 text-warning-600 dark:text-warning-500 border border-warning-300 dark:border-warning-600',
        'outline-info' => 'bg-transparent dark:bg-transparent hover:bg-gray-100 dark:hover:bg-gray-700 focus:ring-gray-500 dark:focus:ring-gray-400 text-info-600 dark:text-info-500 border border-info-300 dark:border-info-600',
        'outline-light' => 'bg-transparent dark:bg-transparent hover:bg-gray-100 dark:hover:bg-gray-700 focus:ring-gray-500 dark:focus:ring-gray-400 text-light-600 dark:text-light-500 border border-light-300 dark:border-light-600',
        'outline-dark' => 'bg-transparent dark:bg-transparent hover:bg-gray-100 dark:hover:bg-gray-700 focus:ring-gray-500 dark:focus:ring-gray-400 text-dark-600 dark:text-dark-500 border border-dark-300 dark:border-dark-600',
        'outline-link' => 'bg-transparent dark:bg-transparent hover:bg-gray-100 dark:hover:bg-gray-700 focus:ring-gray-500 dark:focus:ring-gray-400 text-link-600 dark:text-link-500 border border-link-300 dark:border-link-600',
        'submit' => 'bg-primary-600 dark:bg-primary-500 hover:bg-primary-700 dark:hover:bg-primary-600 focus:ring-primary-500 dark:focus:ring-primary-400 text-white',
        'reset' => 'bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 focus:ring-gray-500 dark:focus:ring-gray-400 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600',
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