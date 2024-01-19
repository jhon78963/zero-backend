@php
    $roleName = auth()
        ->user()
        ->userRoles()
        ->first()->role->name;
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('auth.home') }}" class="app-brand-link">
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
        @if ($roleName == 'Admin' || $roleName == 'Secretaria')
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Administración</span>
            </li>
        @endif

        @if ($roleName == 'Admin')
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-dock-top"></i>
                    <div data-i18n="Account Settings">Control de Acceso</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('roles.index') }}" class="menu-link">
                            <div data-i18n="Account">Roles</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('users.index') }}" class="menu-link">
                            <div data-i18n="Notifications">Usuarios</div>
                        </a>
                    </li>
                </ul>
            </li>
        @endif

        @if ($roleName == 'Admin' || $roleName == 'Secretaria')
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-dock-top"></i>
                    <div data-i18n="Account Settings">Entidades</div>
                </a>
                <ul class="menu-sub">

                    <li class="menu-item">
                        <a href="{{ route('students.index') }}" class="menu-link">
                            <div data-i18n="Account">Estudiantes</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('teachers.index') }}" class="menu-link">
                            <div data-i18n="Notifications">Docentes</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('secretaries.index') }}" class="menu-link">
                            <div data-i18n="Notifications">Secretarias</div>
                        </a>
                    </li>

                </ul>
            </li>
        @endif

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Gestión administrativa</span>
        </li>

        @if ($roleName == 'Admin' || $roleName == 'Secretaria')
            <li class="menu-item">
                <a href="{{ route('periods.index') }}" class="menu-link">
                    <div data-i18n="Account">Periodo Académico</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('calendars.index', $academic_period->name) }}" class="menu-link">
                    <div data-i18n="Account">Calendario Académico</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('class-room.index', $academic_period->name) }}" class="menu-link">
                    <div data-i18n="Account">Aulas</div>
                </a>
            </li>

            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-dock-top"></i>
                    <div data-i18n="Account Settings">Carga Horaria</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('courses.index', $academic_period->name) }}" class="menu-link">
                            <div data-i18n="Account">Cursos</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('workload.teacher', $academic_period->name) }}" class="menu-link">
                            <div data-i18n="Account">Docente</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('workload.index', $academic_period->name) }}" class="menu-link">
                            <div data-i18n="Notifications">Horario</div>
                        </a>
                    </li>
                </ul>
            </li>
        @endif

        @if ($roleName == 'Admin' || $roleName == 'Secretaria')
            <li class="menu-item">
                <a href="{{ route('silabus.index', $academic_period->name) }}" class="menu-link">
                    <div data-i18n="Account">Sílabus</div>
                </a>
            </li>
        @endif

        @if ($roleName == 'Docente')
            <li class="menu-item">
                <a href="{{ route('teacher.silabus.index', $academic_period->name) }}" class="menu-link">
                    <div data-i18n="Account">Sílabus</div>
                </a>
            </li>
        @endif

        @if ($roleName == 'Admin' || $roleName == 'Secretaria')
            <li class="menu-item">
                <a href="{{ route('school-registration.index', $academic_period->name) }}" class="menu-link">
                    <div data-i18n="Account">Matriculas</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('treasuries.index', $academic_period->name) }}" class="menu-link">
                    <div data-i18n="Account">Tesorería</div>
                </a>
            </li>
        @endif

        @if ($roleName == 'Docente')
            <li class="menu-item">
                <a href="{{ route('workload.schedule.teacher', $academic_period->name) }}" class="menu-link">
                    <div data-i18n="Account">Horario</div>
                </a>
            </li>

            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Gestión académica</span>
            </li>
            <li class="menu-item">
                <a href="{{ route('attendance.teacher.index', $academic_period->name) }}" class="menu-link">
                    <div data-i18n="Account">Asistencias</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="#" class="menu-link">
                    <div data-i18n="Account">Notas</div>
                </a>
            </li>
        @endif

        @if ($roleName == 'Estudiante')
            <li class="menu-item">
                <a href="{{ route('workload.schedule.student', $academic_period->name) }}" class="menu-link">
                    <div data-i18n="Account">Horario</div>
                </a>
            </li>

            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Gestión académica</span>
            </li>
            <li class="menu-item">
                <a href="#" class="menu-link">
                    <div data-i18n="Account">Asistencias</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="#" class="menu-link">
                    <div data-i18n="Account">Notas</div>
                </a>
            </li>
        @endif
    </ul>
</aside>
