@vite(['resources/js/pages/student/student-edit.module.js'])

@extends('layouts.app')

@section('main')
<section role="main" class="content-body">
    @include('components.breadcrumbs')

    <div class="col-lg-12">
        <x-utils.container cardTitle="Editar Aluno" cardSubtitle="Informações do Aluno">
            <form method="POST" action="{{ route('student.update', $student->id) }}" enctype="multipart/form-data"
                class="real-time-validation" data-validate-url="{{ route('student.validateRequest') }}">
                @csrf
                @method('PUT')

                <div class="row form-group pb-3">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="col-form-label" for="school_id">Escola</label>
                            <select class="form-control @error('school_id') is-invalid @enderror" id="school_id"
                                name="school_id">
                                <option value="" disabled>-- Selecione --</option>
                                @foreach ($schools as $school)
                                    <option value="{{ $school->id }}" {{ $student->school_id == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                                @endforeach
                            </select>
                            @error('school_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row form-group pb-3">
                    <x-form_components.input name="name" label="Nome Completo" type_input="text" form_group_width="6"
                        value="{{ old('name', $student->name) }}" />
                    <x-form_components.input name="cpf" label="CPF" type_input="text" form_group_width="6"
                        value="{{ old('cpf', $student->cpf) }}" />
                </div>

                <div class="row form-group pb-3">
                    <x-form_components.input name="date_of_birth" label="Data de Nascimento" type_input="date"
                        form_group_width="6" value="{{ old('date_of_birth', $student->date_of_birth) }}" />
                    <x-form_components.input name="enrollment" label="Matrícula" type_input="text" form_group_width="6"
                        value="{{ old('enrollment', $student->enrollment) }}" />
                </div>

                <div class="row form-group pb-3">
                    <x-form_components.input name="grade" label="Série" type_input="text" form_group_width="6"
                        value="{{ old('grade', $student->grade) }}" />
                    <x-form_components.input name="class" label="Turma" type_input="text" form_group_width="6"
                        value="{{ old('class', $student->class) }}" />
                </div>

                <div class="row form-group pb-3">
                    <x-form_components.input name="education_level" label="Nível de Educação" type_input="text"
                        form_group_width="6" value="{{ old('education_level', $student->education_level) }}" />
                    <x-form_components.input name="responsible_name" label="Nome do Responsável" type_input="text"
                        form_group_width="6" value="{{ old('responsible_name', $student->responsible_name) }}" />
                </div>

                <div class="row form-group pb-3">
                    <x-form_components.input name="responsible_phone" label="Telefone do
                                        Responsável" type_input="text" form_group_width="6"
                        value="{{ old('responsible_phone', $student->responsible_phone) }}" />
                    <x-form_components.input name="responsible_email" label="Email do Responsável" type_input="text"
                        form_group_width="6" value="{{ old('responsible_email', $student->responsible_email) }}" />
                </div>

                <div class="row form-group pb-3">
                    <x-form_components.input name="cep" label="CEP" type_input="text" form_group_width="6"
                        value="{{ old('cep', $student->cep) }}" />
                </div>

                <div class="row form-group pb-3">
                    <x-form_components.input name="address" label="Endereço" type_input="text" form_group_width="10"
                        value="{{ old('address', $student->address) }}" />
                    <x-form_components.input name="number" label="Número" type_input="text" form_group_width="2"
                        value="{{ old('number', $student->number) }}" />
                </div>

                <div class="row form-group pb-3">
                    <x-form_components.input name="complement" label="Complemento" type_input="text"
                        form_group_width="6" value="{{ old('complement', $student->complement) }}" />
                    <x-form_components.input name="district" label="Bairro" type_input="text" form_group_width="6"
                        value="{{ old('district', $student->district) }}" />
                </div>

                <div class="row form-group pb-3">
                    <x-form_components.input name="city" label="Cidade" type_input="text" form_group_width="6"
                        value="{{ old('city', $student->city) }}" />
                    <x-form_components.input name="state" label="Estado" type_input="text" form_group_width="6"
                        value="{{ old('state', $student->state) }}" />
                </div>

                <div class="row form-group pb-3">
                    <x-form_components.textarea name="observations" label="Observações" type_input="text"
                        form_group_width="12" value="{{ old('observations', $student->observations) }}" />
                </div>

                <!-- Campo de upload de foto -->
                <div class="row form-group pb-3">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="col-form-label" for="photo">Foto do Aluno</label>
                            <input type="file" class="form-control @error('photo') is-invalid @enderror" id="fileInput"
                                name="photo">
                            @error('photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="video-container">
                                <video id="myVideo" width="320" height="240" autoplay></video>
                                <canvas id="overlay"></canvas>
                                <img id="capturedImage" width="320" height="240" style="display: none;" />
                            </div>
                            <div style="display: flex; padding-top: 20px;">
                                <a href="javascript:void(0);" class="btn btn-default" id="myBtn">Ligar
                                    Câmera</a>
                                <a href="javascript:void(0);" class="btn btn-default" id="takePhoto">Tirar
                                    Foto</a>
                            </div>
                            <input type="hidden" id="capturedImageData" name="capturedImageData">
                        </div>
                    </div>
                </div>

                <div class="row justify-content-end">
                    <div class="col-lg-6">
                        <div class="text-end">
                            <x-utils.btn tagHtml="button" type="submit" color="primary" text="Salvar" />
                            <x-utils.btn tagHtml="a" ref="{{ route('student.show', $student) }}" color="default"
                                text="Cancelar" />
                        </div>
                    </div>
                </div>
            </form>
        </x-utils.container>
    </div>
</section>
@endsection

<!-- Modal para Seleção de Câmera -->
<div class="modal" id="cameraModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Selecionar Câmera</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul id="cameraList" class="list-group">
                    <!-- Lista de Câmeras será preenchida aqui -->
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>