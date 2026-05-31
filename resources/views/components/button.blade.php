@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'type' => 'button',
])

@php
    $baseClass = "nb-btn nb-btn-{$variant} nb-btn-{$size} " . ($attributes->get('class') ?? '');
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->except('class')->merge(['class' => $baseClass]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->except('class')->merge(['class' => $baseClass]) }}>
        {{ $slot }}
    </button>
@endif
