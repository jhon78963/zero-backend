<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Ficha de matricula</title>
    </head>

    <body>
        <h3 style="text-align: center !important;">FICHA DE MATRICULA</h3>

        <h3>
            @php
                echo 'Periodo académico: ' . strtoupper($period->name);
            @endphp
        </h3>

        <table border="1">
            <thead>
                <tr>
                    <td rowspan="2" style="text-align: center !important; width: 200px;">Código de inscripción</td>
                    <td rowspan="2" style="text-align: center !important; width: 200px;">1172808</td>
                </tr>
            </thead>
        </table>

        <br>

        <table class="tg" border="1" style="width: 100%">
            <thead>
                <tr>
                    <th style="text-align: center !important; width: 200px;">1. NOMBRE DEL COLEGIO</th>
                    <th style="text-align: center !important; width: 200px;">NIVEL / MODALIDAD</th>
                    <th style="text-align: center !important; width: 200px;">TURNO</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center !important; width: 200px;">Colegio San Gerardo</td>
                    <td style="text-align: center !important; width: 200px;">Primaria</td>
                    <td style="text-align: center !important; width: 200px;">Mañana</td>
                </tr>
            </tbody>
        </table>

        <br>

        <table border="1" style="width: 100%">
            <thead>
                <tr>
                    <th style="text-align: left !important; padding-left: 1rem;" colspan="4">2. UBICACIÓN DEL CENTRO
                        DE EDUCATIVO</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding-left: 1rem;" colspan="4">UGEL 130016</td>
                </tr>
                <tr>
                    <td style="padding-left: 1rem;">Provincia</td>
                    <td style="padding-left: 1rem;">Trujillo</td>
                    <td style="padding-left: 1rem;">Distrito</td>
                    <td style="padding-left: 1rem;">Trujillo</td>
                </tr>
                <tr>
                    <td style="padding-left: 1rem;">Lugar</td>
                    <td style="padding-left: 1rem;">Santa Inés</td>
                    <td style="padding-left: 1rem;">Dirección</td>
                    <td style="padding-left: 1rem;">Mz. X 1 Lt. 15 Urb. Santa Inés - Trujillo</td>
                </tr>
            </tbody>
        </table>

        <br>

        <table border="1" style="width: 100%">
            <thead>
                <tr>
                    <th style="text-align: left !important; padding-left: 1rem;" colspan="7">3. DATOS PERSONALES DEL
                        ESTUDIANTE</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: left !important; padding-left: 1rem;">Documento de Identidad</td>
                    <td style="text-align: left !important; padding-left: 1rem;" colspan="6">{{ $student->dni }}</td>
                </tr>
                <tr>
                    <td style="text-align: center !important;">Apellido Paterno</td>
                    <td style="text-align: center !important;">Apellido Materno</td>
                    <td style="text-align: center !important;">Nombres</td>
                    <td style="text-align: center !important;" colspan="4">Sexo</td>
                </tr>
                <tr>
                    <td style="text-align: center !important;">{{ $student->surname }}</td>
                    <td style="text-align: center !important;">{{ $student->mother_surname }}</td>
                    <td style="text-align: center !important;">{{ $student->first_name }} {{ $student->other_names }}
                    </td>
                    <td style="text-align: center !important;">M</td>
                    <td style="text-align: center !important;">{{ $student->gender == 'M' ? 'X' : '' }}</td>
                    <td style="text-align: center !important;">F</td>
                    <td style="text-align: center !important;">{{ $student->gender == 'F' ? 'X' : '' }}</td>
                </tr>
                <tr>
                    <td style="text-align: center !important;" colspan="7">DOMICILIO</td>
                </tr>
                <tr>
                    <td style="padding-left: 1rem;">Dirección</td>
                    <td style="padding-left: 1rem;" colspan="6">{{ $student->address }}</td>
                </tr>
                <tr>
                    <td style="padding-left: 1rem;">Teléfono</td>
                    <td style="padding-left: 1rem;" colspan="6">{{ $student->phone }}</td>
                </tr>
            </tbody>
        </table>

        <br>

        <table border="1" style="width: 100%">
            <thead>
                <tr>
                    <th style="text-align: left !important; padding-left: 1rem;" colspan="6">4. DATOS ACADÉMICOS</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding-left: 1rem;">Periodo académico</td>
                    <td style="padding-left: 1rem;" colspan="5">{{ $period->name }}</td>
                </tr>
                <tr>
                    <td style="padding-left: 1rem;">Grado</td>
                    <td style="padding-left: 1rem;" colspan="2">{{ $grade->description }}</td>
                    <td style="padding-left: 1rem;">Sección</td>
                    <td style="padding-left: 1rem;" colspan="2">{{ $section->description }}</td>
                </tr>
            </tbody>
        </table>

        <br>

        Fecha: {{ $matricula->created_date }}

        <br><br><br><br><br><br><br><br><br><br><br><br>

        <table align="center">
            <thead>
                <tr>
                    <th style="text-align: center !important;">________________________________</th>
                    <th class="tg-c3ow" style="width: 25px;"></th>
                    <th style="text-align: center !important;">________________________________</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center !important;" rowspan="2">APODERADO</td>
                    <td style="text-align: center !important;"></td>
                    <td style="text-align: center !important;">SECRETARIA / DIRECCIÓN</td>
                </tr>
                <tr>
                    <td style="text-align: center !important;"></td>
                    <td style="text-align: center !important;">(Firma, sello, post firma)</td>
                </tr>
            </tbody>
        </table>
    </body>

</html>
