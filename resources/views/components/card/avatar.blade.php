{{-- resources/views/components/avatar.blade.php --}}
@props([
    'user',
    'size' => 'md',
    'class' => ''
])

@php
$sizes = [
    'sm' => 'h-8 w-8',
    'md' => 'h-10 w-10',
    'lg' => 'h-12 w-12',
    'xl' => 'h-16 w-16'
];

$sizeClasses = $sizes[$size] ?? $sizes['md'];

// Get user initials (first letter of first name and last name)
$name = $user->name ?? 'User';
$initials = collect(explode(' ', $name))
    ->map(fn($segment) => substr($segment, 0, 1))
    ->take(2)
    ->join('');
@endphp

<div {{ $attributes->merge(['class' => "relative inline-flex {$sizeClasses} {$class}"]) }}>
    @if ($user->profile_photo_path)
        <img 
            src="{{ Storage::url($user->profile_photo_path) }}" 
            alt="{{ $user->name }}"
            class="rounded-full object-cover"
        >
    @else
        <div class="flex items-center justify-center rounded-full h-10 w-10 bg-gray-300">
            <span class="font-medium text-gray-600 text-sm">
                {{ strtoupper($initials) }}
            </span>
        </div>
    @endif

    @if ($user->is_online ?? false)
        <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full bg-green-400 ring-2 ring-white"></span>
    @endif
</div>