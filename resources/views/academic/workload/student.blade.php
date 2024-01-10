@extends('layout.template')

@section('title')
    Carga horaria
@endsection

@section('content')
    <div class="card mb-4">
        <h5 class="card-header">Carga horaria para el alumno</h5>

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

            <button id="saveSchedule">Guardar Selección</button>

            <form id="courseForm" style="display: none;" method="POST" action="{{ route('save-schedule') }}">
                @csrf
                <label for="courseSelect">Seleccionar Curso:</label>
                <select id="courseSelect" name="course_id">
                    @foreach ($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->description }}</option>
                    @endforeach
                </select>

                <input type="text" name="selected_blocks" id="selected_blocks" class="form-control">

                <button type="submit">Guardar</button>
            </form>
        </div>
    </div>

    {{-- @include('academic.course.course-assign-modal') --}}
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
    <script>
        $(document).ready(function() {
            let selectedBlocks = [];

            // Manejar clics en bloques seleccionables
            $('.schedule-block').on('click', function() {
                $(this).toggleClass('selected');

                // Actualizar la lista de bloques seleccionados
                selectedBlocks = $('.schedule-block.selected').map(function() {
                    return {
                        hour: $(this).data('hour'),
                        day: $(this).data('day')
                    };
                }).get();

                $('#selected_blocks').val(JSON.stringify(selectedBlocks));
            });

            // Manejar clic en el botón de guardar selección
            $('#saveSchedule').on('click', function() {
                if (selectedBlocks.length > 0) {
                    // Mostrar el formulario de curso si hay bloques seleccionados
                    $('#courseForm').show();
                }
            });
        });
    </script>
@endsection
