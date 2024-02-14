<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Reporte de morosos</title>
    </head>

    <body>
        <h3 style="text-align: center !important;">REPORTE DE MOROSOS <strong>
                @php
                    echo strtoupper($period->name);
                @endphp
            </strong>
        </h3>
        <div style="justify-content: center !important;">
            <table border="1" align="center" style="width: 100%;">
                <thead>
                    <tr>
                        <th width="10%" class="text-center">AULA</th>
                        <th width="10%" class="text-center">ESTUDIANTE</th>
                        <th width="10%" class="text-center">CONCEPTO</th>
                        <th width="10%" class="text-center">FE. DE VENC.</th>
                        <th width="10%" class="text-center">MONTO</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @if ($morosos->count() > 0)
                        @php $printedStudents = []; @endphp
                        @foreach ($morosos as $moroso)
                            @if (!in_array($moroso->student_id, $printedStudents))
                                @php $printedStudents[] = $moroso->student_id; @endphp
                                @if ($conteoPorEstudiante[$moroso->student_id] > 1)
                                    <tr class="text-center">
                                        <td rowspan="{{ $conteoPorEstudiante[$moroso->student_id] }}">
                                            {{ $moroso->classroom }}
                                        </td>
                                        <td rowspan="{{ $conteoPorEstudiante[$moroso->student_id] }}">
                                            {{ $moroso->surname }}
                                            {{ $moroso->mother_surname }}
                                            {{ $moroso->first_name }}
                                            {{ $moroso->other_names }}
                                        </td>
                                        <td>{{ $moroso->description }}</td>
                                        <td>{{ $moroso->due_date }}</td>
                                        <td>{{ $moroso->cost }}</td>
                                    </tr>
                                    @for ($i = 1; $i < $conteoPorEstudiante[$moroso->student_id]; $i++)
                                        <tr class="text-center">
                                            <td>{{ $morosos[$loop->index + $i]->description }}</td>
                                            <td>{{ $morosos[$loop->index + $i]->due_date }}</td>
                                            <td>{{ $morosos[$loop->index + $i]->cost }}</td>
                                        </tr>
                                    @endfor
                                @else
                                    <tr class="text-center">
                                        <td>{{ $moroso->classroom }}</td>
                                        <td>
                                            {{ $moroso->surname }}
                                            {{ $moroso->mother_surname }}
                                            {{ $moroso->first_name }}
                                            {{ $moroso->other_names }}
                                        </td>
                                        <td>{{ $moroso->description }}</td>
                                        <td>{{ $moroso->due_date }}</td>
                                        <td>{{ $moroso->cost }}</td>
                                    </tr>
                                @endif
                            @endif
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center">NO DATA</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </body>

</html>
