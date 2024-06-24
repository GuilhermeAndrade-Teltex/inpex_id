@vite(['resources/js/pages/student/student-create.module.js'])

@extends('layouts.app')

@section('main')
<section role="main" class="content-body">
    @include('components.breadcrumbs')

    <div class="col-lg-12">
        <x-utils.container cardTitle="Novo Aluno" cardSubtitle="Informações do Aluno">
            <form method="POST" action="{{ route('student.store') }}" class="real-time-validation"
                data-validate-url="{{ route('student.validateRequest') }}" enctype="multipart/form-data">
                @csrf

                <div class="row form-group pb-3">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="col-form-label" for="school_id">Escola</label>
                            <select class="form-control @error('school_id') is-invalid @enderror" id="school_id"
                                name="school_id">
                                <option value="" selected disabled>-- Selecione --</option>
                                @foreach ($schools as $school)
                                    <option value="{{ $school->id }}">{{ $school->name }}</option>
                                @endforeach
                            </select>
                            @error('school_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row form-group pb-3">
                    <x-form_components.input name="name" label="Nome Completo" type_input="text" form_group_width="6" />
                    <x-form_components.input name="cpf" label="CPF" type_input="text" form_group_width="6" />
                </div>
                <div class="row form-group pb-3">
                    <x-form_components.input name="date_of_birth" label="Data de Nascimento" type_input="date"
                        form_group_width="6" />
                    <x-form_components.input name="enrollment" label="Matrícula" type_input="text"
                        form_group_width="6" />
                </div>

                <div class="row form-group pb-3">
                    <x-form_components.input name="grade" label="Série" type_input="text" form_group_width="6" />
                    <x-form_components.input name="class" label="Turma" type_input="text" form_group_width="6" />
                </div>

                <div class="row form-group pb-3">
                    <x-form_components.input name="responsible_name" label="Nome do Responsável" type_input="text"
                        form_group_width="6" />
                    <x-form_components.input name="cpf_responsible" label="CPF do Responsável" type_input="text"
                        form_group_width="6" />
                </div>

                <div class="row form-group pb-3">
                    <x-form_components.input name="responsible_phone" label="Telefone do
                                        Responsável" type_input="text" form_group_width="6" />
                    <x-form_components.input name="responsible_email" label="Email do Responsável" type_input="text"
                        form_group_width="6" />
                </div>

                <div class="row form-group pb-3">
                    <x-form_components.input name="cep" label="CEP" type_input="text" form_group_width="6" />
                </div>

                <div class="row form-group pb-3">
                    <x-form_components.input name="address" label="Endereço" type_input="text" form_group_width="10" />
                    <x-form_components.input name="number" label="Número" type_input="text" form_group_width="2" />
                </div>

                <div class="row form-group pb-3">
                    <x-form_components.input name="complement" label="Complemento" type_input="text"
                        form_group_width="6" />
                    <x-form_components.input name="district" label="Bairro" type_input="text" form_group_width="6" />
                </div>

                <div class="row form-group pb-3">
                    <x-form_components.input name="city" label="Cidade" type_input="text" form_group_width="6" />
                    <x-form_components.input name="state" label="Estado" type_input="text" form_group_width="6" />
                </div>

                <div class="row form-group pb-3">
                    <x-form_components.textarea name="observations" label="Observações" type_input="text"
                        form_group_width="12" />
                </div>

                <div class="row form-group pb-3">
                    <div class="col-lg-12">
                        <x-form_components.input name="photo" label="Foto do Aluno" type_input="file"
                            form_group_width="12" />
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

                <div class="row justify-content-end">
                    <div class="col-lg-6">
                        <div class="text-end">
                            <x-utils.btn tagHtml="button" type="submit" color="primary" text="Salvar" />
                            <x-utils.btn tagHtml="a" ref="{{ route('student.index') }}" color="default"
                                text="Cancelar" />
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