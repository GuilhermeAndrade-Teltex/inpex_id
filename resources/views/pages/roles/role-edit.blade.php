@vite(['resources/js/pages/role/role-edit.module.js'])

@extends('layouts.app')

@section('main')
<section role="main" class="content-body">
    @include('components.breadcrumbs')

    <div class="col-lg-12">
        <x-utils.container cardTitle="Editar Perfil" cardSubtitle="Informações do Perfil de Usuário">
            <form id="user_role_form" class="real-time-validation"
                data-validate-url="{{ route('usersRole.validateRequest') }}" enctype="multipart/form-data">
                @csrf

                <div class="row form-group">
                    <x-form_components.input name="name" label="Nome" type_input="text" form_group_width="6"
                        value="{{ old('name', $role->name) }}" />
                </div>

                <input id="id_profile" type="hidden" value="{{$id}}">
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
                            @foreach ($firstMenuPermissions as $menu1_key => $menu1_value)
                                <tr class="menu1" id="{{ $menu1_value['menu1_id'] }}">
                                    <input class="idFirstPermission {{ $menu1_key }}" type="hidden"
                                        value="{{ $menu1_value['permission_id'] }}">
                                    <td class="module">
                                        <span class="{{ $menu1_value['menu1_icon'] }}"></span>
                                        <span>{{ $menu1_value['menu1_name'] }}</span>
                                    </td>
                                    <td class="text-center"><input type="checkbox" class="mark-all"></td>
                                    <td class="text-center"><input type="checkbox" class="view" {{ $menu1_value['show'] == 1 ? 'checked' : '' }}></td>
                                    <td class="text-center"><input type="checkbox" class="edit" {{ $menu1_value['edit'] == 1 ? 'checked' : '' }}></td>
                                    <td class="text-center"><input type="checkbox" class="insert" {{ $menu1_value['create'] == 1 ? 'checked' : '' }}></td>
                                    <td class="text-center"><input type="checkbox" class="delete" {{ $menu1_value['destroy'] == 1 ? 'checked' : '' }}></td>
                                    <td class="text-center"><input type="checkbox" class="export" {{ $menu1_value['export'] == 1 ? 'checked' : '' }}></td>
                                    <td class="text-center"><input type="checkbox" class="access_log" {{ $menu1_value['access_log'] == 1 ? 'checked' : '' }}></td>
                                    <td class="text-center"><input type="checkbox" class="audit_log" {{ $menu1_value['audit_log'] == 1 ? 'checked' : '' }}></td>
                                </tr>
                                @foreach ($secondMenuPermissions as $menu2_key => $menu2_value)
                                    @if ($menu2_value['menus1_id'] == $menu1_value['menu1_id'])
                                        <tr class="menu2" id="{{ $menu1_value['menu1_id'] . '_' . $menu2_value['menu2_id']}}">
                                            <input class="idSecondPermission {{ $menu2_key }}" type="hidden"
                                                value="{{ $menu2_value['permission_id'] }}">
                                            <td class="module" style="padding-left: 20px;">
                                                <span class="{{ $menu2_value['menu2_icon'] }}"></span>
                                                <span>{{ $menu2_value['menu2_name'] }}</span>
                                            </td>
                                            <td class="text-center"><input type="checkbox" class="mark-all"></td>
                                            <td class="text-center"><input type="checkbox" class="view" {{ $menu2_value['show'] == 1 ? 'checked' : '' }}></td>
                                            <td class="text-center"><input type="checkbox" class="edit" {{ $menu2_value['edit'] == 1 ? 'checked' : '' }}></td>
                                            <td class="text-center"><input type="checkbox" class="insert" {{ $menu2_value['create'] == 1 ? 'checked' : '' }}></td>
                                            <td class="text-center"><input type="checkbox" class="delete" {{ $menu2_value['destroy'] == 1 ? 'checked' : '' }}></td>
                                            <td class="text-center"><input type="checkbox" class="export" {{ $menu2_value['export'] == 1 ? 'checked' : '' }}></td>
                                            <td class="text-center"><input type="checkbox" class="access_log" {{ $menu2_value['access_log'] == 1 ? 'checked' : '' }}></td>
                                            <td class="text-center"><input type="checkbox" class="audit_log" {{ $menu2_value['audit_log'] == 1 ? 'checked' : '' }}></td>
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
                            <x-utils.btn tagHtml="a" ref="{{ route('roles.show', $role) }}" color="default"
                                text="Cancelar" />
                        </div>
                    </div>
                </div>
            </form>
        </x-utils.container>
    </div>

    <div class="col-lg-12 user-card">
        <x-utils.container cardTitle="Usuários" cardSubtitle="Usuários vinculados a esse perfil.">
            <table class="table table-condensed table-bordered table-striped table-responsive">
                <thead>
                    <tr>
                        <th class="text-center align-items-center">Id</th>
                        <th class="text-center align-items-center">Foto</th>
                        <th class="text-center align-items-center">Status</th>
                        <th class="text-center align-items-center">Nome</th>
                        <th class="text-center align-items-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td class="text-center align-middle"><span>{{ $user['id'] }}</span></td>
                            <td class="text-center align-middle"><img src="{{ $user['profile_photo'] }}"
                                    alt="{{ $user['name']}}" id="user_profile_photo"></td>
                            <td class="text-center align-middle">
                                <btn class="bg-dark" id="status_btn">{{ $user['status'] }}</btn>
                            </td>
                            <td class="text-center align-middle"><span>{{ $user['name'] }}</span></td>
                            <td class="text-center align-middle"><a href="" class="btn btn-sm btn-dark removeRoleUser" data-user_id="{{ $user['id'] }}"><i
                                        class="fa fa-trash"></i></a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </x-utils.container>
    </div>
</section>
</div>
</section>
@endsection