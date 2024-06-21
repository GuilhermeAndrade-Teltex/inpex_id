@extends('layouts.app')

@section('main')
<section role="main" class="content-body">
    @include('components.breadcrumbs')

    <div class="col-lg-12">
        <section class="card">
            <header class="card-header">
                <div class="card-actions">
                    <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                </div>

                <h2 class="card-title">Detalhes do Perfil</h2>

                <p class="card-subtitle">
                    Informações do perfil de usuário
                </p>
            </header>

            <div class="card-body">
                <div class="row form-group pb-3">
                    <div class="col-lg-6">
                        <div class="form-group row pb-4">
                            <label class="col-lg-6 control-label text-lg-end pt-1" for="name">Nome:</label>
                            <div class="col-lg-6">
                                <p class="form-control-static mb-0">{{ $usersRole->name }}</p>
                            </div>
                        </div>
                        <div class="form-group row pb-4">
                            <label class="col-lg-6 control-label text-lg-end pt-1" for="date_created">Data de
                                Criação:</label>
                            <div class="col-lg-6">
                                <p class="form-control-static mb-0">{{ $usersRole->date_created }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group row pb-4">
                            <label class="col-lg-6 control-label text-lg-end pt-1" for="date_modified">Data de
                                Modificação:</label>
                            <div class="col-lg-6">
                                <p class="form-control-static mb-0">{{ $usersRole->date_modified }}</p>
                            </div>
                        </div>
                        <div class="form-group row pb-4">
                            <label class="col-lg-6 control-label text-lg-end pt-1" for="created_by">Criado por:</label>
                            <div class="col-lg-6">
                                <p class="form-control-static mb-0">{{ $usersRole->createdBy }}</p>
                            </div>
                        </div>
                        <div class="form-group row pb-4">
                            <label class="col-lg-6 control-label text-lg-end pt-1" for="modified_by">Modificado
                                por:</label>
                            <div class="col-lg-6">
                                <p class="form-control-static mb-0">{{ $usersRole->modifiedBy }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <footer class="card-footer text-end">
                    <x-primary-button>
                        <a href="{{ route('roles.edit', $usersRole) }}">Editar</a>
                    </x-primary-button>
                    <x-utils.btn tagHtml="a" ref="{{ route('roles.index') }}" color="default" text="Cancelar" />
                </footer>
            </div>
        </section>
    </div>
</section>
@endsection