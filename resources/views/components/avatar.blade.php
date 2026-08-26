@props(['user' => null, 'size' => 'md'])

@php
    $initials = '?';
    if ($user && $user->name) {
        $words = explode(' ', $user->name);
        $initials = strtoupper(substr($words[0], 0, 1));
        if (count($words) > 1) {
            $initials .= strtoupper(substr($words[1], 0, 1));
        }
    }
@endphp

<div {{ $attributes->merge(['class' => 'nb-avatar nb-avatar-' . $size . ' shrink-0 aspect-square']) }}>
    @if($user && $user->avatar)
        <img src="{{ str_starts_with($user->avatar, 'http') ? $user->avatar : Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
    @else
        <span class="opacity-70">{{ $initials }}</span>
    @endif
</div>
