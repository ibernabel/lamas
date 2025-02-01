{{-- resources/views/components/loan-status.blade.php --}}
@props(['status'])

@php
$statusClasses = [
  'received' => [
      'bg' => 'bg-yellow-50',
      'text' => 'text-yellow-700',
      'dot' => 'bg-yellow-400',
      'label' => 'received'
  ],
  'verified' => [
      'bg' => 'bg-purple-50',
      'text' => 'text-purple-700',
      'dot' => 'bg-purple-400',
      'label' => 'verified'
  ],
  'assigned' => [
      'bg' => 'bg-blue-50',
      'text' => 'text-blue-700',
      'dot' => 'bg-blue-400',
      'label' => 'assigned'
  ],
  'analyzed' => [
      'bg' => 'bg-cyan-50',
      'text' => 'text-cyan-700',
      'dot' => 'bg-cyan-400',
      'label' => 'analyzed'
  ],
    'approved' => [
        'bg' => 'bg-green-50',
        'text' => 'text-green-700',
        'dot' => 'bg-green-400',
        'label' => 'approved'
    ],
    'rejected' => [
        'bg' => 'bg-red-50',
        'text' => 'text-red-700',
        'dot' => 'bg-red-400',
        'label' => 'rejected'
    ],
    'archived' => [
        'bg' => 'bg-gray-50',
        'text' => 'text-gray-700',
        'dot' => 'bg-gray-400',
        'label' => 'archived'
    ]
];

$currentStatus = $statusClasses[$status] ?? $statusClasses['received'];
@endphp

<span class="inline-flex items-center rounded-md px-2 py-1 text-sm font-medium ring-1 ring-inset {{ $currentStatus['bg'] }} {{ $currentStatus['text'] }} ring-{{ explode('-', $currentStatus['text'])[1] }}-600/20">
    <svg class="mr-1.5 h-2 w-2 {{ $currentStatus['dot'] }}" fill="currentColor" viewBox="0 0 8 8">
        <circle cx="4" cy="4" r="3" />
    </svg>
    {{ __($currentStatus['label']) }}
</span>