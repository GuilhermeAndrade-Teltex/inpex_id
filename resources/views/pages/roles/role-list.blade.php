@vite(['resources/js/pages/role/role-list.module.js'])

@extends('layouts.app')

@section('main')

<section role="main" class="content-body">
    @include('components.breadcrumbs')

    <div class="col-lg-12">
        <section class="card">
            <header class="card-header">
                <div class="card-actions">
                    <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                    <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
                </div>

                <h2 class="card-title">Perfis de Usuário</h2>
            </header>

            <div class="card-body">
                <div class="actions-bar">
                    <span class="label">Ações:</span>
                    @if ($allowedActions['create'])
                        <x-utils.btn ref="{{ route('roles.create') }}" tagHtml="a" color="dark" text="Criar Perfil"
                            icon="user-plus" size="sm" />
                    @endif

                    <!-- <x-utils.btn tagHtml="a" color="dark" text="Logs de Acessos" icon="file-alt" size="sm" />
                    <x-utils.btn tagHtml="a" color="dark" text="Histórico" icon="history" size="sm" /> -->
                </div>
                <table class="table table-bordered table-striped mb-0" id="datatable-tabletools">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Data de Criação</th>
                            <th>Data de Modificação</th>
                            <th>Criado por</th>
                            <th>Modificado por</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                            <tr>
                                <td>{{ $role->id }}</td>
                                <td>{{ \Carbon\Carbon::parse($role->created_at)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($role->updated_at)->format('d/m/Y') }}</td>
                                <td>{{ $role->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($role->modifiedBy)->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('roles.show', $role->id) }}" class="btn btn-sm btn-dark"><i
                                            class="fa fa-eye"></i></a>
                                    <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-dark"><i
                                            class="fa fa-pencil"></i></a>
                                    @if ($role->id != 1 && $role->id != 2)
                                        <a href="" class="btn btn-sm btn-dark" id="remove-role" data-role_id="{{$role->id}}"><i
                                                class="fa fa-trash"></i></a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</section>

@endsection