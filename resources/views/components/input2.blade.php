{{-- resources/views/components/input.blade.php --}}
@props([
    'disabled' => false,
    'type' => 'text'
])

<input 
    {{ $disabled ? 'disabled' : '' }}
    type="{{ $type }}"
    {!! $attributes->merge([
        'class' => 'mt-1 block w-full rounded-md shadow-sm border-gray-300
        focus:border-indigo-500
        focus:ring-indigo-500 sm:text-sm' . ($errors->has($attributes->get('name')) ? ' border-red-300' : '')
    ]) !!}
>