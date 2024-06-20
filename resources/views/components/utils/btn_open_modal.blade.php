@vite(['resources/js/components/utils/btn_open_modal.module.js'])

{{-- animation values = zoom|slide --}}

@props([
    'className' => '',
    'icon' => false,
    'animation',
])

{{-- <?php xdebug_break(); ?> --}}

@php
    $animation = isset($animation) ? "modal-with-$animation-anim" : "";
@endphp

<a class="modal-basic {{$className}} {{$animation}}" href="#modalBasic">
    @if ($icon)
        <i class="{{$icon}}"></i>
    @endif
    Sair
    {{ $slot }}
</a>