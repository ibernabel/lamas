{{-- resources/views/components/detail-item.blade.php --}}
@props(['label', 'value', 'prefix' => null, 'suffix' => null])

<div class="grid grid-cols-4 gap-x-3 items-start text-wrap">
  <dt class="col-span-1 font-normal text-right text-black">{{ $label }}</dt>
  <dd class="col-span-3 text-left text-gray-500 ">{{ $prefix }} {{ $value }} {{ $suffix }}</dd>
</div>