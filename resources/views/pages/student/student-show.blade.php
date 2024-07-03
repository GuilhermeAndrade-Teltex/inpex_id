@extends('layouts.app')

@section('main')
<section role="main" class="content-body">
    @include('components.breadcrumbs')

    <div class="col-lg-12">
        <x-utils.container cardTitle="Detalhes do Aluno" cardSubtitle="Informações do aluno">
            <div class="row form-group pb-3">
                <div class="col-lg-6">
                    <div class="form-group row pb-4">
                        <label class="col-lg-4 control-label text-lg-end pt-1" for="school">Escola:</label>
                        <div class="col-lg-8">
                            <p class="form-control-static mb-0">{{ $student->school->name }}</p>
                        </div>
                    </div>
                    <div class="form-group row pb-4">
                        <label class="col-lg-4 control-label text-lg-end pt-1" for="full_name">Nome
                            Completo:</label>
                        <div class="col-lg-8">
                            <p class="form-control-static mb-0">{{ $student->name }}</p>
                        </div>
                    </div>
                    <div class="form-group row pb-4">
                        <label class="col-lg-4 control-label text-lg-end pt-1" for="cpf">CPF:</label>
                        <div class="col-lg-8">
                            <p class="form-control-static mb-0">{{ $student->cpf }}</p>
                        </div>
                    </div>
                    <div class="form-group row pb-4">
                        <label class="col-lg-4 control-label text-lg-end pt-1" for="date_of_birth">Data de
                            Nascimento:</label>
                        <div class="col-lg-8">
                            <p class="form-control-static mb-0">{{ $student->date_of_birth }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group row pb-4">
                        <label class="col-lg-4 control-label text-lg-end pt-1" for="enrollment">Matrícula:</label>
                        <div class="col-lg-8">
                            <p class="form-control-static mb-0">{{ $student->enrollment }}</p>
                        </div>
                    </div>
                    <div class="form-group row pb-4">
                        <label class="col-lg-4 control-label text-lg-end pt-1" for="grade">Série:</label>
                        <div class="col-lg-8">
                            <p class="form-control-static mb-0">{{ $student->grade }}</p>
                        </div>
                    </div>
                    <div class="form-group row pb-4">
                        <label class="col-lg-4 control-label text-lg-end pt-1" for="class">Turma:</label>
                        <div class="col-lg-8">
                            <p class="form-control-static mb-0">{{ $student->class }}</p>
                        </div>
                    </div>
                    <div class="form-group row pb-4">
                        <label class="col-lg-4 control-label text-lg-end pt-1" for="cpf_responsible">CPF do
                            Responsável:</label>
                        <div class="col-lg-8">
                            <p class="form-control-static mb-0">{{ $student->cpf_responsible }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row form-group pb-3">
                <div class="col-lg-6">
                    <div class="form-group row pb-4">
                        <label class="col-lg-4 control-label text-lg-end pt-1" for="responsible_name">Nome do
                            Responsável:</label>
                        <div class="col-lg-8">
                            <p class="form-control-static mb-0">{{ $student->responsible_name }}</p>
                        </div>
                    </div>
                    <div class="form-group row pb-4">
                        <label class="col-lg-4 control-label text-lg-end pt-1" for="responsible_phone">Telefone do
                            Responsável:</label>
                        <div class="col-lg-8">
                            <p class="form-control-static mb-0">{{ $student->responsible_phone }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group row pb-4">
                        <label class="col-lg-4 control-label text-lg-end pt-1" for="responsible_email">Email do
                            Responsável:</label>
                        <div class="col-lg-8">
                            <p class="form-control-static mb-0">{{ $student->responsible_email }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row form-group pb-3">
                <div class="col-lg-12">
                    <div class="form-group row pb-4">
                        <label class="col-lg-2 control-label text-lg-end pt-1" for="observations">Observações:</label>
                        <div class="col-lg-10">
                            <p class="form-control-static mb-0">{{ $student->observations }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if ($studentImage)
                <div class="row form-group pb-3">
                    <div class="col-lg-12 text-center">
                        <img id="student_photo" src="{{ asset('storage/' . $studentImage->path_original) }}"
                            alt="Foto do Aluno">
                    </div>
                </div>
            @endif

            <footer class="card-footer text-end">
                @if ($allowedActions['edit'])
                    <x-utils.btn tagHtml="a" ref="{{ route('student.edit', $student) }}" color="primary" text="Editar" />
                @endif
                <x-utils.btn tagHtml="a" ref="{{ route('student.index') }}" color="default" text="Cancelar" />
            </footer>
        </x-utils.container>
    </div>
</section>
</div>
</section>
@endsection