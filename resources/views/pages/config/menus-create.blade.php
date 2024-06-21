@extends('layouts.app') 
@section('main')
<section role="main" class="content-body">
    @include('components.breadcrumbs')

    <div class="col-lg-12">
        <x-utils.container cardTitle="Criar Menus">
            <form method="POST" action="{{ route('menus.store') }}">
                @csrf
                <div id="menus1-container">
                </div>

                <x-utils.btn tagHtml="button" color="primary" text="Adicionar Menu" id="add-menu1" />
                <x-utils.btn tagHtml="button" type="submit" color="primary" text="Salvar" />
            </form>
        </x-utils.container>
    </div>
</section>
</div>
</section>
@endsection