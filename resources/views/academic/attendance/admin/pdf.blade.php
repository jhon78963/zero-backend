<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Reporte de faltas</title>
    </head>

    <body>
        <h3 style="text-align: center !important;">REPORTE DE FALTAS </h3>
        <h3>
            @php
                echo 'Periodo académico: ' . strtoupper($period->name);
            @endphp
        </h3>
        <h3>
            @php
                echo 'Aula: ' . strtoupper($classroom->description);
            @endphp
        </h3>
        <h3>
            @php
                echo 'Estudiante: ' . strtoupper($student->surname . ' ' . $student->mother_surname . ' ' . $student->first_name . ' ' . $student->other_names);
            @endphp
        </h3>

        <table border="1" style="width: 100%;">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>ESTADO</th>
                    <th>JUSTIFICACIÓN</th>
                </tr>
            </thead>
            <tbody>
                @if ($missing->count() > 0)
                    @foreach ($missing as $miss)
                        <tr style="text-align: center !important;">
                            <td>{{ $miss->CreationTime }}</td>
                            <td>{{ $miss->status }}</td>
                            <td>-</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="3" style="text-align: center !important;">Sin faltas</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </body>

</html>
