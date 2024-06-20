<nav id="menu" class="nav-main" role="navigation">
    <ul class="nav nav-main">
        @isset($allowedMenus)
            @foreach($allowedMenus as $menuId => $menuData)
                @php
                    $menu = $menuData['menu'];
                    $hasSubmenus = !empty($menuData['submenus']);
                    $menuUrl = empty($menu->url) ? '#' : url($menu->url);
                @endphp
                <li @if($hasSubmenus) class="nav-parent" @endif>
                    <a class="nav-link" href="{{ $menuUrl }}">
                        <i class="{{ $menu->icon }}" aria-hidden="true"></i>
                        <span>{{ $menu->name }}</span>
                    </a>
                    @if ($hasSubmenus)
                        <ul class="nav nav-children">
                            @foreach($menuData['submenus'] as $submenuData)
                                @php
                                    $submenu = $submenuData['menu'];
                                    $submenuUrl = empty($submenu->url) ? '#' : url($submenu->url);
                                @endphp
                                <li>
                                    <a class="nav-link" href="{{ $submenuUrl }}">
                                        <i class="{{ $submenu->icon }}" aria-hidden="true"></i>
                                        <span>{{ $submenu->name }}</span>
                                    </a>
                                    @empty (!$submenuData['submenus'])
                                        <ul class="nav nav-children">
                                            @foreach($submenuData['submenus'] as $submenu3)
                                                @php
                                                    $submenu3Url = empty($submenu3->url) ? '#' : url($submenu3->url);
                                                @endphp
                                                <li>
                                                    <a class="nav-link" href="{{ $submenu3Url }}">
                                                     <i class="{{ $submenu3->icon }}" aria-hidden="true"></i>
                                                        <span>{{ $submenu3->name }}</span>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endempty
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        @endisset
    </ul>
</nav>
