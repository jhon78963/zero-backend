@extends('layout.template')

@section('title')
    Asistencia
@endsection

@section('content')
    <div class="card mb-4" style="padding-right: 1rem">
        <div class="d-flex align-items-center justify-content-between">



            <h5 class="card-header">Asistencias Fecha: {{ $today }}</h5>
        </div>
    </div>

    <div class="card ">
        <div class="d-flex justify-content-between align-items-center me-4 p-3">

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

            <a href="{{ route('attendance.teacher.index', $period->name) }}" class="btn btn-primary">Regresar</a>
        </div>

        @if ($attendance == null || $attendance->status == true)
            <div class="visible-print text-center mb-4">
                {!! $generateQr !!}
            </div>
        @elseif ($attendance != null && $attendance->status == false)
            <p class="text-center" style="font-size: 3rem;">Asistencia Cerrada</p>
        @endif

        <table class="table" id="tabla-roles">
            <thead>
                <tr>
                    <th width="15%">#</th>
                    <th witdh="80%">Alumno</th>
                    <th witdh="20%">Estado</th>
                    <th width="15%">Acción</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @if ($studentAttendances->count() > 0)
                    @foreach ($studentAttendances as $studentAttendance)
                        <tr>
                            <td width="15%">{{ $loop->iteration }}</td>
                            <td witdh="80%">
                                {{ $studentAttendance->surname }}
                                {{ $studentAttendance->mother_surname }}
                                {{ $studentAttendance->first_name }}
                                {{ $studentAttendance->other_names }}
                            </td>
                            <td witdh="20%"> {{ $studentAttendance->status }}</td>
                            <td width="15%">
                                @if ($attendance != null && $attendance->status == true)
                                    <div class="btn-group">
                                        <button type="button"
                                            class="btn btn-danger btn-icon rounded-pill dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <form method="post"
                                                    action="{{ route('attendance.change.present', [$period->id, $fecha, $studentAttendance->student_id]) }}">
                                                    @csrf
                                                    <button class="dropdown-item" type="submit">Presente</button>
                                                </form>
                                            </li>
                                            <li>
                                                <form method="post"
                                                    action="{{ route('attendance.change.missing', [$period->id, $fecha, $studentAttendance->student_id]) }}">
                                                    @csrf
                                                    <button class="dropdown-item" type="submit">Falta</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
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
@endsection

@section('js')
@endsection
