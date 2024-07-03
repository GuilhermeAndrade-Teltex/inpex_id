@extends('layouts.app')
@section('main')
<section role="main" class="content-body">
    @include('components.breadcrumbs')
    <div class="col-lg-12">
        <x-utils.container cardTitle="Básico" cardSubtitle="Informações básicas do Usuário">
            <div class="row form-group pb-3">
                <div class="col-lg-6">
                    <div class="form-group row pb-4">
                        <label class="col-lg-4 control-label text-lg-end pt-1" for="name">Nome Completo:</label>
                        <div class="col-lg-6">
                            <p class="form-control-static mb-0">{{ $user->fullname }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group row pb-4">
                        <label class="col-lg-3 control-label text-lg-end pt-1" for="email">E-mail:</label>
                        <div class="col-lg-6">
                            <p class="form-control-static mb-0">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row form-group pb-3">
                <div class="col-lg-6">
                    <div class="form-group row pb-4">
                        <label class="col-lg-4 control-label text-lg-end pt-1" for="perfil">Perfil:</label>
                        <div class="col-lg-6">
                            <p class="form-control-static mb-0">{{ $user->role->name }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group row pb-4">
                        <label class="col-lg-3 control-label text-lg-end pt-1" for="cpf">CPF:</label>
                        <div class="col-lg-6">
                            <p class="form-control-static mb-0" id="cpf">{{ $user->cpf }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="card-footer text-end">
                @if ($allowedActions['edit'])
                    <x-utils.btn tagHtml="a" ref="{{ route('user.edit', ['id' => $user->id]) }}" color="primary"
                        text="Editar" />
                @endif
                <x-utils.btn tagHtml="a" ref="{{ route('user.index') }}" color="default" text="Cancelar" />
            </footer>
        </x-utils.container>
    </div>
</section>
</div>
</section>
@endsection