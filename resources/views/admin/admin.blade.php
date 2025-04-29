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
            <h3 class="h4">{{ __('Admin Dashboard') }}</h3>
                <p>{{ __('User') . ': ' . Auth::user()->name }}</p>
                <p>{{ __('Email') . ': ' . Auth::user()->email }}</p>
                <p>{{ __('Role') . ': ' . Auth::user()->role }}</p>
                <p>{{ __('Last Login') . ': ' . Auth::user()->last_login }}</p>
                <p>{{ __('Account Status') . ': ' . Auth::user()->status }}</p>
        </div>
    </div>
    <x-slot name="footer">
        <p>{{ __('Lamas') }}</p>
    </x-slot>
</x-admin.app-layout>
