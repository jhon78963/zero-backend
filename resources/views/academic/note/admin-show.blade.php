@extends('layout.template')

@section('title')
    Registro de notas
@endsection

@section('content')
    <div class="card mb-4" style="padding-right: 1rem">
        <div class="d-flex align-items-center justify-content-between ">
            @if (isset($previousEstudiante))
                <a href="{{ route('grade.admin.showPrevious', [$period->name, $previousEstudiante->id]) }}"
                    class="btn btn-primary ms-3">Anterior</a>
            @endif

            <h5 class="card-header">Registro de notas del alumno {{ $student->first_name }} {{ $student->surname }}</h5>

            @if (isset($nextEstudiante))
                <a href="{{ route('grade.admin.showNext', [$period->name, $nextEstudiante->id]) }}"
                    class="btn btn-primary">Siguiente</a>
            @endif
        </div>
    </div>

    <div class="card">
        <table class="table table-bordered">
            <thead>
                <tr class="text-center">
                    <th rowspan="2">Área</th>
                    <th rowspan="2">CRITERIOS DE EVALUACIÓN</th>
                    <th colspan="4">BIMESTRE/TRIMESTRE</th>
                    <th rowspan="2">Calific. Final del Área</th>
                    <th rowspan="2">Eval. de Recuperación</th>
                </tr>
                <tr class="text-center">
                    <th>1</th>
                    <th>2</th>
                    <th>3</th>
                    <th>4</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($courses as $course)
                    @foreach ($competenciasPorCurso[$course->id] as $competencia)
                        @if ($loop->iteration == 1)
                            <tr>
                                <td rowspan="{{ count($competenciasPorCurso[$course->id]) + 1 }}">
                                    {{ $course->description }}</td>
                                <td>{{ $competencia['description'] }}</td>
                                <td>
                                    <select name="nota_1[]" class="form-control text-center" style="width: 80px;" disabled>
                                        <option value="">Nota</option>
                                        <option value="AD"{{ $competencia['grade_b_1'] == 'AD' ? 'selected' : '' }}>
                                            AD
                                        </option>
                                        <option value="A" {{ $competencia['grade_b_1'] == 'A' ? 'selected' : '' }}>
                                            A
                                        </option>
                                        <option value="B" {{ $competencia['grade_b_1'] == 'B' ? 'selected' : '' }}>
                                            B
                                        </option>
                                        <option value="C" {{ $competencia['grade_b_1'] == 'C' ? 'selected' : '' }}>
                                            C</option>
                                    </select>
                                </td>
                                <td>
                                    <select name="nota_2[]" class="form-control text-center" style="width: 80px" disabled>
                                        <option value="">Nota</option>
                                        <option value="AD" {{ $competencia['grade_b_2'] == 'AD' ? 'selected' : '' }}>AD
                                        </option>
                                        <option value="A" {{ $competencia['grade_b_2'] == 'A' ? 'selected' : '' }}>A
                                        </option>
                                        <option value="B" {{ $competencia['grade_b_2'] == 'B' ? 'selected' : '' }}>B
                                        </option>
                                        <option value="C" {{ $competencia['grade_b_3'] == 'C' ? 'selected' : '' }}>C
                                        </option>
                                    </select>
                                </td>
                                <td>
                                    <select name="nota_3[]" class="form-control text-center" style="width: 80px" disabled>
                                        <option value="">Nota</option>
                                        <option value="AD" {{ $competencia['grade_b_3'] == 'AD' ? 'selected' : '' }}>AD
                                        </option>
                                        <option value="A" {{ $competencia['grade_b_3'] == 'A' ? 'selected' : '' }}>A
                                        </option>
                                        <option value="B" {{ $competencia['grade_b_3'] == 'B' ? 'selected' : '' }}>B
                                        </option>
                                        <option value="C" {{ $competencia['grade_b_3'] == 'C' ? 'selected' : '' }}>C
                                        </option>
                                    </select>
                                </td>
                                <td>
                                    <select name="nota_4[]" class="form-control text-center" style="width: 80px" disabled>
                                        <option value="">Nota</option>
                                        <option value="AD" {{ $competencia['grade_b_4'] == 'AD' ? 'selected' : '' }}>AD
                                        </option>
                                        <option value="A" {{ $competencia['grade_b_4'] == 'A' ? 'selected' : '' }}>A
                                        </option>
                                        <option value="B" {{ $competencia['grade_b_4'] == 'B' ? 'selected' : '' }}>B
                                        </option>
                                        <option value="C" {{ $competencia['grade_b_4'] == 'C' ? 'selected' : '' }}>C
                                        </option>
                                    </select>
                                </td>
                                <td rowspan="{{ count($competenciasPorCurso[$course->id]) + 1 }}" class="text-center">
                                    @if (isset($promediosPorCurso[$course->id]['prom_grade_course_final']))
                                        @if (
                                            $promediosPorCurso[$course->id]['prom_grade_course_final'] != 0 &&
                                                $promediosPorCurso[$course->id]['prom_grade_b_1'] != 0 &&
                                                $promediosPorCurso[$course->id]['prom_grade_b_2'] != 0 &&
                                                $promediosPorCurso[$course->id]['prom_grade_b_3'] != 0 &&
                                                $promediosPorCurso[$course->id]['prom_grade_b_4'] != 0)
                                            {{ $promediosPorCurso[$course->id]['promedio_grade_course_final'] }}
                                        @endif
                                    @endif
                                </td>
                                <td rowspan="{{ count($competenciasPorCurso[$course->id]) + 1 }}" class="text-center">
                                    <select name="nota_recuperación[]" class="form-control text-center" style="width: 80px"
                                        disabled>
                                        <option value="">Nota</option>
                                        <option value="AD">AD</option>
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>
                                    </select>
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td>{{ $competencia['description'] }}</td>
                                <td>
                                    <select name="nota_1[]" class="form-control text-center" style="width: 80px" disabled>
                                        <option value="">Nota</option>
                                        <option value="AD" {{ $competencia['grade_b_1'] == 'AD' ? 'selected' : '' }}>AD
                                        </option>
                                        <option value="A" {{ $competencia['grade_b_1'] == 'A' ? 'selected' : '' }}>A
                                        </option>
                                        <option value="B" {{ $competencia['grade_b_1'] == 'B' ? 'selected' : '' }}>B
                                        </option>
                                        <option value="C" {{ $competencia['grade_b_1'] == 'C' ? 'selected' : '' }}>C
                                        </option>
                                    </select>
                                </td>
                                <td>
                                    <select name="nota_2[]" class="form-control text-center" style="width: 80px" disabled>
                                        <option value="">Nota</option>
                                        <option value="AD" {{ $competencia['grade_b_2'] == 'AD' ? 'selected' : '' }}>AD
                                        </option>
                                        <option value="A" {{ $competencia['grade_b_2'] == 'A' ? 'selected' : '' }}>A
                                        </option>
                                        <option value="B" {{ $competencia['grade_b_2'] == 'B' ? 'selected' : '' }}>B
                                        </option>
                                        <option value="C" {{ $competencia['grade_b_2'] == 'C' ? 'selected' : '' }}>C
                                        </option>
                                    </select>
                                </td>
                                <td>
                                    <select name="nota_3[]" class="form-control text-center" style="width: 80px" disabled>
                                        <option value="">Nota</option>
                                        <option value="AD" {{ $competencia['grade_b_3'] == 'AD' ? 'selected' : '' }}>AD
                                        </option>
                                        <option value="A" {{ $competencia['grade_b_3'] == 'A' ? 'selected' : '' }}>A
                                        </option>
                                        <option value="B" {{ $competencia['grade_b_3'] == 'B' ? 'selected' : '' }}>B
                                        </option>
                                        <option value="C" {{ $competencia['grade_b_3'] == 'C' ? 'selected' : '' }}>C
                                        </option>
                                    </select>
                                </td>
                                <td>
                                    <select name="nota_4[]" class="form-control text-center" style="width: 80px" disabled>
                                        <option value="">Nota</option>
                                        <option value="AD" {{ $competencia['grade_b_4'] == 'AD' ? 'selected' : '' }}>AD
                                        </option>
                                        <option value="A" {{ $competencia['grade_b_4'] == 'A' ? 'selected' : '' }}>A
                                        </option>
                                        <option value="B" {{ $competencia['grade_b_4'] == 'B' ? 'selected' : '' }}>B
                                        </option>
                                        <option value="C" {{ $competencia['grade_b_4'] == 'C' ? 'selected' : '' }}>C
                                        </option>
                                    </select>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    <tr>
                        <td>CALIF. PROMEDIO AREA</td>
                        <td id="prom_grade_b_1" class="text-center">
                            @if ($promediosPorCurso[$course->id]['prom_grade_b_1'] != 0)
                                {{ $promediosPorCurso[$course->id]['promedio_grade_b_1'] }}
                            @endif
                        </td>
                        <td id="prom_grade_b_2" class="text-center">
                            @if ($promediosPorCurso[$course->id]['prom_grade_b_2'] != 0)
                                {{ $promediosPorCurso[$course->id]['promedio_grade_b_2'] }}
                            @endif
                        </td>
                        <td id="prom_grade_b_3" class="text-center">
                            @if ($promediosPorCurso[$course->id]['prom_grade_b_3'] != 0)
                                {{ $promediosPorCurso[$course->id]['promedio_grade_b_3'] }}
                            @endif
                        </td>
                        <td id="prom_grade_b_4" class="text-center">
                            @if ($promediosPorCurso[$course->id]['prom_grade_b_4'] != 0)
                                {{ $promediosPorCurso[$course->id]['promedio_grade_b_4'] }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('js')
@endsection
