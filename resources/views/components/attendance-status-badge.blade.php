<!-- File: resources/views/components/attendance-status-badge.blade.php -->
<!-- Komponen untuk menampilkan badge status absensi dengan animasi modern -->

@props(['status'])

@php
    $statusConfig = [
        'hadir' => [
            'bg' => 'bg-gradient-to-r from-green-400 to-green-500',
            'text' => 'text-white',
            'border' => 'border-green-300',
            'icon' => '✅',
            'label' => 'Disetujui',
            'shadow' => 'shadow-lg shadow-green-500/50'
        ],
        'pending' => [
            'bg' => 'bg-gradient-to-r from-amber-400 to-amber-500',
            'text' => 'text-white',
            'border' => 'border-amber-300',
            'icon' => '⏳',
            'label' => 'Menunggu Persetujuan',
            'animate' => 'badge-pulse',
            'shadow' => 'shadow-lg shadow-amber-500/50'
        ],
        'ditolak' => [
            'bg' => 'bg-gradient-to-r from-red-400 to-red-500',
            'text' => 'text-white',
            'border' => 'border-red-300',
            'icon' => '❌',
            'label' => 'Ditolak',
            'shadow' => 'shadow-lg shadow-red-500/50'
        ]
    ];
    
    $config = $statusConfig[$status] ?? $statusConfig['pending'];
    $animate = $config['animate'] ?? '';
    $shadow = $config['shadow'] ?? '';
@endphp

<span class="inline-flex items-center {{ $config['bg'] }} {{ $config['text'] }} text-sm font-bold px-4 py-1.5 rounded-full border {{ $config['border'] }} {{ $animate }} {{ $shadow }} transition-all hover:scale-105 cursor-pointer">
    <span class="mr-2 text-lg">{{ $config['icon'] }}</span>
    {{ $config['label'] }}
</span>
