@extends('layouts.app')

@section('main')
<section role="main" class="content-body">
    @include('components.breadcrumbs')

    <div class="col-lg-12">
        <x-utils.container cardTitle="Nova Escola" cardSubtitle="Informações da Escola">
            <form method="POST" action="{{ route('school.store') }}" class="real-time-validation"
                data-validate-url="{{ route('school.validateRequest') }}">
                @csrf

                <div class="row form-group pb-3">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="col-form-label" for="client_id">Cliente</label>
                            <select class="form-control @error('client_id') is-invalid @enderror" id="client_id"
                                name="client_id">
                                <option value="" selected disabled>-- Selecione --</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </select>
                            @error('client_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <x-form_components.input name="name" label="Nome" type_input="text" form_group_width="6" />
                </div>

                <div class="row form-group pb-3">
                    <x-form_components.input name="responsible" label="Responsável" type_input="text"
                        form_group_width="6" />
                    <x-form_components.input name="regional" label="Regional" type_input="text" form_group_width="6" />
                </div>

                <div class="row form-group pb-3">
                    <x-form_components.input name="cep" label="CEP" type_input="text" form_group_width="6" />
                </div>

                <div class="row form-group pb-3">
                    <x-form_components.input name="address" label="Endereço" type_input="text" form_group_width="6" />
                    <x-form_components.input name="number" label="Número" type_input="text" form_group_width="2" />
                    <x-form_components.input name="complement" label="Complemento" type_input="text"
                        form_group_width="4" />
                </div>

                <div class="row form-group pb-3">
                    <x-form_components.input name="district" label="Bairro" type_input="text" form_group_width="6" />
                    <x-form_components.input name="city" label="Cidade" type_input="text" form_group_width="4" />
                    <x-form_components.input name="state" label="Estado" type_input="text" form_group_width="2" />
                </div>

                <div class="row form-group pb-3">
                    <x-form_components.textarea name="observations" label="Observações" type_input="text"
                        form_group_width="12" />
                </div>

                <div class="row justify-content-end">
                    <div class="col-lg-6">
                        <div class="text-end">
                            <x-utils.btn tagHtml="button" type="submit" color="primary" text="Salvar" />
                            <x-utils.btn tagHtml="a" ref="{{ route('school.index') }}" color="default"
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