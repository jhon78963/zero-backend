<!DOCTYPE html>
<html lang="pt=br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
    <link rel="icon" type="image/png" href="{{ asset('loginn/img/icono.png') }}">

    <!--CSS-->
    <link rel="stylesheet" href="{{ asset('loginn/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('loginn/css/media.css') }}">

    <!--JS & jQuery-->
    <script type="text/javascript" src="{{ asset('loginn/js/script.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>

<body>
    <form id="frmRegister">
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
                <h1>Únetenos,<br>Crea tu cuenta hoy!</h1>

                <div class="box">
                    <h2>ingresa tus datos</h2>

                    <input type="text" name="name" id="name" placeholder="nombre">
                    <div class="row" id="alertNombreError" style="display: none;">
                        <p id="nombreError" style="font-weight: 500; color: red; font-size: 0.75rem;"></p>
                    </div>

                    <input type="text" name="email" id="email" placeholder="e-mail">
                    <div class="row" id="alertCorreoError" style="display: none;">
                        <p id="correoError" style="font-weight: 500; color: red; font-size: 0.75rem;"></p>
                    </div>

                    <input type="password" name="password" id="password" placeholder="contraseña">
                    <div class="row" id="alertPasswordError" style="display: none;">
                        <p id="passwordError" style="font-weight: 500; color: red; font-size: 0.75rem;"></p>
                    </div>

                    <input type="password" name="cpassword" id="cpassword" placeholder="confirmar contraseña">
                    <div class="row" id="alertConpasswordError" style="display: none;">
                        <p id="conPasswordError" style="font-weight: 500; color: red; font-size: 0.75rem;"></p>
                    </div>

                    <div class="check">
                        <input type="checkbox" name="termino" id="termino" style="width: 13px; height: 13px;">
                        <label for="terminos" style="color: #3d3d3d;">He leído y acepto los términos</label>
                    </div>

                    <div class="row" id="alertCredentialError" style="display: none;">
                        <p id="credentialError" style="font-weight: 500; color: red; font-size: 0.75rem;"></p>
                    </div>

                    <button type="submit" id="btnRegister">Crear cuenta</button>


                    <a href="{{ route('auth.login') }}">
                        <p>¿Ya tienes una cuenta? Iniciar sesión</p>
                    </a>

                </div>
            </div>
        </div>
    </form>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $('#termino').click(() => {
            const div = $('<div>').attr('style', `
                position: absolute;
                width: 65%;
                height: 420px;
                border-radius: 10px;
                background: #fff;

                color: #3d3d3d;

                -webkit-box-shadow: 0px 0px 6px -1px #000000;
                box-shadow: 0px 0px 6px -1px #000000;

                display: flex;
                flex-direction: column;
                justify-content: space-evenly;
                align-items: center;
            `)

            $(div).addClass('termino')

            $(div).append(`Eu entendo e aceitos os terminos impostos pela plataforma...`)

            $(div).append(
                $('<button>Ok</button>').click(() => {
                    div.hide()
                })
            )

            $('body').append(div)
        })
    </script>

    <script>
        $(function() {
            enviarLogin();
        });

        var enviarLogin = function() {
            $("#frmRegister").on("submit", function(e) {
                e.preventDefault();

                $.ajax({
                    url: '{{ route('auth.store') }}',
                    method: 'POST',
                    dataType: 'json',
                    data: new FormData($("#frmRegister")[0]),
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $("#alertError").hide();
                        $('#btnRegister').attr("disabled", true);
                        $('#btnRegister').html(
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Verificando...'
                        );
                    },
                    success: function(data) {
                        $("#frmRegister")[0].reset();

                        if (data.status == 'success') {
                            $("#alertCredentialError").hide();
                            $("#alertPasswordError").hide();
                            $("#alertCorreoError").hide();
                            $("#alertNombreError").hide();
                            $("#alertConpasswordError").hide();
                        }

                        let timerInterval
                        Swal.fire({
                            title: 'Login Exitoso!',
                            html: 'Ingresando al sistema en <b></b> milisegundos.',
                            timer: 700,
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
                                window.location.href = '{{ route('auth.home') }}';
                            }
                        });
                    },
                    error: function(data) {
                        console.log(data);
                        let errores = data.responseJSON.errors;
                        let credentials = data.responseJSON.message;
                        let status = data.responseJSON.status;

                        if (data.status == 422) {
                            if (errores.cpassword != undefined) {
                                $("#conPasswordError").html(errores.cpassword[0]);
                                $("#alertConpasswordError").show();
                                $("#alertCredentialError").hide();
                            } else {
                                $("#alertConpasswordError").hide();
                            }

                            if (errores.name != undefined) {
                                $("#nombreError").html(errores.name[0]);
                                $("#alertNombreError").show();
                                $("#alertCredentialError").hide();
                            } else {
                                $("#alertNombreError").hide();
                            }

                            if (errores.email != undefined) {
                                $("#correoError").html(errores.email[0]);
                                $("#alertCorreoError").show();
                                $("#alertCredentialError").hide();
                            } else {
                                $("#alertCorreoError").hide();
                            }

                            if (errores.password != undefined) {
                                $("#passwordError").html(errores.password[0]);
                                $("#alertPasswordError").show();
                                $("#alertCredentialError").hide();
                            } else {
                                $("#alertPasswordError").hide();
                            }
                        } else if (data.status == 401) {
                            if (credentials != undefined && status != undefined) {
                                $("#credentialError").html(credentials);
                                $("#alertCredentialError").show();
                                $("#alertPasswordError").hide();
                                $("#alertCorreoError").hide();
                                $("#alertNombreError").hide();
                                $("#alertConpasswordError").hide();
                            } else {
                                $("#alertCredentialError").hide();
                                $("#alertPasswordError").hide();
                                $("#alertCorreoError").hide();
                                $("#alertNombreError").hide();
                                $("#alertConpasswordError").hide();
                            }
                        }

                        $('#btnRegister').text('LOGIN');
                        $('#btnRegister').attr("disabled", false);
                    },
                    complete: function() {
                        $('#btnRegister').text('Login');
                        $('#btnRegister').attr("disabled", false);
                    },
                });
            });
        }
    </script>

</body>

</html>
