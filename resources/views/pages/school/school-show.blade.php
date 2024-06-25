@extends('layouts.app')

@section('main')
<section role="main" class="content-body">
    @include('components.breadcrumbs')

    <div class="col-lg-12">
        <x-utils.container cardTitle="Detalhes da Escola" cardSubtitle="Informações da escola">
            <div class="row form-group pb-3">
                <div class="col-lg-6">
                    <div class="form-group row pb-4">
                        <label class="col-lg-4 control-label text-lg-end pt-1" for="client">Cliente:</label>
                        <div class="col-lg-8">
                            <p class="form-control-static mb-0">{{ $school->client->name }}</p>
                        </div>
                    </div>
                    <div class="form-group row pb-4">
                        <label class="col-lg-4 control-label text-lg-end pt-1" for="name">Nome:</label>
                        <div class="col-lg-8">
                            <p class="form-control-static mb-0">{{ $school->name }}</p>
                        </div>
                    </div>
                    <div class="form-group row pb-4">
                        <label class="col-lg-4 control-label text-lg-end pt-1" for="education_level">Regional:</label>
                        <div class="col-lg-8">
                            <p class="form-control-static mb-0">{{ $school->regional }}</p>
                        </div>
                    </div>
                    <div class="form-group row pb-4">
                        <label class="col-lg-4 control-label text-lg-end pt-1" for="responsible">Responsável:</label>
                        <div class="col-lg-8">
                            <p class="form-control-static mb-0">{{ $school->responsible }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group row pb-4">
                        <label class="col-lg-4 control-label text-lg-end pt-1" for="cep">CEP:</label>
                        <div class="col-lg-8">
                            <p class="form-control-static mb-0">{{ $school->cep }}</p>
                        </div>
                    </div>
                    <div class="form-group row pb-4">
                        <label class="col-lg-4 control-label text-lg-end pt-1" for="address">Endereço:</label>
                        <div class="col-lg-8">
                            <p class="form-control-static mb-0">{{ $school->address }}</p>
                        </div>
                    </div>
                    <div class="form-group row pb-4">
                        <label class="col-lg-4 control-label text-lg-end pt-1" for="number">Número:</label>
                        <div class="col-lg-8">
                            <p class="form-control-static mb-0">{{ $school->number }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row form-group pb-3">
                <div class="col-lg-6">
                    <div class="form-group row pb-4">
                        <label class="col-lg-4 control-label text-lg-end pt-1" for="complement">Complemento:</label>
                        <div class="col-lg-8">
                            <p class="form-control-static mb-0">{{ $school->complement }}</p>
                        </div>
                    </div>
                    <div class="form-group row pb-4">
                        <label class="col-lg-4 control-label text-lg-end pt-1" for="district">Bairro:</label>
                        <div class="col-lg-8">
                            <p class="form-control-static mb-0">{{ $school->district }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group row pb-4">
                        <label class="col-lg-4 control-label text-lg-end pt-1" for="city">Cidade:</label>
                        <div class="col-lg-8">
                            <p class="form-control-static mb-0">{{ $school->city }}</p>
                        </div>
                    </div>
                    <div class="form-group row pb-4">
                        <label class="col-lg-4 control-label text-lg-end pt-1" for="state">Estado:</label>
                        <div class="col-lg-8">
                            <p class="form-control-static mb-0">{{ $school->state }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row form-group pb-3">
                <div class="col-lg-12">
                    <div class="form-group row pb-4">
                        <label class="col-lg-2 control-label text-lg-end pt-1" for="observations">Observações:</label>
                        <div class="col-lg-10">
                            <p class="form-control-static mb-0">{{ $school->observations }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="card-footer text-end">
                <x-utils.btn tagHtml="a" ref="{{ route('school.edit', ['id' => $school->id]) }}" color="primary"
                    text="Editar" />
                <x-utils.btn tagHtml="a" ref="{{ route('school.index') }}" color="default" text="Cancelar" />
            </footer>
        </x-utils.container>
    </div>
</section>
</div>
</section>
@endsection