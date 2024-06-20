@extends('layouts.app')
@section('main')
<section role="main" class="content-body">
    @include('components.breadcrumbs')
    <div class="col-lg-12">
        <x-utils.container cardTitle="Básico" cardSubtitle="Informações básicas do Usuário">
            <form method="POST" action="{{ route('user.store') }}" class="real-time-validation"
                data-validate-url="{{ route('user.validateRequest') }}">
                @csrf
                <div class="row form-group pb-3">
                    <x-form_components.input name="name" label="Nome Completo" type_input="text" form_group_width="6" />
                    <x-form_components.input name="email" label="E-mail" type_input="email" form_group_width="6" />
                </div>
                <div class="row form-group pb-3">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="col-form-label" for="perfil">Perfil</label>
                            <div class="col-sm-12">
                                <select id="perfil" name="perfil" data-plugin-selectTwo
                                    class="form-control populate @error('perfil') is-invalid @enderror"
                                    title="Selecione um perfil">
                                    <option value="" disabled selected>-- Selecione --</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}" {{ old('perfil') == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('perfil')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <x-form_components.input name="cpf" label="CPF" type_input="cpf" form_group_width="6" />
                </div>
                <div class="row justify-content-end">
                    <div class="col-lg-6">
                        <div class="text-end">
                            <x-utils.btn tagHtml="button" type="submit" color="primary" text="Salvar" />
                            <x-utils.btn tagHtml="a" ref="{{ route('user.index') }}" color="default" text="Cancelar" />
                        </div>
                    </div>
                </div>
            </form>
        </x-utils.container>
    </div>
</section>
</div>
</section>
@endsection