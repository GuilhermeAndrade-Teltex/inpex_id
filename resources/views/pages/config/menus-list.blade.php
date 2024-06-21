@extends('layouts.app')
@section('main')

<section role="main" class="content-body">
    @include('components.breadcrumbs')

    <div class="col-lg-12">
        <section class="card">
            <header class="card-header">
                <h2 class="card-title">Lista de Menus</h2>
            </header>
            <div class="card-body">
                <table class="table table-bordered table-striped mb-0" id="datatable-tabletools">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tipo Menu</th>
                            <th>Nome do Menu</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($menus1 as $menu1)
                            <tr class="submenu-row">
                                <td>{{ $menu1->id }}</td>
                                <td>Menu 1</td>
                                <td>{{ $menu1->name }}</td>
                                <td>
                                    @if(isset($allowedActions) && isset($allowedMenus[$menu1->id]))
                                        @if(in_array($menu1->id, array_column($allowedMenus[$menu1->id], 'id')))
                                            @if($allowedActions['show'])
                                                <a href="{{ route('menus.show', $menu1->id) }}" class="btn btn-sm btn-info"><i class="fa fa-eye"></i></a>
                                            @endif
                                            @if($allowedActions['edit'])
                                                <a href="{{ route('menus.edit', $menu1->id) }}" class="btn btn-sm btn-warning"><i class="fa fa-pencil"></i></a>
                                            @endif
                                            @if($allowedActions['destroy'])
                                                <form action="{{ route('menus.destroy', $menu1->id) }}" method="POST" style="display: inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                                                </form>
                                            @endif
                                        @endif
                                    @endif
                                </td>
                            </tr>

                            @if ($menu1->menus2->isNotEmpty())
                                @foreach ($menu1->menus2 as $menu2)
                                    <tr class="submenu-row">
                                        <td>{{ $menu2->id }}</td>
                                        <td>Menu 2</td>
                                        <td class="pl-1">{{ $menu2->name }}</td>
                                        <td>
                                            @if(isset($allowedActions) && isset($allowedMenus[$menu1->id]['submenus'][$menu2->id]))
                                                @if(in_array($menu2->id, array_column($allowedMenus[$menu1->id]['submenus'][$menu2->id], 'id')))
                                                    @if($allowedActions['show'])
                                                        <a href="{{ route('submenus.show', $menu2->id) }}" class="btn btn-sm btn-info"><i class="fa fa-eye"></i></a>
                                                    @endif
                                                    @if($allowedActions['edit'])
                                                        <a href="{{ route('submenus.edit', $menu2->id) }}" class="btn btn-sm btn-warning"><i class="fa fa-pencil"></i></a>
                                                    @endif
                                                    @if($allowedActions['destroy'])
                                                        <form action="{{ route('submenus.destroy', $menu2->id) }}" method="POST" style="display: inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                                                        </form>
                                                    @endif
                                                @endif
                                            @endif
                                        </td>
                                    </tr>

                                    @if ($menu2->menus3->isNotEmpty())
                                        @foreach ($menu2->menus3 as $menu3)
                                            <tr class="submenu-row">
                                                <td>{{ $menu3->id }}</td>
                                                <td>Menu 3</td>
                                                <td class="pl-2">{{ $menu3->name }}</td>
                                                <td>
                                                    @if(isset($allowedActions) && isset($allowedMenus[$menu1->id]['submenus'][$menu2->id]['submenus'][$menu3->id]))
                                                        @if(in_array($menu3->id, array_column($allowedMenus[$menu1->id]['submenus'][$menu2->id]['submenus'], 'id')))
                                                            @if($allowedActions['show'])
                                                                <a href="{{ route('subsubmenus.show', $menu3->id) }}" class="btn btn-sm btn-info"><i class="fa fa-eye"></i></a>
                                                            @endif
                                                            @if($allowedActions['edit'])
                                                                <a href="{{ route('subsubmenus.edit', $menu3->id) }}" class="btn btn-sm btn-warning"><i class="fa fa-pencil"></i></a>
                                                            @endif
                                                            @if($allowedActions['destroy'])
                                                                <form action="{{ route('subsubmenus.destroy', $menu3->id) }}" method="POST" style="display: inline-block;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                                                                </form>
                                                            @endif
                                                        @endif
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</section>

@endsection
