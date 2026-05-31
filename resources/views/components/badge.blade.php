@props(['color' => 'yellow'])

<span {{ $attributes->merge(['class' => 'nb-badge nb-badge-' . $color]) }}>
    {{ $slot }}
</span>
