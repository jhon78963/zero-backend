<!DOCTYPE html>
<html
    style="width:100%;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0">

<head>
    <meta http-equiv="Content-Security-Policy"
        content="script-src 'none'; connect-src 'none'; object-src 'none'; form-action 'none';">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta name="x-apple-disable-message-reformatting">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="telephone=no" name="format-detection">
    <title>Recuperar contraseña</title>
    <link rel="icon" type="image/png" href="{{ asset('loginn/img/icono.png') }}">
    <style type="text/css">
        #outlook a {
            padding: 0;
        }

        .ExternalClass {
            width: 100%;
        }

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }

        .es-button {
            text-decoration: none !important;
        }

        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        .es-desk-hidden {
            display: none;
            float: left;
            overflow: hidden;
            width: 0;
            max-height: 0;
            line-height: 0;
        }

        .es-button-border:hover a.es-button {
            background: #ffffff !important;
            border-color: #ffffff !important;
        }

        .es-button-border:hover {
            background: #ffffff !important;
            border-style: solid solid solid solid !important;
            border-color: whitesmoke whitesmoke whitesmoke whitesmoke !important;
        }

        @media only screen and (max-width:600px) {

            p,
            ul li,
            ol li,
            a {
                font-size: 16px !important;
                line-height: 150% !important
            }

            h1 {
                font-size: 20px !important;
                text-align: center;
                line-height: 120% !important
            }

            h2 {
                font-size: 16px !important;
                text-align: left;
                line-height: 120% !important
            }

            h3 {
                font-size: 20px !important;
                text-align: center;
                line-height: 120% !important
            }

            h1 a {
                font-size: 20px !important
            }

            h2 a {
                font-size: 16px !important;
                text-align: left
            }

            h3 a {
                font-size: 20px !important
            }

            .es-menu td a {
                font-size: 14px !important
            }

            .es-header-body p,
            .es-header-body ul li,
            .es-header-body ol li,
            .es-header-body a {
                font-size: 10px !important
            }

            .es-footer-body p,
            .es-footer-body ul li,
            .es-footer-body ol li,
            .es-footer-body a {
                font-size: 12px !important
            }

            .es-infoblock p,
            .es-infoblock ul li,
            .es-infoblock ol li,
            .es-infoblock a {
                font-size: 12px !important
            }

            *[class="gmail-fix"] {
                display: none !important
            }

            .es-m-txt-c,
            .es-m-txt-c h1,
            .es-m-txt-c h2,
            .es-m-txt-c h3 {
                text-align: center !important
            }

            .es-m-txt-r,
            .es-m-txt-r h1,
            .es-m-txt-r h2,
            .es-m-txt-r h3 {
                text-align: right !important
            }

            .es-m-txt-l,
            .es-m-txt-l h1,
            .es-m-txt-l h2,
            .es-m-txt-l h3 {
                text-align: left !important
            }

            .es-m-txt-r img,
            .es-m-txt-c img,
            .es-m-txt-l img {
                display: inline !important
            }

            .es-button-border {
                display: block !important
            }

            a.es-button {
                font-size: 14px !important;
                display: block !important;
                border-left-width: 0px !important;
                border-right-width: 0px !important
            }

            .es-btn-fw {
                border-width: 10px 0px !important;
                text-align: center !important
            }

            .es-adaptive table,
            .es-btn-fw,
            .es-btn-fw-brdr,
            .es-left,
            .es-right {
                width: 100% !important
            }

            .es-content table,
            .es-header table,
            .es-footer table,
            .es-content,
            .es-footer,
            .es-header {
                width: 100% !important;
                max-width: 600px !important
            }

            .es-adapt-td {
                display: block !important;
                width: 100% !important
            }

            .adapt-img {
                width: 100% !important;
                height: auto !important
            }

            .es-m-p0 {
                padding: 0px !important
            }

            .es-m-p0r {
                padding-right: 0px !important
            }

            .es-m-p0l {
                padding-left: 0px !important
            }

            .es-m-p0t {
                padding-top: 0px !important
            }

            .es-m-p0b {
                padding-bottom: 0 !important
            }

            .es-m-p20b {
                padding-bottom: 20px !important
            }

            .es-mobile-hidden,
            .es-hidden {
                display: none !important
            }

            tr.es-desk-hidden,
            td.es-desk-hidden,
            table.es-desk-hidden {
                width: auto !important;
                overflow: visible !important;
                float: none !important;
                max-height: inherit !important;
                line-height: inherit !important
            }

            tr.es-desk-hidden {
                display: table-row !important
            }

            table.es-desk-hidden {
                display: table !important
            }

            td.es-desk-menu-hidden {
                display: table-cell !important
            }

            .es-menu td {
                width: 1% !important
            }

            table.es-table-not-adapt,
            .esd-block-html table {
                width: auto !important
            }

            table.es-social {
                display: inline-block !important
            }

            table.es-social td {
                display: inline-block !important
            }
        }
    </style>
</head>

<body
    style="width:100%;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0">
    <div class="es-wrapper-color" style="background-color:#FAFAFA">
        <table class="es-wrapper" width="100%" cellspacing="0" cellpadding="0"
            style="border-collapse:collapse;border-spacing:0px;padding:0;Margin:0;width:100%;height:100%;background-repeat:repeat;background-position:center top">
            <tbody>
                <tr style="border-collapse:collapse">
                    <td valign="top" style="padding:0;Margin:0">
                        <br>
                        <table cellpadding="0" cellspacing="0" class="es-header" align="center"
                            style="border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;background-color:transparent;background-repeat:repeat;background-position:center top">
                            <tbody>
                                <tr style="border-collapse:collapse">
                                    <td class="es-adaptive" align="center" style="padding:0;Margin:0">
                                        <table class="es-header-body"
                                            style="border-collapse:collapse;border-spacing:0px;background-color:whitesmoke;width:600px"
                                            cellspacing="0" cellpadding="0" bgcolor="whitesmoke" align="center">
                                            <tbody>
                                                <tr style="border-collapse:collapse">
                                                    <td style="Margin:0;padding-top:20px;padding-bottom:20px;padding-left:20px;padding-right:20px;background-color:whitesmoke"
                                                        bgcolor="whitesmoke" align="left">
                                                        <table class="es-left" cellspacing="0" cellpadding="0"
                                                            align="left"
                                                            style="border-collapse:collapse;border-spacing:0px;float:left">
                                                            <tbody>
                                                                <tr style="border-collapse:collapse">
                                                                    <td class="es-m-p20b" align="left"
                                                                        style="padding:0;Margin:0;width:270px">
                                                                        <table width="100%" cellspacing="0"
                                                                            cellpadding="0" role="presentation"
                                                                            style="border-collapse:collapse;border-spacing:0px">
                                                                            <tbody>
                                                                                <tr style="border-collapse:collapse">
                                                                                    <td class="es-m-p0l es-m-txt-c"
                                                                                        align="left"
                                                                                        style="padding:0;Margin:0;font-size:0">
                                                                                        <a href="#"
                                                                                            style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;font-size:14px;text-decoration:none;color:#1376C8"><img
                                                                                                src="{{ asset('loginn/img/logop.png') }}"
                                                                                                alt
                                                                                                style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic"
                                                                                                width="183"></a>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="es-content" cellspacing="0" cellpadding="0" align="center"
                            style="border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%">
                            <tbody>
                                <tr style="border-collapse:collapse">
                                    <td style="padding:0;Margin:0;background-color:#FAFAFA" bgcolor="#fafafa"
                                        align="center">
                                        <table class="es-content-body"
                                            style="border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;width:600px"
                                            cellspacing="0" cellpadding="0" bgcolor="#ffffff" align="center">
                                            <tbody>
                                                <tr style="border-collapse:collapse">
                                                    <td style="padding:0;Margin:0;padding-left:20px;padding-right:20px;padding-top:40px;background-color:transparent;background-position:left top"
                                                        bgcolor="transparent" align="left">
                                                        <table width="100%" cellspacing="0" cellpadding="0"
                                                            style="border-collapse:collapse;border-spacing:0px">
                                                            <tbody>
                                                                <tr style="border-collapse:collapse">
                                                                    <td valign="top" align="center"
                                                                        style="padding:0;Margin:0;width:560px">
                                                                        <table
                                                                            style="border-collapse:collapse;border-spacing:0px;background-position:left top"
                                                                            width="100%" cellspacing="0"
                                                                            cellpadding="0" role="presentation">
                                                                            <tbody>
                                                                                <tr style="border-collapse:collapse">
                                                                                    <td align="center"
                                                                                        style="padding:0;Margin:0;padding-top:5px;padding-bottom:5px;font-size:0">
                                                                                        <img src="https://tlr.stripocdn.email/content/guids/CABINET_dd354a98a803b60e2f0411e893c82f56/images/23891556799905703.png"
                                                                                            alt
                                                                                            style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic"
                                                                                            width="175">
                                                                                    </td>
                                                                                </tr>
                                                                                <tr style="border-collapse:collapse">
                                                                                    <td align="center"
                                                                                        style="padding:0;Margin:0;padding-top:15px;padding-bottom:15px">
                                                                                        <h1
                                                                                            style="Margin:0;line-height:24px;font-family:arial, 'helvetica neue', helvetica, sans-serif;font-size:20px;font-style:normal;font-weight:normal;color:#333333">
                                                                                            <strong>¿OLVIDASTE TU
                                                                                            </strong>
                                                                                        </h1>
                                                                                        <h1
                                                                                            style="Margin:0;line-height:24px;font-family:arial, 'helvetica neue', helvetica, sans-serif;font-size:20px;font-style:normal;font-weight:normal;color:#333333">
                                                                                            <strong>&nbsp;CONTRASEÑA?</strong>
                                                                                        </h1>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr style="border-collapse:collapse">
                                                                                    <td align="left"
                                                                                        style="padding:0;Margin:0;padding-left:40px;padding-right:40px">
                                                                                        <p
                                                                                            style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;font-size:16px;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;line-height:24px;color:#666666;text-align:center">
                                                                                            Hola,&nbsp;
                                                                                            {{-- {{ auth()->user()->name }}&nbsp;{{ auth()->user()->surname }} --}}
                                                                                        </p>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr style="border-collapse:collapse">
                                                                                    <td align="left"
                                                                                        style="padding:0;Margin:0;padding-right:35px;padding-left:40px">
                                                                                        <p
                                                                                            style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;font-size:16px;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;line-height:24px;color:#666666;text-align:center">
                                                                                            ¡Hay una solicitud de
                                                                                            restablecimiento de tu
                                                                                            contraseña!</p>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr style="border-collapse:collapse">
                                                                                    <td align="center"
                                                                                        style="padding:0;Margin:0;padding-top:25px;padding-left:40px;padding-right:40px">
                                                                                        <p
                                                                                            style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;font-size:16px;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;line-height:24px;color:#666666">
                                                                                            Si no realizó esta
                                                                                            solicitud, simplemente
                                                                                            ignore este correo
                                                                                            electrónico. De lo
                                                                                            contrario, haga clic en el
                                                                                            botón de
                                                                                            abajo para cambiar su
                                                                                            contraseña:</p>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr style="border-collapse:collapse">
                                                                                    <td align="center"
                                                                                        style="Margin:0;padding-left:10px;padding-right:10px;padding-top:40px;padding-bottom:40px">
                                                                                        <span class="es-button-border"
                                                                                            style="border-style:solid;border-color:whitesmoke;background:whitesmoke;border-width:2px;display:inline-block;border-radius:10px;width:auto">
                                                                                            Contraseña por defecto:
                                                                                            {{-- {{ $password_default }} --}}
                                                                                        </span>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr style="border-collapse:collapse">
                                                    <td style="Margin:0;padding-top:5px;padding-bottom:20px;padding-left:20px;padding-right:20px;background-position:left top"
                                                        align="left">
                                                        <table width="100%" cellspacing="0" cellpadding="0"
                                                            style="border-collapse:collapse;border-spacing:0px">
                                                            <tbody>
                                                                <tr style="border-collapse:collapse">
                                                                    <td valign="top" align="center"
                                                                        style="padding:0;Margin:0;width:560px">
                                                                        <table width="100%" cellspacing="0"
                                                                            cellpadding="0" role="presentation"
                                                                            style="border-collapse:collapse;border-spacing:0px">
                                                                            <tbody>
                                                                                <tr style="border-collapse:collapse">
                                                                                    <td align="left"
                                                                                        style="padding:0;Margin:0">
                                                                                        <p
                                                                                            style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;font-size:14px;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;line-height:21px;color:#666666">
                                                                                            Gracias: <br>
                                                                                            El equipo de sistemas del
                                                                                            Colegio San Gerardo
                                                                                        </p>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="es-footer" cellspacing="0" cellpadding="0" align="center"
                            style="border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;background-color:transparent;background-repeat:repeat;background-position:center top">
                            <tbody>
                                <tr style="border-collapse:collapse">
                                    <td style="padding:0;Margin:0;background-color:#FAFAFA" bgcolor="#fafafa"
                                        align="center">
                                        <table class="es-footer-body" cellspacing="0" cellpadding="0"
                                            bgcolor="#ffffff" align="center"
                                            style="border-collapse:collapse;border-spacing:0px;background-color:transparent;width:600px">
                                            <tbody>
                                                <tr style="border-collapse:collapse">
                                                    <td style="Margin:0;padding-top:10px;padding-left:20px;padding-right:20px;padding-bottom:30px;background-color:whitesmoke;background-position:left top"
                                                        bgcolor="whitesmoke" align="left">
                                                        <table width="100%" cellspacing="0" cellpadding="0"
                                                            style="border-collapse:collapse;border-spacing:0px">
                                                            <tbody>
                                                                <tr style="border-collapse:collapse">
                                                                    <td valign="top" align="center"
                                                                        style="padding:0;Margin:0;width:560px">
                                                                        <table width="100%" cellspacing="0"
                                                                            cellpadding="0" role="presentation"
                                                                            style="border-collapse:collapse;border-spacing:0px">
                                                                            <tbody>
                                                                                <tr style="border-collapse:collapse">
                                                                                    <td align="left"
                                                                                        style="padding:0;Margin:0;padding-top:5px;padding-bottom:5px">
                                                                                        <h2
                                                                                            style="Margin:0;line-height:19px;font-family:arial, 'helvetica neue', helvetica, sans-serif;font-size:16px;font-style:normal;font-weight:normal;color:black">
                                                                                            <strong>¿Tienes
                                                                                                consultas?</strong>
                                                                                        </h2>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr style="border-collapse:collapse">
                                                                                    <td align="left"
                                                                                        style="padding:0;Margin:0;padding-bottom:5px">
                                                                                        <p
                                                                                            style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;font-size:14px;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;line-height:21px;color:black">
                                                                                            Estamos aquí para ayudarte,
                                                                                            contactanos por <a
                                                                                                style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;font-size:14px;text-decoration:none;color:black"
                                                                                                href="#">Facebook</a>
                                                                                        </p>
                                                                                        <p
                                                                                            style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;font-size:14px;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;line-height:21px;color:black">
                                                                                            o <a style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;font-size:14px;text-decoration:none;color:black"
                                                                                                href="#">Whatsapp</a><br>
                                                                                        </p>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
