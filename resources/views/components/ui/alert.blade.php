@props([
    'type' => 'info', // info | success | warning | error
    'title' => null,
    'message' => null,
])
@php
    $map = [
        'info' => ['cls' => 'alert-info', 'icon' => 'ℹ️'],
        'success' => ['cls' => 'alert-success', 'icon' => '✅'],
        'warning' => ['cls' => 'alert-warning', 'icon' => '⚠️'],
        'error' => ['cls' => 'alert-error', 'icon' => '⛔'],
    ];
    $meta = $map[$type] ?? $map['info'];
@endphp

<div class="alert {{ $meta['cls'] }}">
    <span class="text-xl leading-none">{{ $meta['icon'] }}</span>
    <div>
        @if($title)
            <h3 class="font-bold">{{ $title }}</h3>
        @endif
        <div class="text-sm">{{ $message ?? ($slot->isNotEmpty() ? $slot : '') }}</div>
    </div>
</div>
