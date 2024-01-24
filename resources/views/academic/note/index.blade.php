@extends('layout.template')

@section('title')
    Sílabus
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
                                    <button class="btn btn-primary btn-rounded">Subir notas</button>
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
