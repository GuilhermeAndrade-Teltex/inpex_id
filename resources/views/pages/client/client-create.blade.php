@extends('layouts.app')

@section('main')
<section role="main" class="content-body">
    @include('components.breadcrumbs')

    <div class="col-lg-12">
    <x-utils.container cardTitle="Novo Cliente" cardSubtitle="Informações do Cliente">
        <form method="POST" action="{{ route('client.store') }}" class="real-time-validation"
            data-validate-url="{{ route('client.validateRequest') }}">
            @csrf

            <div class="row form-group pb-3">
                <x-form_components.input name="name" label="Nome" type_input="text"
                    form_group_width="6" />

                <x-form_components.input name="cnpj" label="CNPJ" type_input="text"
                    form_group_width="6" />
            </div>

            <div class="row form-group pb-3">
                <x-form_components.input name="cep" label="CEP" type_input="text"
                    form_group_width="6" />

                <x-form_components.input name="address" label="Endereço" type_input="text"
                    form_group_width="6" />
            </div>

            <div class="row form-group pb-3">
                <x-form_components.input name="number" label="Número" type_input="text"
                    form_group_width="2" />

                <x-form_components.input name="complement" label="Complemento" type_input="text"
                    form_group_width="4" />

                <x-form_components.input name="district" label="Bairro" type_input="text"
                    form_group_width="6" />
            </div>

            <div class="row form-group pb-3">
                <x-form_components.input name="city" label="Cidade" type_input="text"
                    form_group_width="6" />

                <x-form_components.input name="state" label="Estado" type_input="text"
                    form_group_width="2" />
            </div>

            <div class="row form-group pb-3">
                <x-form_components.textarea name="observations" label="Observações"
                    form_group_width="12" />
            </div>

            <div class="row justify-content-end">
                <div class="col-lg-6">
                    <div class="text-end">
                        <x-utils.btn tagHtml="button" type="submit" color="primary" text="Salvar" />
                        <x-utils.btn tagHtml="a" ref="{{ route('client.index') }}" color="default" text="Cancelar" />
                    </div>
                </div>
            </div>
        </form>
    </x-utils.container>
    </div>
</section>
@endsection