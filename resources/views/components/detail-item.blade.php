{{-- resources/views/components/detail-item.blade.php --}}
@props(['label', 'value', 'prefix' => null, 'suffix' => null])

<div class="sm:col-span-1">
    <dt class="text-sm font-medium text-gray-500">{{ $label }}</dt>
    <dd class="mt-1 text-sm text-gray-900">
        {{ $prefix }} {{ $value }} {{ $suffix }}
    </dd>
</div>