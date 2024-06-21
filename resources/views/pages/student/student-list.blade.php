@vite(['resources/js/pages/student/student-list.module.js'])

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

                <h2 class="card-title">Alunos</h2>
            </header>
            <div class="card-body">
                <div class="actions-bar">
                    <span class="label">Ações:</span>
                    @if ($allowedActions['create'])
                        <x-utils.btn ref="{{ route('student.create') }}" tagHtml="a" color="dark" text="Criar Aluno"
                            icon="user-plus" size="sm" />
                    @endif

                    <x-utils.btn ref="{{ route('studentImage.create') }}" tagHtml="a" color="dark"
                        text="Carregar Alunos" icon="file-alt" size="sm" />

                    <!-- <x-utils.btn tagHtml="a" color="dark" text="Logs de Acessos" icon="file-alt" size="sm" />

                    <x-utils.btn tagHtml="a" color="dark" text="Histórico" icon="history" size="sm" /> -->
                </div>

                <table class="table table-bordered table-striped mb-0" id="datatable-tabletools">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Data de Criação</th>
                            <th>Nome</th>
                            <th>CPF</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $student)
                            <tr>
                                <td>{{ $student->id }}</td>
                                <td>{{ \Carbon\Carbon::parse($student->created_at)->format('d/m/Y') }}</td>
                                <td>{{ $student->name }}</td>
                                <td><a class="link"
                                        href="{{ route('student.show', ['id' => $student->id]) }}">{{ $student->cpf }}</a>
                                </td>
                                <td>
                                    <a href="{{ route('student.show', $student->id) }}" class="btn btn-sm btn-dark"><i
                                            class="fa fa-eye"></i></a>
                                    <a href="{{ route('student.edit', $student->id) }}" class="btn btn-sm btn-dark"><i
                                            class="fa fa-pencil"></i></a>
                                    <a href="" class="btn btn-sm btn-dark" id="remove-student" data-student_id="{{$student->id}}"><i class="fa fa-trash"></i></a>
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