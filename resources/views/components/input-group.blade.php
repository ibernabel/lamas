{{-- resources/views/components/input-group.blade.php --}}
@props([
    'inline' => false
])

<div {{ $attributes->merge(['class' => $inline ? 'sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5' : 'space-y-1']) }}>
    {{ $slot }}
</div>