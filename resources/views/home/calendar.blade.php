@extends('layout-home.template')

@section('title')
    Calendario
@endsection

@section('content')
    <div class="card mb-4" style="padding-right: 1rem">
        <div class="d-flex align-items-center justify-content-between ">
            <h5 class="card-header">Calendario</h5>

            <form action="{{ route('calendars.home.index') }}">
                <div class="navbar-nav align-items-center">
                    <div class="nav-item d-flex align-items-center">
                        <input type="text" name="search" class="form-control border-search shadow-none"
                            placeholder="Buscar..." style="width: 500px;">
                        <button class="p-0 btn btn-default">
                            <i class="bx bx-search fs-4 lh-0"></i>
                        </button>
                    </div>
                </div>
            </form>

            <button type="button" class="btn rounded-pill btn-icon btn-outline-primary me-4" data-bs-toggle="modal"
                data-bs-target="#modalCreateCalendar">
                <span class="tf-icons bx bx-list-plus"></span>
            </button>
        </div>
    </div>

    <div class="modal fade" id="modalCreateCalendar" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('calendars.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1">Registrar Actividad</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col mb-3">
                                <label for="TenantId" class="form-label">Periodo académico</label>
                                <select name="TenantId" id="TenantId" class="form-control">
                                    <option value="">-</option>
                                    @foreach ($academic_periods as $period)
                                        <option value="{{ $period->id }}">{{ $period->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="c_responsible_person" class="form-label">Responsable</label>
                                <select name="responsible_person" id="c_responsible_person" class="form-control">
                                    <option value="">Seleccione ...</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="name" class="form-label">Actividad</label>
                                <input type="text" id="c_activity" name="activity" class="form-control"
                                    placeholder="Detalla en que consitirá la actividad" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <label for="end" class="form-label">Inicio</label>
                                <input type="date" id="c_start" name="start" class="form-control" />
                            </div>
                            <div class="col-6">
                                <label for="end" class="form-label">Fin</label>
                                <input type="date" id="c_end" name="end" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Cerrar
                        </button>
                        <button type="submit" class="btn btn-primary" id="btnCreateUser">Registrar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="text-nowrap mt-1">
            <table class="table" id="tabla-roles">
                <thead>
                    <tr>
                        <th width="60%">Actividad (Responsable)</th>
                        <th class="text-center">Duración</th>
                        <th class="text-center">Inicio</th>
                        <th class="text-center">Fin</th>
                        <th class="text-center">Opciones</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($academic_calendars as $academic_calendar)
                        <tr>
                            <td>{{ $academic_calendar->activity }}
                                <strong>({{ $academic_calendar->role->name }})</strong>
                            </td>
                            <td class="text-center">{{ $academic_calendar->duration_activity }}</td>
                            <td class="text-center">{{ $academic_calendar->start }}</td>
                            <td class="text-center">{{ $academic_calendar->end }}</td>
                            <td>
                                <div class="text-center">
                                    <button type="button" class="btn rounded-pill btn-icon btn-outline-primary me-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEditCalendar{{ $academic_calendar->id }}">
                                        <span class="tf-icons bx bx-edit-alt"></span>
                                    </button>

                                    <button type="button" class="btn rounded-pill btn-icon btn-outline-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalDeleteCalendar{{ $academic_calendar->id }}">
                                        <span class="tf-icons bx bx-trash"></span>
                                    </button>
                                </div>

                                <div class="modal fade" id="modalEditCalendar{{ $academic_calendar->id }}"
                                    data-backdrop="static" data-keyboard="false" tabindex="-1"
                                    aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <form action="{{ route('calendars.update', $academic_calendar->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel1">Editar Actividad
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col mb-3">
                                                            <label for="TenantId" class="form-label">Periodo
                                                                académico</label>
                                                            <select name="TenantId" id="TenantId" class="form-control">
                                                                <option value="">-</option>
                                                                @foreach ($academic_periods as $period)
                                                                    <option value="{{ $period->id }}"
                                                                        {{ $period->id == $academic_calendar->TenantId ? 'selected' : '' }}>
                                                                        {{ $period->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col mb-3">
                                                            <label for="name" class="form-label">Responsable</label>
                                                            <select name="responsible_person" id="c_responsible_person"
                                                                class="form-control">
                                                                <option value="">Seleccione ...</option>
                                                                @foreach ($roles as $role)
                                                                    <option value="{{ $role->id }}"
                                                                        {{ $role->id == $academic_calendar->responsible_person ? 'selected' : '' }}>
                                                                        {{ $role->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col mb-3">
                                                            <label for="name" class="form-label">Actividad</label>
                                                            <input type="text" id="c_activity" name="activity"
                                                                class="form-control"
                                                                value="{{ $academic_calendar->activity }}"
                                                                placeholder="Detalla en que consitirá la actividad" />
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <label for="end" class="form-label">Inicio</label>
                                                            <input type="date" id="c_start" name="start"
                                                                class="form-control"
                                                                value="{{ $academic_calendar->start }}" />
                                                        </div>
                                                        <div class="col-6">
                                                            <label for="end" class="form-label">Fin</label>
                                                            <input type="date" id="c_end" name="end"
                                                                class="form-control"
                                                                value="{{ $academic_calendar->end }}" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary"
                                                        data-bs-dismiss="modal">
                                                        Cerrar
                                                    </button>
                                                    <button type="submit" class="btn btn-primary"
                                                        id="btnCreateUser">Actualizar</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="modal fade" id="modalDeleteCalendar{{ $academic_calendar->id }}"
                                    data-backdrop="static" data-keyboard="false" tabindex="-1"
                                    aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <form action="{{ route('calendars.delete', $academic_calendar->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel1">Eliminar Actividad
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p style="color: red;">¿Seguro de eliminar esta actividad?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary"
                                                        data-bs-dismiss="modal">
                                                        Cerrar
                                                    </button>
                                                    <button type="submit" class="btn btn-danger"
                                                        id="btnCreateUser">Eliminar</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('css')
    <style>
        .border-search {
            border-top: 1px;
            border-right: 1px;
            border-left: 1px;
            border-radius: 0;
        }
    </style>
@endsection

@section('js')
@endsection
