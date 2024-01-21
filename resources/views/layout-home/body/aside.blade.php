@php
    $roleName = auth()
        ->user()
        ->userRoles()
        ->first()->role->name;
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('auth.home.principal') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ asset('loginn/img/icono.png') }}" alt="" class="w-px-40 h-auto rounded-circle">
            </span>
            <span class="app-brand-text demo menu-text fw-bolder ms-2">San Gerardo</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        @if ($roleName == 'Admin' || $roleName == 'Secretaria' || $roleName == 'Dirección Académica')
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Administración</span>
            </li>
        @endif

        @if ($roleName == 'Admin' || $roleName == 'Dirección Académica')
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-dock-top"></i>
                    <div data-i18n="Account Settings">Control de Acceso</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('roles.home.index') }}" class="menu-link">
                            <div data-i18n="Account">Roles</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('users.home.index') }}" class="menu-link">
                            <div data-i18n="Notifications">Usuarios</div>
                        </a>
                    </li>
                </ul>
            </li>
        @endif

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Gestión administrativa</span>
        </li>

        @if ($roleName == 'Admin' || $roleName == 'Dirección Académica')
            <li class="menu-item">
                <a href="{{ route('calendars.home.index') }}" class="menu-link">
                    <div data-i18n="Account">Calendario Académico</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('periods.home.index') }}" class="menu-link">
                    <div data-i18n="Account">Periodo Académico</div>
                </a>
            </li>
        @endif
    </ul>
</aside>
