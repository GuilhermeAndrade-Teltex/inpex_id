@extends('layouts.app')

@section('main')
<section role="main" class="content-body">
    @include('components.breadcrumbs')
    <div class="col-lg-12">
        <x-utils.container cardTitle="Editar Usuário" cardSubtitle="Informações básicas do Usuário">
            <form method="POST" action="{{ route('user.update', $user->id) }}" class="real-time-validation"
                data-validate-url="{{ route('user.validateRequest') }}">
                @csrf
                @method('PUT')
                <div class="row
                        form-group pb-3">
                    <!-- Campo: Nome Completo -->
                    <x-form_components.input name="name" label="Nome Completo" type_input="text" form_group_width="6"
                        value="{{ old('name', $user->name) }}" />
                    <!-- Campo: Perfil -->
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="col-form-label" for="perfil">Perfil</label>
                            <select id="perfil" name="perfil"
                                class="form-control @error('perfil') is-invalid @enderror">
                                <option value="" disabled selected>-- Selecione --</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('perfil', $user->role_id) == $role->id ? 'selected' : '' }}> {{ $role->name }}</option>
                                @endforeach
                            </select>
                            @error('perfil')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Botões de Ação -->
                <div class="row justify-content-end">
                    <div class="col-lg-6">
                        <div class="text-end">
                            <x-utils.btn tagHtml="button" type="submit" color="primary" text="Salvar" />
                            <x-utils.btn tagHtml="a" ref="{{ route('user.show', $user->id) }}" color="default" text="Cancelar" />
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