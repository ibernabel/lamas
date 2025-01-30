{{-- resources/views/components/card.blade.php --}}
@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'bg-white shadow sm:rounded-lg ' . $class]) }}>
    {{ $slot }}
</div>