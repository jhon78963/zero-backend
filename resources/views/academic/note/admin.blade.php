@extends('layout.template')

@section('title')
    Registro de notas
@endsection

@section('content')
    <div class="card mb-4" style="padding-right: 1rem">
        <form action="{{ route('grade.admin.index', $period->name) }}">
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
        <table class="table">
            <thead>
                <tr class="text-center">
                    <th>#</th>
                    <th width="60%">Alumno</th>
                    <th>Calific. Final del Área</th>
                    <th>Eval. de Recuperación</th>
                    <td></td>
                </tr>
            </thead>
            <tbody>
                @if (count($students) > 0)
                    @foreach ($students as $student)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">
                                {{ $student->surname }} {{ $student->mother_surname }}
                                {{ $student->first_name }} {{ $student->other_names }}
                            </td>
                            <td class="text-center">
                                {{ $student->grade_final != null ? $student->grade_final : 'en curso' }}
                            </td>
                            <td class="text-center">
                                {{ $student->grade_extension != null ? $student->grade_final : 'en curso' }}
                            </td>
                            <td class="text-center">
                                <a href="{{ route('grade.admin.show', [$period->name, $student->student_id]) }}"
                                    class="btn btn-primary btn-sm">
                                    <i class='bx bx-show'></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" class="text-center">NO DATA</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    {{-- <div class="card">
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
                                        <option value="C" {{ $competencia['grade_b_1'] == 'D' ? 'selected' : '' }}>
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

                                </td>
                                <td rowspan="{{ count($competenciasPorCurso[$course->id]) + 1 }}" class="text-center">
                                    <select name="nota_recuperación[]" class="form-control text-center" style="width: 80px"
                                        disabled>
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
    </div> --}}
@endsection

@section('js')
@endsection
