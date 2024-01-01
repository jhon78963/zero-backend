@extends('layout.template')

@section('title')
    Calendario
@endsection

@section('content')
    <div class="card mb-4" style="padding-right: 1rem">
        <div class="d-flex align-items-center justify-content-between ">
            <h5 class="card-header">Calendario Académico</h5>
            <button type="button" class="btn rounded-pill btn-icon btn-outline-primary me-1" data-bs-toggle="modal"
                data-bs-target="#modalCreateCalendar">
                <span class="tf-icons bx bx-list-plus"></span>
            </button>
        </div>
    </div>

    <div class="modal fade" id="modalCreateCalendar" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('calendars.store', $academic_period->name) }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1">Registrar Actividad</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col mb-3">
                                <label for="name" class="form-label">Responsable</label>
                                <input type="text" id="c_responsible_person" name="responsible_person"
                                    class="form-control" placeholder="Cargo del responsable de la actividad" />
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
        <div class="table-responsive text-nowrap mt-1">
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
                                <strong>({{ $academic_calendar->responsible_person }})</strong>
                            </td>
                            <td class="text-center">{{ $academic_calendar->duration_activity }}</td>
                            <td class="text-center">{{ $academic_calendar->start }}</td>
                            <td class="text-center">{{ $academic_calendar->end }}</td>
                            <td class="text-center">
                                <button type="button" class="btn rounded-pill btn-icon btn-outline-primary me-1"
                                    id="btnEdit">
                                    <span class="tf-icons bx bx-edit-alt"></span>
                                </button>

                                <button type="button" class="btn rounded-pill btn-icon btn-outline-danger" id="btnDelete">
                                    <span class="tf-icons bx bx-trash"></span>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('js')
@endsection
