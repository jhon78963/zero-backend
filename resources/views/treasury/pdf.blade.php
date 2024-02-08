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
                        <th width="10%">Aula</th>
                        <th width="10%">Moroso</th>
                        <th width="10%">Concepto</th>
                        <th width="10%">Fecha de venc.</th>
                        <th width="10%">Monto</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($morosos->count() > 0)
                        @foreach ($morosos as $moroso)
                            <tr>
                                <td style="justify-content: center !important;">{{ $moroso->classroom }}</td>
                                <td class="text-center">
                                    {{ $moroso->first_name }}
                                    {{ $moroso->other_names }}
                                    {{ $moroso->surname }}
                                    {{ $moroso->mother_surname }}
                                </td>
                                <td class="text-center">{{ $moroso->description }}</td>
                                <td style="justify-content: center !important;">{{ $moroso->due_date }}</td>
                                <td style="justify-content: center !important;">{{ $moroso->cost }}</td>
                            </tr>
                        @endforeach
                    @else
                        <td colspan="3">NO DATA</td>
                    @endif
                </tbody>
            </table>
        </div>
    </body>

</html>
