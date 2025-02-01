{{-- resources/views/components/card.blade.php --}}
@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'bg-gray-50 sm:rounded-lg ' . $class]) }}>
    {{ $slot }}
</div>