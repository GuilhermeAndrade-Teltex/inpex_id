@props([
    'cardTitle',
    'cardSubtitle' => '',
])

<section class="card">
    <header class="card-header">
        <div class="card-actions">
            <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
        </div>

        <h2 class="card-title">{{$cardTitle}}</h2>

        <p class="card-subtitle">
            {{$cardSubtitle}}
        </p>
    </header>

    <div class="card-body">
        {{ $slot }}
    </div>
</section>