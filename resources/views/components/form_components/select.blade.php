@props([
    'name',
    'label',
    'form_group_width' => '6',
])

<div class="col-lg-{{$form_group_width}}">
    <div class="form-group">
        <label class="col-form-label" for="{{$name}}">Escola</label>
        <select class="form-control @error($name) is-invalid @enderror" id="{{$name}}"
            name="{{$name}}">
            <option value="" selected disabled>-- Selecione --</option>

            {{$slot}}
        </select>
        @error($name)
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>