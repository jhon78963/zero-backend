<!DOCTYPE html>
<html lang="pt=br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña</title>
    <link rel="icon" type="image/png" href="{{ asset('loginn/img/icono.png') }}">

    <!--CSS-->
    <link rel="stylesheet" href="{{ asset('loginn/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('loginn/css/media.css') }}">
</head>

<body>
    <form id="frmRecovery">
        @csrf
        <div id="container">
            <div class="banner">
                <img src="{{ asset('loginn/img/login.png') }}" alt="imagem-login">
                <p style="color: #fff; text-align: center;">
                    Bienvenido, accede y disfruta de todo el contenido,
                    <br>Somos un equipo de profesionales comprometidos con
                    <br>brindarte la mejor herramienta para tu desarrollo académico.
                </p>
            </div>

            <div class="box-login">
                <h1>¿Perdiste tu contraseña?<br>recuperar por email ahora</h1>

                <div class="box">
                    <h2>ingrese su email existente</h2>
                    <input type="text" name="email" id="email" placeholder="e-mail">
                    <div class="row" id="alertEmailError" style="display: none;">
                        <p id="emailError" style="font-weight: 500; color: red; font-size: 0.75rem;"></p>
                    </div>
                    <div class="row" id="alertCredentialError" style="display: none;">
                        <p id="credentialError" style="font-weight: 500; color: red; font-size: 0.75rem;"></p>
                    </div>

                    <p style="text-align: justify; padding: 0px 30px 0px 30px;">
                        Se reiniciará su contraseña a una por defecto que le llegará a su bandeja de entrada,
                        por favor asegúrese de que su email sean correctos.
                        y que sea igual a la cuenta que quieres
                        para recuperar
                    </p>

                    <button type="submit" id="btnRecovery">Reiniciar</button>

                    <a href="{{ route('auth.login') }}">
                        <p>¿Recordaste tu contraseña? Iniciar sesión</p>
                    </a>
                </div>
            </div>
        </div>
    </form>

    <!--JS & jQuery-->
    <script type="text/javascript" src="{{ asset('loginn/js/script.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(function() {
            enviarLogin();
        });

        var enviarLogin = function() {
            $("#frmRecovery").on("submit", function(e) {
                e.preventDefault();

                $.ajax({
                    url: '{{ route('auth.recovery') }}',
                    method: 'POST',
                    dataType: 'json',
                    data: new FormData($("#frmRecovery")[0]),
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $("#alertError").hide();
                        $('#btnRecovery').attr("disabled", true);
                        $('#btnRecovery').html(
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Verificando...'
                        );
                    },
                    success: function(data) {
                        $("#frmRecovery")[0].reset();

                        if (data.status == 'success') {
                            $("#alertEmailError").hide();
                        }

                        let timerInterval
                        Swal.fire({
                            title: 'Envío Exitoso!',
                            html: 'Envíando mensaje en <b style="color:black"></b> milisegundos.',
                            timer: 1000,
                            timerProgressBar: true,
                            didOpen: () => {
                                Swal.showLoading()
                                const b = Swal.getHtmlContainer().querySelector('b')
                                timerInterval = setInterval(() => {
                                    b.textContent = Swal.getTimerLeft()
                                }, 100)
                            },
                            willClose: () => {
                                clearInterval(timerInterval)
                            }
                        }).then((result) => {
                            // if (result.dismiss === Swal.DismissReason.timer) {
                            //     window.location.href = '{{ route('auth.login') }}';
                            // }
                            Swal.fire({
                                html: 'Por favor, revise su bandeja de entrada o en su defecto en spam. Se le envío la contraseña reestablecida.',
                                confirmButtonText: 'Acceptar',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href =
                                        '{{ route('auth.login') }}';
                                }
                            })
                        });
                    },
                    error: function(data) {
                        let errores = data.responseJSON.errors;
                        let credentials = data.responseJSON.message;
                        let status = data.responseJSON.status;

                        if (errores != undefined) {
                            if (errores.email != undefined) {
                                $("#emailError").html(errores.email[0]);
                                $("#alertEmailError").show();
                                $("#alertCredentialError").hide();
                            }
                        }

                        if (credentials != undefined && status != undefined) {
                            $("#credentialError").html(credentials);
                            $("#alertEmailError").hide();
                            $("#alertCredentialError").show();
                        }

                        $('#btnRecovery').text('Reiniciar');
                        $('#btnRecovery').attr("disabled", false);
                    },
                    complete: function() {
                        $('#btnRecovery').text('Reiniciar');
                        $('#btnRecovery').attr("disabled", false);
                    },
                });
            });
        }
    </script>
</body>

</html>
