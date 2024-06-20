@extends('layouts.app')

@section('main')
<section role="main" class="content-body">
    @include('components.breadcrumbs')

    <div class="col-lg-12">
        <x-utils.container cardTitle="Editar Cliente" cardSubtitle="Informações do Cliente">
            <form method="POST" action="{{ route('client.update', $client) }}" class="real-time-validation"
                data-validate-url="{{ route('client.validateRequest') }}">
                @csrf
                @method('PUT')

                <div class="row form-group pb-3">
                    <x-form_components.input name="name" label="Nome" type_input="text" form_group_width="6"
                        value="{{ old('name', $client->name) }}" />
                    <x-form_components.input name="cnpj" label="CNPJ" type_input="text" form_group_width="6"
                        value="{{ old('cnpj', $client->cnpj) }}" />
                </div>

                <div class="row form-group pb-3">
                    <x-form_components.input name="cep" label="CEP" type_input="text" form_group_width="6"
                        value="{{ old('cep', $client->cep) }}" />
                    <x-form_components.input name="address" label="Endereço" type_input="text" form_group_width="6"
                        value="{{ old('address', $client->address) }}" />
                </div>

                <div class="row form-group pb-3">
                    <x-form_components.input name="number" label="Número" type_input="text" form_group_width="2"
                        value="{{ old('number', $client->number) }}" />
                    <x-form_components.input name="complement" label="Complemento" type_input="text"
                        form_group_width="4" value="{{ old('complement', $client->complement) }}" />
                    <x-form_components.input name="district" label="Bairro" type_input="text" form_group_width="6"
                        value="{{ old('district', $client->district) }}" />
                </div>

                <div class="row form-group pb-3">
                    <x-form_components.input name="city" label="Cidade" type_input="text" form_group_width="6"
                        value="{{ old('city', $client->city) }}" />

                    <x-form_components.input name="state" label="Estado" type_input="text" form_group_width="2"
                        value="{{ old('state', $client->state) }}" />
                </div>

                <div class="row form-group pb-3">
                    <x-form_components.textarea name="observations" label="Observações" type_input="text"
                        form_group_width="12" value="{{ old('observations', $client->observations) }}" />
                </div>

                <div class="row justify-content-end">
                    <div class="col-lg-6">
                        <div class="text-end">
                            <x-utils.btn tagHtml="button" type="submit" color="primary" text="Salvar" />
                            <x-utils.btn tagHtml="a" ref="{{ route('client.show', $client) }}" color="default"
                                text="Cancelar" />
                        </div>
                    </div>
                </div>
            </form>
    </div>
</section>
</x-utils.container>
</div>
</section>
@endsection