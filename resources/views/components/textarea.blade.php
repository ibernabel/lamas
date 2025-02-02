{{-- resources/views/components/textarea.blade.php --}}
@props([
    'disabled' => false
])

<textarea
    {{ $disabled ? 'disabled' : '' }}
    {!! $attributes->merge([
        'class' => 'mt-1 block w-full rounded-md shadow-sm border-gray-300
        dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500
        dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600  sm:text-sm' . ($errors->has($attributes->get('name')) ? ' border-red-300' : '')
    ]) !!}
>{{ $slot }}</textarea>
