<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Reporte de pagos</title>
    </head>

    <body>
        <h3 style="text-align: center !important;">REPORTE DE PAGOS </h3>
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
                    <th>FECHA</th>
                    <th>CONCEPTO</th>
                    <th>MONTO</th>
                </tr>
            </thead>
            <tbody>
                @if ($payments->count() > 0)
                    @foreach ($payments as $payment)
                        <tr class="text-center">
                            <td style="text-align: center !important;">{{ $payment->date }}</td>
                            <td style="padding-left: 1rem !important;">{{ $payment->description }}</td>
                            <td style="text-align: center !important;">{{ $payment->cost }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="3" class="text-center">Sin pagos</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </body>

</html>
