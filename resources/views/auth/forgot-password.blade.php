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

    <!--JS & jQuery-->
    <script type="text/javascript" src="{{ asset('loginn/js/script.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
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
                <h2>ingrese su cuenta existente</h2>
                <input type="text" name="username" id="username" placeholder="username">
                <input type="email" name="email" id="email" placeholder="e-mail">
                <input type="email" name="cmail" id="cmail" placeholder="confirmar e-mail">

                <p style="text-align: justify; padding: 0px 30px 0px 30px;">
                    Un código será enviado a su bandeja de
                    entrada, copie este código y péguelo en la
                    siguiente pantalla, asegúrese de que su
                    usuario y el email sean correctos.
                    y que sea igual a la cuenta que quieres
                    para recuperar
                </p>

                <button>Obtener código</button>

                <a href="{{ route('auth.login') }}">
                    <p>¿Recordaste tu contraseña? Iniciar sesión</p>
                </a>
            </div>
        </div>
    </div>
</body>

</html>
