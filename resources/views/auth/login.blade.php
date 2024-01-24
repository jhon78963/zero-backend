<!DOCTYPE html>
<html lang="pt=br">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link rel="icon" type="image/png" href="{{ asset('loginn/img/icono.png') }}">

        <!--CSS-->
        <link rel="stylesheet" href="{{ asset('loginn/css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('loginn/css/media.css') }}">
    </head>

    <body>
        <form id="frmLogin">
            @csrf
            <div id="container">
                <div class="banner">
                    <img src="{{ asset('loginn/img/login.png') }}" alt="imagem-login">
                    <p style="color: #fff; font-weight: 400; text-align: center;">
                        Bienvenido, accede y disfruta de todo el contenido,
                        <br>Somos un equipo de profesionales comprometidos con
                        <br>brindarte la mejor herramienta para tu desarrollo académico.
                    </p>
                </div>

                <div class="box-login">
                    <h1>
                        ¡Hola!<br>
                        Bienvenido de nuevo.
                    </h1>
                    <div class="box">
                        <h2>Inicia sesión ahora</h2>

                        <input type="text" name="email" id="email" placeholder="email">
                        <div class="row" id="alertEmailError" style="display: none;">
                            <p id="emailError" style="font-weight: 500; color: red; font-size: 0.75rem;"></p>
                        </div>

                        <input type="password" name="password" id="password" placeholder="password">
                        <div class="row" id="alertPasswordError" style="display: none;">
                            <p id="passwordError" style="font-weight: 500; color: red; font-size: 0.75rem;"></p>
                        </div>

                        <div class="row" id="alertCredentialError" style="display: none;">
                            <p id="credentialError" style="font-weight: 500; color: red; font-size: 0.75rem;"></p>
                        </div>

                        <a href="{{ route('auth.password') }}">
                            <p>¿Olvidaste tu contraseña?</p>
                        </a>

                        <button type="submit" id="btnLogin">Login</button>

                        <a href="{{ route('auth.register') }}">
                            <p>¿No tienes una cuenta? Registrate
                            </p>
                        </a>

                        <div class="social">
                            <img src="{{ asset('loginn/img/facebook.png') }}" alt="facebook">
                            <img src="{{ asset('loginn/img/google.png') }}" alt="google">
                            <img src="{{ asset('loginn/img/twitter.png') }}" alt="twitter">
                            <img src="{{ asset('loginn/img/github.png') }}" alt="github">
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!--JS & jQuery-->
        <script type="text/javascript" src="{{ asset('loginn/js/script.js') }}"></script>
        {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
        <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            $(function() {
                enviarLogin();
            });

            var enviarLogin = function() {
                $("#frmLogin").on("submit", function(e) {
                    e.preventDefault();

                    $.ajax({
                        url: '{{ route('auth.validate') }}',
                        method: 'POST',
                        dataType: 'json',
                        data: new FormData($("#frmLogin")[0]),
                        contentType: false,
                        processData: false,
                        beforeSend: function() {
                            $("#alertError").hide();
                            $('#btnLogin').attr("disabled", true);
                            $('#btnLogin').html(
                                '<div class="spinner-border spinner-border-sm text-primary" role="status"></div> Verificando...'
                            );
                        },
                        success: function(data) {
                            $("#frmLogin")[0].reset();

                            if (data.status == 'success') {
                                $("#alertEmailError").hide();
                                $("#alertPasswordError").hide();
                                $("#alertCredentialError").hide();
                            }

                            let timerInterval
                            Swal.fire({
                                title: 'Login Exitoso!',
                                html: 'Ingresando al sistema en <b style="color:black"></b> milisegundos.',
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
                                if (result.dismiss === Swal.DismissReason.timer) {
                                    if (data.role == 1) {
                                        window.location.href =
                                            '{{ route('auth.home.principal') }}';
                                    } else {
                                        window.location.href = `/${data.period.name}/bienvenido`;
                                    }
                                }
                            })
                        },
                        error: function(data) {
                            let errores = data.responseJSON.errors;
                            let credentials = data.responseJSON.message;
                            let status = data.responseJSON.status;

                            if (data.responseJSON.exception != undefined && data.responseJSON
                                .exception == 'ErrorException') {
                                $("#credentialError").html(
                                    "El usuario no tiene un rol asignado");
                                $("#alertEmailError").hide();
                                $("#alertPasswordError").hide();
                                $("#alertCredentialError").show();
                            }

                            if (errores != undefined) {
                                if (errores.email != undefined) {
                                    $("#emailError").html(errores.email[0]);
                                    $("#alertEmailError").show();
                                    $("#alertPasswordError").hide();
                                    $("#alertCredentialError").hide();
                                }

                                if (errores.password != undefined) {
                                    $("#passwordError").html(errores.password[0]);
                                    $("#alertPasswordError").show();
                                    $("#alertCredentialError").hide();
                                    $("#alertCredentialError").hide();
                                }
                            }

                            if (credentials != undefined && status != undefined) {
                                $("#credentialError").html(credentials);
                                $("#alertEmailError").hide();
                                $("#alertPasswordError").hide();
                                $("#alertCredentialError").show();
                            }

                            $('#btnLogin').text('LOGIN');
                            $('#btnLogin').attr("disabled", false);
                        },
                        complete: function() {
                            $('#btnLogin').text('Login');
                            $('#btnLogin').attr("disabled", false);
                        },
                    });
                });
            }
        </script>
    </body>

</html>
