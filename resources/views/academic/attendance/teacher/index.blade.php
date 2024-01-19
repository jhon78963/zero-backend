@extends('layout.template')

@section('title')
    Sílabus
@endsection

@section('content')
    <div class="card mb-4" style="padding-right: 1rem">
        <div class="d-flex align-items-center justify-content-between ">
            <h5 class="card-header">Asistencias</h5>
            <a class="btn btn-primary"
                href="{{ route('attendance.teacher.create', $academic_period->name) }}">
                Registrar Asistencia
            </a>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive text-nowrap mt-1">
            <table class="table" id="tabla-roles">
                <thead>
                    <tr>
                        <th width="15%">Fecha</th>
                        <th class="text-center">Profesor</th>
                        <th class="text-center">Aula</th>
                        <th width="15%" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @if ($attendances->count() > 0)
                        @foreach ($attendances as $attendace)
                            <tr>
                                <td>{{ $attendace->date }}</td>
                                <td class="text-center">{{ $attendace->teacher->first_name }}
                                    {{ $attendace->teacher->surname }}</td>
                                <td class="text-center">{{ $attendace->classroom->description }}</td>
                                <td class="text-center"></td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4" class="text-center">NO DATA</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('js')
@endsection
