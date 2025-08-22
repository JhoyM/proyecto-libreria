@props([
    'title',
    'description' => null,
    'actionUrl' => null,
    'actionLabel' => null,
    'actionColor' => 'primary',
    'icon' => '📭',
])

<div class="card bg-base-100 shadow">
    <div class="card-body items-center text-center">
        <div class="text-4xl">{!! $icon !!}</div>
        <h2 class="card-title mt-2">{{ $title }}</h2>
        @if($description)
            <p class="text-base-content/70">{{ $description }}</p>
        @endif
        @if($actionUrl && $actionLabel)
            <div class="card-actions mt-2">
                <a href="{{ $actionUrl }}" class="btn btn-{{ $actionColor }}">{{ $actionLabel }}</a>
            </div>
        @endif
    </div>
</div>
