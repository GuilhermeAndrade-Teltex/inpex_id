@vite(['resources/js/pages/role/role-create.module.js'])

@extends('layouts.app')

@section('main')
<section role="main" class="content-body">
    @include('components.breadcrumbs')

    <div class="col-lg-12">
        <x-utils.container cardTitle="Inserir Perfil" cardSubtitle="Informações do Perfil de Usuário">
            <input type="hidden" id="menus" data-menu1="{{ json_encode($menus1) }}"
                data-menu2="{{ json_encode($menus2) }}">
            <form id="user_role_form" class="real-time-validation"
                data-validate-url="{{ route('usersRole.validateRequest') }}" enctype="multipart/form-data">
                @csrf

                <div class="row form-group">
                    <x-form_components.input name="name" label="Nome" type_input="text" form_group_width="6" />
                </div>
                <div id="profile-roles-table" class="row form-group">
                    <table class="table table-condensed table-bordered table-striped table-responsive"
                        id="table_permissions">
                        <thead>
                            <tr>
                                <th class="text-center align-items-center">Módulos</th>
                                <th class="text-center align-items-center"><input type="checkbox" disabled></th>
                                <th class="text-center align-items-center"><span class="fas fa-eye fa-fw"
                                        title="Visualizar"></span><span> Visualizar</span></th>
                                <th class="text-center align-items-center"><span class="fas fa-pencil-alt fa-fw"
                                        title="Editar"></span><span> Editar</span></th>
                                <th class="text-center align-items-center"><span class="fas fa-plus fa-fw"
                                        title="Inserir"></span><span> Inserir</span></th>
                                <th class="text-center align-items-center"><span class="fas fa-trash fa-fw"
                                        title="Excluir"></span><span> Excluir</span></th>
                                <th class="text-center align-items-center"><span class="fas fa-file-export fa-fw"
                                        title="Exportar"></span><span> Exportar</span></th>
                                <th class="text-center align-items-center"><span class="fas fa-book-open fa-fw"
                                        title="Logs de Acesso"></span><span> Log de Acesso</span></th>
                                <th class="text-center align-items-center"><span class="fas fa-align-justify fa-fw"
                                        title="Histórico"></span><span> Histórico</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($menus1 as $menu1)
                                <tr class="menu1" id="{{ $menu1['id'] }}">
                                    <td class="module">
                                        <span class="{{ $menu1['icon'] }}"></span>
                                        <span>{{ $menu1['name'] }}</span>
                                    </td>
                                    <td class="text-center"><input type="checkbox" class="mark-all"></td>
                                    <td class="text-center"><input type="checkbox" class="view"></td>
                                    <td class="text-center"><input type="checkbox" class="edit"></td>
                                    <td class="text-center"><input type="checkbox" class="insert">
                                    </td>
                                    <td class="text-center"><input type="checkbox" class="delete">
                                    </td>
                                    <td class="text-center"><input type="checkbox" class="export">
                                    </td>
                                    <td class="text-center"><input type="checkbox" class="access_log"></td>
                                    <td class="text-center"><input type="checkbox" class="audit_log"></td>
                                </tr>
                                @foreach ($menus2 as $menu2)
                                    @if ($menu2['menus1_id'] == $menu1['id'])
                                        <tr class="menu2" id="{{ $menu1['id'] . '_' . $menu2['id']}}">
                                            <td class="module" style="padding-left: 20px;">
                                                <span class="{{ $menu2['icon'] }}"></span>
                                                <span>{{ $menu2['name'] }}</span>
                                            </td>
                                            <td class="text-center"><input type="checkbox" class="mark-all"></td>
                                            <td class="text-center"><input type="checkbox" class="view">
                                            </td>
                                            <td class="text-center"><input type="checkbox" class="edit">
                                            </td>
                                            <td class="text-center"><input type="checkbox" class="insert"></td>
                                            <td class="text-center"><input type="checkbox" class="delete"></td>
                                            <td class="text-center"><input type="checkbox" class="export"></td>
                                            <td class="text-center"><input type="checkbox" class="access_log">
                                            </td>
                                            <td class="text-center"><input type="checkbox" class="audit_log">
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="row justify-content-end">
                    <div class="col-lg-6">
                        <div class="text-end">
                            <x-utils.btn tagHtml="button" type="submit" color="primary" text="Salvar"
                                id="usersRoleBtn" />
                            <x-utils.btn tagHtml="a" ref="{{ route('roles.index') }}" color="default" text="Cancelar" />
                        </div>
                    </div>
                </div>
            </form>
        </x-utils.container>
    </div>
</section>
@endsection