@extends('layout.template')

@section('title')
    Asistencia
@endsection

@section('content')
    <div class="card mb-4" style="padding-right: 1rem">
        <form action="{{ route('attendance.admin.missing', [$period->name, $classroomSelected->id]) }}" method="GET">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="card-header">Reporte de Faltas</h5>

                <select name="classroom_id" id="classroom_id" class="form-control text-center" style="width: 15%;">
                    @foreach ($classrooms as $classroom)
                        <option value="{{ $classroom->id }}"
                            {{ $classroom->id == $classroomSelected->id ? 'selected' : '' }}>
                            {{ $classroom->description }}</option>
                    @endforeach
                </select>

                <div class="d-flex align-items-center">

                    <button class="btn btn-primary me-2" type="submit">
                        <i class='bx bx-search-alt-2'></i>
                    </button>
                </div>


            </div>
        </form>
    </div>

    <div class="card">
        <div class="table-responsive text-nowrap mt-1">
            <table class="table" id="tabla-roles">
                <thead>
                    <tr>
                        <th width="15%">Fecha</th>
                        <th>Alumno</th>
                        <th class="text-center">ACCION</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @if ($students->count() > 0)
                        @foreach ($students as $student)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    {{ $student->surname }}
                                    {{ $student->mother_surname }}
                                    {{ $student->first_name }}
                                    {{ $student->other_names }}
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('attendance.admin.pdf', [$period->name, $classroomSelected->id, $student->student_id]) }}"
                                        class="btn btn-primary btn-sm" target="_blank">
                                        <i class="bx bxs-file-pdf"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3" class="text-center">NO DATA</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('js')
@endsection
