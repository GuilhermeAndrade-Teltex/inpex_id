@extends('layouts.app')

@section('main')
<section role="main" class="content-body">
    @include('components.breadcrumbs')

    <div class="col-lg-12">
        <x-utils.container cardTitle="Editar Escola" cardSubtitle="Informações da Escola">
            <form method="POST" action="{{ route('school.update', $school) }}" class="real-time-validation"
                data-validate-url="{{ route('school.validateRequest') }}">
                @csrf
                @method('PUT')

                <div class="row form-group pb-3">

                    <x-form_components.select name="client_id" label="Cliente" form_group_width="6">
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id', $school->client_id) == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                        @endforeach
                    </x-form_components.select>

                    <x-form_components.input name="name" label="Nome" type_input="text" form_group_width="6"
                        value="{{ old('name', $school->name) }}" />
                </div>

                <div class="row form-group pb-3">
                    <x-form_components.input name="responsible" label="Responsável" type_input="text"
                    form_group_width="6" value="{{ old('responsible', $school->responsible) }}" />
                    <x-form_components.input name="regional" label="Regional" type_input="text"
                        form_group_width="6" value="{{ old('regional', $school->regional) }}" />
                </div>

                <div class="row form-group pb-3">
                    <x-form_components.input name="cep" label="CEP" type_input="text" form_group_width="6"
                        value="{{ old('cep', $school->cep) }}" />
                </div>

                <div class="row form-group pb-3">
                    <x-form_components.input name="address" label="Endereço" type_input="text" form_group_width="6"
                        value="{{ old('number', $school->number) }}" />
                    <x-form_components.input name="number" label="Número" type_input="text" form_group_width="2"
                        value="{{ old('number', $school->number) }}" />
                    <x-form_components.input name="complement" label="Complemento" type_input="text"
                        form_group_width="4" value="{{ old('complement', $school->complement) }}" />
                </div>

                <div class="row form-group pb-3">
                    <x-form_components.input name="district" label="Bairro" type_input="text" form_group_width="6"
                        value="{{ old('district', $school->district) }}" />
                    <x-form_components.input name="city" label="Cidade" type_input="text" form_group_width="4"
                        value="{{ old('city', $school->city) }}" />
                    <x-form_components.input name="state" label="Estado" type_input="text" form_group_width="2"
                        value="{{ old('state', $school->state) }}" />
                </div>

                <div class="row form-group pb-3">
                    <x-form_components.textarea name="observations" label="Observações" type_input="text"
                        form_group_width="12" value="{{ old('observations', $school->observations) }}" />
                </div>

                <div class="row justify-content-end">
                    <div class="col-lg-6">
                        <div class="text-end">
                            <x-utils.btn tagHtml="button" type="submit" color="primary" text="Salvar" />
                            <x-utils.btn tagHtml="a" ref="{{ route('school.show', ['id' => $school->id]) }}" color="default"
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