<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Nota de pago</title>
        <style>
            @page {
                size: 105mm 160mm;
                /* Ancho y alto del ticket */
                margin: 0;
            }

            body {
                font-family: Arial, sans-serif;
                font-size: 12px;
                margin: 0;
                padding: 10px;
                line-height: 1.4;
                text-align: center;
            }

            .header {
                font-weight: bold;
                font-size: 16px;
                margin-bottom: 10px;
            }

            .body {
                font-weight: bold;
                font-size: 11px;
                margin-bottom: 10px;
            }

            .info {
                margin-bottom: 10px;
            }

            .item-row {
                display: flex;
                justify-content: space-between;
                margin-bottom: 5px;
                border-bottom: 1px #ccc;
            }

            .item-row:last-child {
                border-bottom: none;
            }

            .item-name {
                flex: 1;
                text-align: left;
            }

            .item-quantity,
            .item-price {
                flex: 0.3;
                text-align: left;
            }

            .subtotal-row {
                display: flex;
                flex-wrap: wrap;
                justify-content: space-between;
                border-top: 1px;
                padding-top: 5px;
            }

            .total-row {
                display: flex;
                flex-wrap: wrap;
                justify-content: space-between;
                margin-top: 10px;
                border-top: 1px solid #000;
                padding-top: 5px;
            }

            .table-row {
                border-bottom: 1px solid #000;
            }

            .total-label {
                font-weight: bold;
                flex: 1;
                text-align: right;
            }

            .total-value {
                font-weight: bold;
                flex: 0.5;
                text-align: right;
                white-space: pre-line;
            }

            text {
                display: none;
            }
        </style>
    </head>

    <body>
        <div class="header">{{ $venta->nombre_comercial_emisor }}</div>
        <div class="info">
            <strong>{{ $venta->ruc_emisor }}</strong>
            <br>
            <strong>{{ $venta->direccion_emisor }}</strong>
            <br>
            <strong>Trujillo - La Libertad</strong>
            <br>
            <strong>(044) 373387 - 944 979 191</strong>
            <br>
            <strong>informes@iepsangerardo.edu.pe</strong>
        </div>

        <div class="total-row"></div>

        <div class="header">NOTA DE VENTA ELECTRÓNICA</div>
        <div class="body">
            {{ $venta->serie }} - {{ $venta->numero }}
            <br>
            Fecha de emisión: {{ $fecha }} {{ $venta->hora_emision }}
            <br>
            Señor(a): {{ $venta->nombre_cliente }}
            <br>
            {{ $venta->numero_documento_cliente }}
        </div>

        <div class="total-row"></div>

        <table style="width: 100%">
            <thead>
                <tr>
                    <th>Cant.</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Importe</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productos as $producto)
                    <tr>
                        <td style="text-align: center;">{{ $producto->cantidad }}</td>
                        <td>{{ $producto->description }}</td>
                        <td style="text-align: center;">{{ $producto->monto }}</td>
                        <td style="text-align: center;">{{ $producto->monto_total }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-row"></div>

        <table style="width: 100%">
            <thead>
                <tr>
                    <th width="10%"></th>
                    <th width="40%"></th>
                    <th style="text-align: right;">SubTotal:</th>
                    <th style="text-align: right;">S/ {!! number_format((float) ($venta->total - $venta->total_igv), 2) !!}</th>
                </tr>
                <tr>
                    <th></th>
                    <th></th>
                    <th style="text-align: right;">IGV (18%):</th>
                    <th style="text-align: right;">S/ {!! number_format((float) $venta->total_igv, 2) !!}</th>
                </tr>
                <tr>
                    <th></th>
                    <th></th>
                    <th style="text-align: right;">TOTAL:</th>
                    <th style="text-align: right;">S/ {!! number_format((float) $venta->total, 2) !!}</th>
                </tr>
            </thead>
        </table>

        <div class="total-row"></div>

        <div class="body" style="text-align: left; font-weight: bold;">
            SON: {{ $enLetras->numletras($venta->total, 'SOLES') }}
        </div>

        <div class="total-row"></div>

        <div class="body" style="margin-bottom: 5px;">
            <div style="margin-top: 10px; font-weight: bold;">
                Pago: {{ $venta->payment_method }}
                <br>

                Gracias por su puntualidad
                <br>
            </div>

            <div style="margin-top: 10px">
                {!! DNS2D::getBarcodeSVG($codebar, 'QRCODE') !!}
            </div>

        </div>
    </body>

</html>
