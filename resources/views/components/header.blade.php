<!-- start: header -->
<header class="header">
    <div class="logo-container">
        <a href="{{ route('dashboard') }}" class="logo">
            <img src="{{ asset('images/logos/logo-wide.jpg') }}" width="120" height="45" alt="Teltex Tecnologia" />
        </a>
        <div class="d-md-none toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html"
            data-fire-event="sidebar-left-opened">
            <i class="fas fa-bars" aria-label="Toggle sidebar"></i>
        </div>
    </div>
    <!-- start: search & user box -->
    <div class="header-right">
        <span class="separator"></span>
        @include('components.perfil')
    </div>
    <!-- end: search & user box -->
</header>
<!-- end: header -->
