@extends('layout.template')
@section('content')
    <div class="row">
        <div class="col-lg-4 col-md-4 order-1">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between">
                                    <div class="avatar flex-shrink-0">
                                        <img src="{{ asset('assets/img/icons/unicons/matricula.png') }}" alt="Credit Card"
                                            class="rounded">
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn p-0" type="button" id="cardOpt6" data-bs-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt6">
                                            <a class="dropdown-item" href="javascript:void(0);">Ver Mas</a>
                                        </div>
                                    </div>
                                </div>
                                <span>Matrículas</span>
                                <h3 class="card-title text-nowrap mb-1">0</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ asset('assets/img/icons/unicons/tesoreria.png') }}" alt="Credit Card"
                                        class="rounded">
                                </div>
                                <div class="dropdown">
                                    <button class="btn p-0" type="button" id="cardOpt6" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt6">
                                        <a class="dropdown-item" href="javascript:void(0);">Ver Mas</a>
                                    </div>
                                </div>
                            </div>
                            <span>Tesorería</span>
                            <h3 class="card-title text-nowrap mb-1">S/ 0</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 order-1">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">

                                    <img src="{{ asset('assets/img/icons/unicons/docente.png') }}" alt="Credit Card"
                                        class="rounded">
                                </div>
                                <div class="dropdown">
                                    <button class="btn p-0" type="button" id="cardOpt6" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt6">
                                        <a class="dropdown-item" href="javascript:void(0);">Ver Mas</a>
                                    </div>
                                </div>
                            </div>
                            <span>Docentes</span>
                            <h3 class="card-title text-nowrap mb-1">0</h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ asset('assets/img/icons/unicons/curso.png') }}" alt="Credit Card"
                                        class="rounded">
                                </div>
                                <div class="dropdown">
                                    <button class="btn p-0" type="button" id="cardOpt6" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt6">
                                        <a class="dropdown-item" href="javascript:void(0);">Ver Mas</a>
                                    </div>
                                </div>
                            </div>
                            <span>Cursos</span>
                            <h3 class="card-title text-nowrap mb-1">0</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 order-1">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ asset('assets/img/icons/unicons/alumno.png') }}" alt="Credit Card"
                                        class="rounded">
                                </div>
                                <div class="dropdown">
                                    <button class="btn p-0" type="button" id="cardOpt6" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt6">
                                        <a class="dropdown-item" href="javascript:void(0);">Ver Mas</a>
                                    </div>
                                </div>
                            </div>
                            <span>Alumnos</span>
                            <h3 class="card-title text-nowrap mb-1">0</h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ asset('assets/img/icons/unicons/padres.png') }}" alt="Credit Card"
                                        class="rounded">
                                </div>
                                <div class="dropdown">
                                    <button class="btn p-0" type="button" id="cardOpt6" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt6">
                                        <a class="dropdown-item" href="javascript:void(0);">Ver Mas</a>
                                    </div>
                                </div>
                            </div>
                            <span>Padres</span>
                            <h3 class="card-title text-nowrap mb-1">0</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 col-md-6">
            <div class="card overflow-hidden mb-4">
                <div class="card-img-top"
                    style="background-color: #696cff; height: 11rem; text-align: center; display: flex; flex-direction: column; justify-content: center; align-items: center;">
                    <img src="{{ auth()->user()->profilePicture }}" alt="" class="rounded-circle mb-2"
                        style="width: 100px; height: 100px;">
                    <h4 style="color: white" class="mb-1"><em>{{ auth()->user()->username }}</em></h4>
                    <h6 style="color: white" class="mb-0">
                        <em>{{ auth()->user()->userRoles()->first()->role->name }}</em>
                    </h6>
                </div>

                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-6">Usuario</div>
                            <div class="col-6">{{ auth()->user()->username }}</div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-6">Email</div>
                            <div class="col-6">{{ auth()->user()->email }}</div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-6">Teléfono</div>
                            <div class="col-6">{{ auth()->user()->phoneNumber }}</div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-12">
            <div class="card mb-4">
                <div class="card-body text-center"> <!-- Agrega la clase "text-center" aquí -->
                    <div class="card-title align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <img src="{{ asset('assets/img/icons/unicons/periodo1.png') }}" alt="Academic Period"
                                class="rounded">
                        </div>
                    </div>
                    <h6 class="text-center">Periodo académico</h6>
                    <h6 class="text-center">activo</h6>
                    <h3 class="card-title text-nowrap mb-2 text-center" style="color:#696cff">PA-2023</h3>
                </div>
            </div>
        </div>



        <div class="col-lg-6 col-md-4 col-sm-12 mb-3">
            <div class="card overflow-hidden mb-4">
                <h5 class="card-header">Agenda</h5>

                <div class="table-responsive text-nowrap ps ps--active-y" id="vertical-example">
                    <table class="table">
                        <thead class="table-light">
                            <tr>
                                <th width="20%">{{ $diaActual }}</th>
                                <th width="75%"></th>
                                <th width="5%">{{ $fechaActual }}</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <tr>
                                <td>
                                    <strong>08:00am - 09:30am </strong>
                                </td>
                                <td>Reunión</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="ps__rail-x" style="left: 0px; bottom: -300px;">
                    <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
                </div>
                <div class="ps__rail-y" style="top: 300px; height: 232px; right: 0px;">
                    <div class="ps__thumb-y" tabindex="0" style="top: 59px; height: 45px;"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
@endsection

@section('js')
    <script src="{{ asset('assets/js/extended-ui-perfect-scrollbar.js') }}"></script>
@endsection
