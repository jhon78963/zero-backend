@extends('layout-home.template')
@section('content')
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
            @foreach ($academic_periods as $period)
                <div class="card mb-4 p-1">
                    <div class="card-body text-center"> <!-- Agrega la clase "text-center" aquí -->
                        <div class="card-title align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ asset('assets/img/icons/unicons/periodo.png') }}" alt="Academic Period"
                                    class="rounded">
                            </div>
                        </div>
                        <h6 class="text-center">Periodo académico</h6>
                        <h6 class="text-center">activo</h6>
                        <h3 class="card-title text-nowrap mb-2 text-center" style="color:#696cff">
                            {{ $period->name }}</h3>
                        <a href="{{ route('periods.home', $period->name) }}" class="btn rounded-pill btn-primary">Ir</a>
                    </div>
                </div>
            @endforeach
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
