@props(['icon' => '🌟', 'title', 'description' => null])

<div {{ $attributes->merge(['class' => 'nb-empty-state']) }}>
    <div class="text-5xl mb-4 grayscale hover:grayscale-0 transition-all duration-300">
        {{ $icon }}
    </div>
    <h3 class="font-heading font-bold text-xl mb-2">{{ $title }}</h3>
    @if($description)
        <p class="font-medium text-sm opacity-80 mb-6 max-w-xs">{{ $description }}</p>
    @endif
    {{ $slot }}
</div>
