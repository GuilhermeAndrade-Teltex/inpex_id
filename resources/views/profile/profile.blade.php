@extends('layouts.app')
@section('main')
<section role="main" class="content-body">
    @include('components.breadcrumbs')

    <div class="col-lg-12">
        <x-utils.container cardTitle="Meu Perfil" cardSubtitle="Dados do Usuário">
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data"
                class="real-time-validation" data-validate-url="{{ route('profile.validateRequest') }}">
                @csrf
                @method('PUT')
                <div class="row form-group pb-3">
                    <x-form_components.input name="fullname" label="Nome Completo" type_input="text"
                        form_group_width="12" value="{{ old('fullname', $user->fullname) }}" />
                </div>
                <div class="row form-group pb-3">
                    <x-form_components.input name="email" label="Email" type_input="Email" form_group_width="6"
                        value="{{ old('email', $user->email) }}" />
                    <x-form_components.input name="password" label="Senha" type_input="password" form_group_width="6"
                        value="{{ old('password', $user->password) }}" />
                </div>
                <div class="row form-group pb-3">
                    <x-form_components.input name="date_created" label="Data de Criação" type_input="text"
                        form_group_width="6" value="{{ old('date_created', $user->date_created) }}" readonly="true" />
                    <x-form_components.input name="date_modified" label="Data de Modificação" type_input="text"
                        form_group_width="6" value="{{ old('date_modified', $user->date_modified) }}" readonly="true" />
                </div>
                <div class="row form-group pb-3">
                    <x-form_components.input name="created_by" label="Criado Por" type_input="text" form_group_width="6"
                        value="{{ old('created_by', $user->created_by) }}" readonly="true" />
                    <x-form_components.input name="modified_by" label="Modificado Por" type_input="text"
                        form_group_width="6" value="{{ old('modified_by', $user->modified_by) }}" readonly="true" />
                </div>
                <div class="row form-group pb-3">
                    <x-form_components.input name="role_id" label="Perfil" type_input="text" form_group_width="6"
                        value="{{ old('role_id', $role->name) }}" readonly="true" />
                    <x-form_components.input name="cpf" label="CPF" type_input="text" form_group_width="6"
                        value="{{ old('cpf', $user->cpf) }}" readonly="true" />
                </div>
                <div class="row justify-content-end">
                    <div class="col-lg-6">
                        <div class="text-end">
                            <x-utils.btn tagHtml="a" ref="{{ route('dashboard') }}" color="default" text="Cancelar" />
                            <x-utils.btn tagHtml="button" type="submit" color="primary" text="Salvar" />
                        </div>
                    </div>
                </div>
            </form>
        </x-utils.container>
    </div>
</section>
@endsection