@extends('layout.template')

@section('title')
    Notas
@endsection

@section('content')
    <div class="card mb-4" style="padding-right: 1rem">
        <form action="{{ route('grade.teacher.index', $period->name) }}">
            <div class="d-flex align-items-center justify-content-between ">
                <h5 class="card-header">Registro de notas</h5>

                <div class="d-flex align-items-center">
                    <select name="classroom_id" id="classroom_id" class="form-control text-center me-1">
                        @foreach ($classrooms as $classroom)
                            <option value="{{ $classroom->id }}"
                                {{ $classroom->id == $classroomSelected->id ? 'selected' : '' }}>
                                {{ $classroom->description }}</option>
                        @endforeach
                    </select>

                    <button class="btn btn-primary" type="submit">
                        <i class='bx bx-search-alt-2'></i>
                    </button>
                </div>

                <h5 class="card-header">{{ $period->name }}</h5>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="table-responsive text-nowrap mt-1">
            <table class="table" id="tabla-roles">
                <thead>
                    <tr>
                        <th width="15%">#</th>
                        <th>Estudiante</th>
                        <th width="15%" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @if ($classroom_students->count() > 0)
                        @foreach ($classroom_students as $classroom_student)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    {{ $classroom_student->surname }}
                                    {{ $classroom_student->mother_surname }}
                                    {{ $classroom_student->first_name }}
                                    {{ $classroom_student->other_names }}
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('grade.teacher.create', [$period->name, $classroom_student->classroom_id, $classroom_student->student->id]) }}"
                                        class="btn btn-primary btn-rounded">Subir notas</a>
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
