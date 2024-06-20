@props([
    'name',
    'label',
    'type_input' => 'text',
    'form_group_width' => '6',
    'value' => false
])

@php
    $value = !!$value ? old($name, $value) : old($name);
@endphp

<div class="col-lg-{{$form_group_width}}">
    <div class="form-group">
        <label class="col-form-label" for="{{$name}}">{{$label}}</label>
        <input type="{{$type_input}}" class="form-control @error($name) is-invalid @enderror" id="{{$name}}" name="{{$name}}"
            value="{{ $value }}" autofocus>
        @error($name)
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>