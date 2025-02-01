<x-admin.app-layout>
  <x-slot name="title">
    {{ __('Admin') }}
  </x-slot>
  <x-slot name="content_header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Dashboard') }}
    </h2>
</x-slot>
<div class="card">
  <div class="card-body">
    <p>Contenido Espectacular</p>
  </div>
</div>
<x-slot name="footer">
  <p>{{ __('Footer') }}</p>
</x-slot>
</x-admin.app-layout>
