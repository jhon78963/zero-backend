<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Alumnos matriculados</title>
    </head>

    <body>
        <h3 style="text-align: center !important;">MATRICULADOS POR AULA <strong>
                @php
                    echo strtoupper($period->name);
                @endphp
            </strong>
        </h3>

        <table border="1" style="width: 100%;s">
            <thead>
                <tr style="text-align: center !important;">
                    <th>Aula</th>
                    <th>Matriculados</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($registrationClassrooms as $registration)
                    <tr style="text-align: center !important;">
                        <td>{{ $registration->description }}</td>
                        <td>{{ $registration->registration }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>

</html>
