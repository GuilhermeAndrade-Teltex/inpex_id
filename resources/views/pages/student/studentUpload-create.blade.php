@vite(['resources/js/studentUpload-create.js'])

@extends('layouts.app')
@section('main')
<section role="main" class="content-body">
    @include('components.breadcrumbs')

    <div class="col-lg-12">
        <x-utils.container cardTitle="Carregamento de Alunos" cardSubtitle="Carregue as fotos dos alunos">
            <form class="dropzone real-time-validation" method="POST" id="upload-form"
                action="{{ route('studentImage.store') }}">
                @csrf
                <div class="row form-group pb-3">
                    <x-form_components.select name="school_id" label="Escola" form_group_width="6">
                        @foreach ($schools as $school)
                            <option value="{{ $school->id }}">{{ $school->name }}</option>
                        @endforeach 
                    </x-form_components.select>

                    <x-form_components.input name="class" label="Turma" type_input="text" form_group_width="6" />
                </div>
                <!-- Loading -->

                <x-app_components.spinner_loading loading="hide" />
            </form>
            <div class="row justify-content-end">
                <div class="col-lg-6">
                    <div class="text-end">
                        <x-utils.btn tagHtml="button" type="submit" color="primary" text="Salvar"
                            id="saveImageButton" />
                        <x-utils.btn tagHtml="a" ref="{{ route('student.index') }}" color="default" text="Cancelar" />
                    </div>
                </div>
            </div>
        </x-utils.container>
        <div class="card hidden" id="card_images">
            <header class="card-header">
                <div class="card-actions">
                    <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                </div>
                <h2 class="card-title">Imagens carregadas</h2>
                <p class="card-subtitle">
                    Fotos carregadas dos alunos
                </p>
            </header>
            <div class="card-body previews">
                <div id="datatable-container">
                </div>
            </div>
        </div>
    </div>
</section>
@endsection