@extends('layout.template')

@section('title')
    Sílabus
@endsection

@section('content')
    <div class="card mb-4" style="padding-right: 1rem">
        <div class="d-flex align-items-center justify-content-between ">
            <h5 class="card-header">Asistencias Fecha: {{ $today }}</h5>
        </div>
    </div>

    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center me-4">

            @if ($attendance == null)
                <form method="post" action="{{ route('attendance.enable', $period->id) }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        Aperturar asistencia
                    </button>
                </form>
            @endif

            @if ($attendance != null)
                <form method="post" action="{{ route('attendance.disable', [$period->id, $fecha]) }}">
                    @csrf
                    <button type="submit" class="btn btn-danger">Cerrar Asistencia</button>
                </form>
            @endif
        </div>

        @if ($attendance == null || $attendance->status == true)
            <div class="visible-print text-center">
                {!! $generateQr !!}
            </div>
        @elseif ($attendance != null && $attendance->status == false)
            <p class="text-center" style="font-size: 3rem;">Asistencia Cerrada</p>
        @endif

        <div class="table-responsive text-nowrap mt-1">
            <table class="table" id="tabla-roles">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th witdh="80%">Alumno</th>
                        <th witdh="80%">Estado</th>
                        <th width="15%">Acción</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @if ($studentAttendances->count() > 0)
                        @foreach ($studentAttendances as $studentAttendance)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    {{ $studentAttendance->student->first_name }} {{ $studentAttendance->student->surname }}
                                </td>
                                <td> {{ $studentAttendance->status }}</td>
                                <td>
                                    @if ($attendance != null && $attendance->status == true)
                                        <form method="post"
                                            action="{{ route('attendance.change', [$period->id, $fecha, $studentAttendance->student_id]) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-danger">Cambiar</button>
                                        </form>
                                    @endif
                                </td>
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
