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

<div {{ $attributes->merge(['class' => 'nb-avatar nb-avatar-' . $size]) }}>
    @if($user && $user->avatar)
        <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}">
    @else
        <span class="opacity-70">{{ $initials }}</span>
    @endif
</div>
