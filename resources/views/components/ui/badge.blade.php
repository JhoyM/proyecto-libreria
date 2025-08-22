@props([
    'color' => 'primary',
    'value' => null,
])
<span class="badge badge-{{ $color }}">{{ $value ?? $slot }}</span>
