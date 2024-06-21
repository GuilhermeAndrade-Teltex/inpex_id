@extends('layouts.app')
@section('main')

<?php //xdebug_break(); ?>

<section role="main" class="content-body">
    @include('components.breadcrumbs')
    <div class="col-lg-12">
        <section class="card">
            <header class="card-header">
                <div class="card-actions">
                    <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                    <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
                </div>

                <h2 class="card-title">Watchlists</h2>
            </header>
            <div class="card-body">
                <table class="table table-bordered table-striped mb-0" id="datatable-tabletools">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tipo de Watchlist</th>
                            <th>Nome do Watchlist</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data_watchlists['data'] as $watchlists) 
                            @foreach ($watchlists as $watchlist)
                                <tr>
                                    <td>{{ $watchlist['watchlist_id'] }}</td>
                                    <td>{{ $watchlist['watchlist_type'] }}</td>
                                    <td>{{ $watchlist['display_name'] }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</section>
@endsection