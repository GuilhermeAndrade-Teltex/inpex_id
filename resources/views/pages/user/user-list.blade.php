@vite(['resources/js/pages/user/user-list.module.js'])

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

                <h2 class="card-title">Usuários</h2>
            </header>
            <div class="card-body">
                <div class="actions-bar">
                    <span class="label">Ações:</span>
                    @if ($allowedActions['create'])
                        <x-utils.btn ref="{{ route('user.create') }}" tagHtml="a" color="dark" text="Criar Usuário"
                            icon="user-plus" size="sm" />
                    @endif

                    <!-- <x-utils.btn tagHtml="a" color="dark" text="Logs de Acessos" icon="file-alt" size="sm" /> -->
                    <!-- <x-utils.btn tagHtml="a" color="dark" text="Histórico" icon="history" size="sm" /> -->
                </div>
                <table class="table table-bordered table-striped mb-0" id="datatable-tabletools">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Data de Criação</th>
                            <th>Perfil</th>
                            <th>Nome Completo</th>
                            <th>E-mail</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ \Carbon\Carbon::parse($user->date_created)->format('d/m/Y') }}</td>
                                <td>{{ $user->role_id }}</td>
                                <td><a class="link"
                                        href="{{ route('user.show', ['id' => $user->id]) }}">{{ $user->fullname }}</a></td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if ($allowedActions['show'])
                                        <a href="{{ route('user.show', $user->id) }}" class="btn btn-sm btn-dark"><i
                                                class="fa fa-eye"></i></a>
                                    @endif
                                    @if ($allowedActions['edit'])
                                        <a href="{{ route('user.edit', $user->id) }}" class="btn btn-sm btn-dark"><i
                                                class="fa fa-pencil"></i></a>
                                    @endif
                                    @if ($allowedActions['destroy'])
                                        <a class="btn btn-sm btn-dark" id="remove-user" data-user_id="{{$user->id}}"><i
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