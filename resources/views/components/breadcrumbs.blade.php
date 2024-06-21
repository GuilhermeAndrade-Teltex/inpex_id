<header class="page-header">
    <h2>{{ isset($pageTitle) ? $pageTitle : 'Dashboard' }}</h2>
    <div class="right-wrapper text-end">
        <ol class="breadcrumbs">
            @isset($breadcrumbs)
                @foreach ($breadcrumbs as $breadcrumb)
                    <li>
                        <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['name'] }}</a>
                    </li>
                @endforeach
            @endisset
        </ol>
        <a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fas fa-chevron-left"></i></a>
    </div>
</header>
