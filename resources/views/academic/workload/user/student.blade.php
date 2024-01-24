@extends('layout.template')

@section('title')
    Carga horaria
@endsection

@section('content')
    <div class="card mb-4">
        <div class="d-flex align-items-center justify-content-between ">
            <h5 class="card-header">Horario</h5>
            <div style="padding-right: 1rem">
                <button type="button" class="btn btn-outline-primary me-1" onclick="openCreateScheduleModal()">
                    Guardar Selección
                </button>
            </div>
        </div>

        <form action="{{ route('workload.index', $period->name) }}" method="GET">
            <div class="d-flex align-items-center justify-content-center">
                <select name="classroom_id" id="classroom_id" class="form-control text-center" style="width: 30%;">
                    <option value="">Seleccione ...</option>
                    @foreach ($classrooms as $classroom)
                        <option value="{{ $classroom->id }}" {{ $classroom->id == $classroom_id ? 'selected' : '' }}>
                            {{ $classroom->description }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </form>


        <div class="table-responsive text-nowrap">
            <table>
                <thead>
                    <tr>
                        <th>Hora</th>
                        <th>Lunes</th>
                        <th>Martes</th>
                        <th>Miércoles</th>
                        <th>Jueves</th>
                        <th>Viernes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($schedule as $row)
                        <tr>
                            <td>{{ $row['time'] }}</td>
                            @foreach ($row['days'] as $day)
                                <td class="schedule-block {{ $day['class'] }}" data-hour="{{ $row['time'] }}"
                                    data-day="{{ $day['day'] }}">
                                    @if ($day['class'] == 'occupied')
                                        {{ $day['content'] }}
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('css')
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .recess {
            background-color: #ffc107;
        }

        .selected {
            background-color: #4caf50;
            color: white;
        }
    </style>
@endsection

@section('js')
@endsection
