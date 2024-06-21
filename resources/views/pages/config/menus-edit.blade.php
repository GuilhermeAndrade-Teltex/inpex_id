@extends('layouts.app')
@section('main')
<section role="main" class="content-body">
    @include('components.breadcrumbs')

    <div class="col-lg-12">
        <x-utils.container cardTitle="Editar Menu">
            <form method="POST" action="{{ route('menus.update', $menu1->id) }}">
                @csrf
                @method('PUT')

                <div class="menu1-item">
                    <h3>Menu 1</h3>
                    <input type="hidden" name="menu1[id]" value="{{ $menu1->id }}">
                    <x-form_components.input name="menu1[name]" label="Nome" id="menus1_name" type_input="text"
                        value="{{ $menu1->name }}" form_group_width="12" />
                    <x-form_components.input name="menu1[url]" label="URL" id="URL" type_input="text"
                        value="{{ $menu1->url }}" form_group_width="12" />
                    <x-form_components.input name="menu1[icon]" label="Ícone" id="menus1_icon" type_input="text"
                        value="{{ $menu1->icon }}" form_group_width="12" />
                    <x-form_components.input name="menu1[position]" label="Posição" id="menus1_position"
                        type_input="number" value="{{ $menu1->position }}" form_group_width="12" />

                    <div id="menus2-container">
                        @foreach ($menu1->menus2 as $index2 => $menu2)
                            <div class="menu2-item" data-menu2-id="{{ $menu2->id }}">
                                <h4>SubMenu {{ $index2 + 1 }}</h4>
                                <div class="form-group">
                                    <label for="menus1_menus2_{{ $index2 + 1 }}_name">Nome:</label>
                                    <input type="text" class="form-control" id="menus1_menus2_{{ $index2 + 1 }}_name"
                                        name="menu1[menus2][{{ $index2 }}][name]" value="{{ $menu2->name }}">
                                </div>
                                <div id="menus3-container-{{ $index2 }}">
                                    @foreach ($menu2->menus3 as $index3 => $menu3)
                                        <div class="menu3-item" data-menu3-id="{{ $menu3->id }}">
                                            <h5>SubMenu {{ $index3 + 1 }}</h5>
                                            <div class="form-group">
                                                <label
                                                    for="menus1_menus2_{{ $index2 + 1 }}_menus3_{{ $index3 + 1 }}_name">Nome:</label>
                                                <input type="text" class="form-control"
                                                    id="menus1_menus2_{{ $index2 + 1 }}_menus3_{{ $index3 + 1 }}_name"
                                                    name="menu1[menus2][{{ $index2 }}][menus3][{{ $index3 }}][name]"
                                                    value="{{ $menu3->name }}">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-secondary add-menu3" data-menu1-id="{{ $menu1->id }}"
                                    data-menu2-id="{{ $menu2->id }}">Adicionar Submenu</button>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" class="btn btn-secondary add-menu2" data-menu1-id="{{ $menu1->id }}">Adicionar
                        Submenu</button>
                </div>

                <x-utils.btn tagHtml="button" color="primary" id="add-menu1" text="Adicionar Menu" />
                <x-utils.btn tagHtml="button" type="submit" color="primary" text="Salvar" />
            </form>
        </x-utils.container>
    </div>
</section>
@endsection