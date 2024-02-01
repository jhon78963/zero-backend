@extends('layout.template')

@section('title')
    Asistencia
@endsection

@section('content')
    <div class="card mb-4" style="padding-right: 1rem">
        <form action="{{ route('attendance.admin.index', $period->name) }}" method="GET">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="card-header">Asistencias</h5>

                <select name="classroom_id" id="classroom_id" class="form-control text-center" style="width: 15%;">
                    @foreach ($classrooms as $classroom)
                        <option value="{{ $classroom->id }}">
                            {{ $classroom->description }}</option>
                    @endforeach
                </select>

                <div class="d-flex align-items-center">
                    <input type="date" value="{{ $today }}" class="form-control me-1" name="date">

                    <button class="btn btn-primary" type="submit">
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
                        <th class="text-center">Estado</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @if ($attendances->count() > 0)
                        @foreach ($attendances as $attendace)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($attendace->CreationTime)->format('d/m/Y') }}</td>
                                <td>
                                    {{ $attendace->surname }}
                                    {{ $attendace->mother_surname }}
                                    {{ $attendace->first_name }}
                                    {{ $attendace->other_names }}
                                </td>
                                <td class="text-center">{{ $attendace->status }}</td>
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
