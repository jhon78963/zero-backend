@extends('layout.template')

@section('title')
    Calendario
@endsection

@section('content')
    <div class="card mb-4" style="padding-right: 1rem">
        <div class="d-flex align-items-center justify-content-between ">
            <h5 class="card-header">Cursos</h5>
            <button type="button" class="btn rounded-pill btn-icon btn-outline-primary me-1" data-bs-toggle="modal"
                data-bs-target="#modalCreateCalendar">
                <span class="tf-icons bx bx-list-plus"></span>
            </button>
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
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('js')
@endsection
