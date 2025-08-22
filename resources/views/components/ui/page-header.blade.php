@props([
    'title',
    'subtitle' => null,
    'icon' => null,
])

<div class="mb-6">
    <div class="flex items-center justify-between gap-4">
        <div class="flex items-start gap-3">
            @if($icon)
                <div class="text-primary text-2xl">{!! $icon !!}</div>
            @endif
            <div>
                <h1 class="text-2xl font-bold">{{ $title }}</h1>
                @if($subtitle)
                    <p class="text-sm text-base-content/70 mt-1">{{ $subtitle }}</p>
                @endif
            </div>
        </div>
        <div class="shrink-0">
            {{ $actions ?? '' }}
        </div>
    </div>
</div>
