@props([
    'items' => [], // each: ['label' => 'Home', 'url' => route('dashboard')]
])

@if(!empty($items))
<div class="breadcrumbs text-sm mb-4">
    <ul>
        @foreach($items as $item)
            @php($label = $item['label'] ?? '')
            @php($url = $item['url'] ?? null)
            <li>
                @if($url)
                    <a href="{{ $url }}">{{ $label }}</a>
                @else
                    <span>{{ $label }}</span>
                @endif
            </li>
        @endforeach
    </ul>
</div>
@endif
