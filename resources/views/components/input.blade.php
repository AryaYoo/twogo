@props([
    'name',
    'id' => null,
    'label' => null,
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'error' => null,
])

@php
    $inputId = $id ?? $name;
@endphp

<div class="nb-form-group">
    @if($label)
        <label for="{{ $inputId }}" class="nb-label">
            {{ $label }} @if($required) <span class="text-red-500">*</span> @endif
        </label>
    @endif
    
    @if($type === 'textarea')
        <textarea 
            id="{{ $inputId }}" 
            name="{{ $name }}" 
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => 'nb-textarea']) }}
        >{{ old($name, $value) }}</textarea>
    @elseif($type === 'password')
        <div class="relative w-full">
            <input 
                type="password" 
                id="{{ $inputId }}" 
                name="{{ $name }}" 
                value="{{ old($name, $value) }}"
                placeholder="{{ $placeholder }}"
                {{ $required ? 'required' : '' }}
                {{ $attributes->merge(['class' => 'nb-input !pr-11']) }}
            >
            <button 
                type="button" 
                onclick="if(window.togglePasswordVisibility){window.togglePasswordVisibility('{{ $inputId }}')}else{var el=document.getElementById('{{ $inputId }}');el.type=el.type==='password'?'text':'password'}"
                tabindex="-1"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-[#1A1A2E] hover:text-[#4361EE] focus:outline-none p-1 transition-all hover:scale-110 active:scale-95 cursor-pointer z-10"
                title="Lihat / Sembunyikan Password"
                aria-label="Lihat / Sembunyikan Password"
            >
                <svg id="eye-icon-{{ $inputId }}" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <svg id="eye-off-icon-{{ $inputId }}" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                </svg>
            </button>
        </div>
    @else
        <input 
            type="{{ $type }}" 
            id="{{ $inputId }}" 
            name="{{ $name }}" 
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => 'nb-input']) }}
        >
    @endif
    
    @if($error || $errors->has($name))
        <p class="text-red-500 text-xs font-bold mt-1">
            {{ $error ?? $errors->first($name) }}
        </p>
    @endif
</div>
