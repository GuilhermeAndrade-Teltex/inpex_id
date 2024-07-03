@extends('layouts.app')

@section('main')
<section role="main" class="content-body">
    @include('components.breadcrumbs')

    <div class="col-lg-12">
        <x-utils.container cardTitle="Detalhes do Cliente" cardSubtitle="Informações do cliente">
            <div class="row form-group pb-3">
                <div class="col-lg-6">
                    <div class="form-group row pb-4">
                        <label class="col-lg-4 control-label text-lg-end pt-1" for="name">Nome:</label>
                        <div class="col-lg-8">
                            <p class="form-control-static mb-0">{{ $client->name }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group row pb-4">
                        <label class="col-lg-4 control-label text-lg-end pt-1" for="cnpj">CNPJ:</label>
                        <div class="col-lg-8">
                            <p class="form-control-static mb-0">{{ $client->cnpj }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row form-group pb-3">
                <div class="col-lg-6">
                    <div class="form-group row pb-4">
                        <label class="col-lg-4 control-label text-lg-end pt-1" for="cep">CEP:</label>
                        <div class="col-lg-8">
                            <p class="form-control-static mb-0">{{ $client->cep }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group row pb-4">
                        <label class="col-lg-4 control-label text-lg-end pt-1" for="address">Endereço:</label>
                        <div class="col-lg-8">
                            <p class="form-control-static mb-0">{{ $client->address }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row form-group pb-3">
                <div class="col-lg-6">
                    <div class="form-group row pb-4">
                        <label class="col-lg-4 control-label text-lg-end pt-1" for="number">Número:</label>
                        <div class="col-lg-8">
                            <p class="form-control-static mb-0">{{ $client->number }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group row pb-4">
                        <label class="col-lg-4 control-label text-lg-end pt-1" for="complement">Complemento:</label>
                        <div class="col-lg-8">
                            <p class="form-control-static mb-0">{{ $client->complement }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row form-group pb-3">
                <div class="col-lg-6">
                    <div class="form-group row pb-4">
                        <label class="col-lg-4 control-label text-lg-end pt-1" for="district">Bairro:</label>
                        <div class="col-lg-8">
                            <p class="form-control-static mb-0">{{ $client->district }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group row pb-4">
                        <label class="col-lg-4 control-label text-lg-end pt-1" for="city">Cidade:</label>
                        <div class="col-lg-8">
                            <p class="form-control-static mb-0">{{ $client->city }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row form-group pb-3">
                <div class="col-lg-6">
                    <div class="form-group row pb-4">
                        <label class="col-lg-4 control-label text-lg-end pt-1" for="state">Estado:</label>
                        <div class="col-lg-8">
                            <p class="form-control-static mb-0">{{ $client->state }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group row pb-4">
                        <label class="col-lg-4 control-label text-lg-end pt-1" for="observations">Observações:</label>
                        <div class="col-lg-8">
                            <p class="form-control-static mb-0">{{ $client->observations }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="card-footer text-end">
                @if ($allowedActions['edit'])
                    <x-utils.btn tagHtml="a" color="primary" text="Editar" ref="{{ route('client.edit', $client) }}" />
                @endif
                <x-utils.btn tagHtml="a" color="default" text="Cancelar" ref="{{ route('client.index') }}" />
            </footer>
        </x-utils.container>
    </div>
</section>
</div>
</section>
@endsection