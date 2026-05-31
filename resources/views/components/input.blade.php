@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'error' => null,
])

<div class="nb-form-group">
    @if($label)
        <label for="{{ $name }}" class="nb-label">
            {{ $label }} @if($required) <span class="text-red-500">*</span> @endif
        </label>
    @endif
    
    @if($type === 'textarea')
        <textarea 
            id="{{ $name }}" 
            name="{{ $name }}" 
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => 'nb-textarea']) }}
        >{{ old($name, $value) }}</textarea>
    @else
        <input 
            type="{{ $type }}" 
            id="{{ $name }}" 
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
