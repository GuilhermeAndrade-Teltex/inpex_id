@vite(['resources/css/components/utils/btn.css'])

@props([
    'tagHtml' => 'button',
    'ref' => "#",
    'type' => 'button',
    'color' => 'primary',
    'class' => '',
    'id' => '',
    'text',
    'disabled' => false,
    'size' => '',
    'icon' => false
])

@php
    $disabled = $disabled ? "disabled" : "";

    $size_options = ['xs', 'sm', 'lg'];
    $size = in_array($size, $size_options) ? "btn-$size" : '';
@endphp

@switch($tagHtml)
    @case('button')
        <button type="{{$type}}" class="btn btn-{{$color}} {{$size}} {{$class}}" {{$disabled}} id="{{$id}}">
            @if($icon)
                <i class="fas fa-{{$icon}}"></i>
            @endif
            {{$text}}
        </button>
        @break
    @case('a')
        <a class="btn btn-{{$color}} {{$size}} {{$class}} {{$disabled}}" href="{{$ref}}" id="{{$id}}">
            @if($icon)
                <i class="fas fa-{{$icon}}"></i>
            @endif
            {{$text}}
        </a>
        @break
@endswitch