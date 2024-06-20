@props([
    'loading' => 'hide'
])

@php
    $loading = $loading === "show" ? "loading-overlay-showing" : "";
@endphp

<div class="{{$loading}}" id="LoadingOverlayApi" data-loading-overlay>
    <div class="loading-overlay">
        <div class="bounce-loader">
            <div class="bounce1"></div>
            <div class="bounce2"></div>
            <div class="bounce3"></div>
        </div>
    </div>
</div>