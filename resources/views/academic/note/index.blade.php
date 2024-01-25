@extends('layout.template')

@section('title')
    Notas
@endsection

@section('content')
    <div class="card mb-4" style="padding-right: 1rem">
        <div class="d-flex align-items-center justify-content-between ">
            <h5 class="card-header">Listado de alumnos del {{ $room->description }}</h5>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive text-nowrap mt-1">
            <table class="table" id="tabla-roles">
                <thead>
                    <tr>
                        <th width="15%">#</th>
                        <th class="text-center">Estudiante</th>
                        <th width="15%" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @if ($classroom_students->count() > 0)
                        @foreach ($classroom_students as $classroom_student)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $classroom_student->student->first_name }}
                                    {{ $classroom_student->student->surname }}</td>
                                <td class="text-center">
                                    @if ($calendar_notas)
                                        @if (
                                            $calendar_notas->activity == 'Subida de notas I Bimestre' ||
                                                $calendar_notas->activity == 'Subida de notas II Bimestre' ||
                                                $calendar_notas->activity == 'Subida de notas III Bimestre' ||
                                                $calendar_notas->activity == 'Subida de notas IV Bimestre')
                                            <a href="{{ route('grade.teacher.create', [$period->name, $room->id, $classroom_student->student->id]) }}"
                                                class="btn btn-primary btn-rounded">Subir notas</a>
                                        @else
                                            <p class="me-4">Está fuera del periodo de registro de notas</p>
                                        @endif
                                    @else
                                        <p class="me-4">Está fuera del periodo de registro de notas</p>
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
