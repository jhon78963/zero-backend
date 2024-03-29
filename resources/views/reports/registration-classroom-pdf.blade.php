<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Matriculados en Aula</title>
    </head>

    <body>

        <h3 style="text-align: center !important;">MATRICULADOS POR AULA</h3>

        <p>Periodo Académico: {{ strtoupper($period->name) }}</p>
        <p>Aula: {{ strtoupper($classroom->description) }}</p>

        <table border="1" style="width: 100%;">
            <thead>
                <tr style="text-align: center !important;">
                    <th>#</th>
                    <th>Alumno</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($registrationClassrooms as $registration)
                    <tr>
                        <td style="text-align: center !important;">{{ $loop->iteration }}</td>
                        <td style="padding-left: 1rem;">
                            {{ $registration->surname }} {{ $registration->mother_surname }}
                            {{ $registration->firstname }} {{ $registration->other_names }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </body>

</html>
