@extends('layouts.app')

@section('main')
<section role="main" class="content-body">
    @include('components.breadcrumbs')

    <div class="col-lg-12">
        <x-utils.container cardTitle="Inserir Perfil" cardSubtitle="Informações do Perfil de Usuário">
            <form method="POST" action="{{ route('roles.create') }}">
                @csrf

                <div class="row form-group">
                    <x-form_components.input name="name" label="Nome" type_input="text" form_group_width="6" />
                </div>

                <div class="row justify-content-end">
                    <div class="col-lg-6">
                        <div class="text-end">
                            <x-utils.btn tagHtml="button" type="submit" color="primary" text="Salvar" />
                            <x-utils.btn tagHtml="a" ref="{{ route('roles.index') }}" color="default" text="Cancelar" />
                        </div>
                    </div>
                </div>
            </form>
        </x-utils.container>
    </div>
</section>
@endsection