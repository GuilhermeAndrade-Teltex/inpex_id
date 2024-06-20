@vite(['resources/js/components/utils/modal.module.js'])

@props([
    'headerTitle',
    'maxWidth' => 'md',
    'footerBtn1',
    'footerBtn2',
    'footerBtn3' => false
])

{{-- <?php xdebug_break(); ?> --}}

@php
    $footerBtn1 = !!$footerBtn1 ? $dom_json_decode($footerBtn1) : false;
    $footerBtn2 = !!$footerBtn2 ? $dom_json_decode($footerBtn2) : false;
    $footerBtn3 = !!$footerBtn3 ? $dom_json_decode($footerBtn3) : false;

    $idBtn1 = isset($footerBtn1["id"]) ? $footerBtn1["id"] : '';
    $idBtn2 = isset($footerBtn2["id"]) ? $footerBtn2["id"] : '';
    $idBtn3 = isset($footerBtn3["id"]) ? $footerBtn3["id"] : '';

    $iconBtn1 = isset($footerBtn1["icon"]) ? $footerBtn1["icon"] : '';
    $iconBtn2 = isset($footerBtn2["icon"]) ? $footerBtn2["icon"] : '';
    $iconBtn3 = isset($footerBtn3["icon"]) ? $footerBtn3["icon"] : '';

    $colorBtn1 = isset($footerBtn1["color"]) ? $footerBtn1["color"] : '';
    $colorBtn2 = isset($footerBtn2["color"]) ? $footerBtn2["color"] : '';
    $colorBtn3 = isset($footerBtn3["color"]) ? $footerBtn3["color"] : '';

    $widthArray = ['xs', 'sm', 'md', 'lg', 'full'];

    $maxWidth = in_array($maxWidth, $widthArray) ? $maxWidth : 'md';

    $layout_btns = $footerBtn3 === false ? "flex-end" : "space-between;";

@endphp

<div id="modalBasic" class="modal-block mfp-hide zoom-anim-dialog modal-block-{{$maxWidth}}">
    <section class="card">
        <header class="card-header">
            <h2 class="card-title">{{$headerTitle}}</h2>
        </header>
        <div class="card-body">
            <div class="row">
                <div class="col-{{$maxWidth}}-12">
                    {{ $slot }}
                </div>
            </div>
        </div>
        <footer class="card-footer">
            <div class="row">
                <div class="col-md-12 text-end" style="display: flex; justify-content: {{$layout_btns}}">
                    @if(!!$footerBtn3)
                        <button class="btn btn-{{$colorBtn3}} modal-confirm" id="{{$idBtn3}}">
                            <span class="fas {{$iconBtn3}}" aria-hidden="true"></span>
                            {{$footerBtn3["label"]}}
                        </button>
                    @endif
                    <div style="display: flex;">
                        <button class="btn btn-{{$maxWidth}} me-2 btn-{{$colorBtn2}} modal-dismiss" id="{{$idBtn2}}">
                            <span class="fas {{$iconBtn2}}" aria-hidden="true"></span>
                            {{$footerBtn2["label"]}}
                        </button>
                        <button class="btn btn-{{$maxWidth}} btn-{{$colorBtn1}} modal-confirm" id="{{$idBtn1}}">
                            <span class="fas {{$iconBtn1}}" aria-hidden="true"></span>
                            {{$footerBtn1["label"]}}
                        </button>
                    </div>
                </div>
            </div>
        </footer>
    </section>
</div>