@props(['padding' => true])

<div {{ $attributes->merge(['class' => 'nb-card ' . ($padding ? 'p-4' : 'p-0')]) }}>
    {{ $slot }}
</div>
