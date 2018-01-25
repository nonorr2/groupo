<?php
require("./remote_php/funcionesPaginacion.php");
require("./remote_php/constantes.php");
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Groupo</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="style.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://fonts.googleapis.com/css?family=Lobster+Two" rel="stylesheet">
        <link rel="icon" type="image/png" href="images/icon.png" />
        <script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="funciones.js"></script>

        <?php
        if (isset($_POST['envio_login'])) {
            $user = filter_var($_POST['user'], FILTER_SANITIZE_STRING);
            $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

            $link = crearConexion();
            $query = "SELECT `password`, `foto`, `tipo`, `ultimo_acceso` FROM `usuario` WHERE `username`='$user'";
            $result = mysqli_query($link, $query);
            $linea = mysqli_fetch_array($result);
            cerrarConexion($link);

            if (mysqli_num_rows($result) > 0) {
                if (password_verify($password, $linea['password'])) {
                    $_SESSION['usuario_groupo'] = $user;
                    $_SESSION['tipo_groupo'] = $linea['tipo'];
                    $_SESSION['foto_groupo'] = $linea['foto'];
                    $_SESSION['ultima_sesion_groupo'] = $linea['ultimo_acceso'];

                    $link = crearConexion();
                    $query = "UPDATE `usuario` SET `ultimo_acceso`=now() WHERE `username`='$user'";
                    mysqli_query($link, $query);
                    cerrarConexion($link);

                    setcookie('user', $user, 15770000);
                    if ($_SESSION['tipo_groupo'] == 1) {
                        header("Location: usuarios-admin.php");
                    } else {
                        header("Location: inicioLogeado.php");
                    }
                } else {
                    $error_login = TRUE;
                }
            } else {
                $error_login = TRUE;
            }
        }
        ?>
        <script>
            // Change style of navbar on scroll
            window.onscroll = function () {
                myFunction()
            };
            function myFunction() {
                var navbar = document.getElementById("myNavbar");
                if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
                    navbar.className = "w3-bar" + " w3-card" + " w3-animate-top" + " w3-white";
                } else {
                    navbar.className = navbar.className.replace(" w3-card w3-animate-top w3-white", "");
                }
            }

            // Used to toggle the menu on small screens when clicking on the menu button
            function toggleFunction() {
                var x = document.getElementById("navDemo");
                if (x.className.indexOf("w3-show") == -1) {
                    x.className += " w3-show";
                } else {
                    x.className = x.className.replace(" w3-show", "");
                }
            }
        </script>
    </head>
    <body>
        <nav class="w3-top" id="navegacion_superior">

            <!-- Navbar (sit on top) -->
            <div class="w3-bar" id="myNavbar">
                <a class="w3-bar-item w3-button w3-hover-black w3-hide-medium w3-hide-large w3-right" href="javascript:void(0);" onclick="toggleFunction()" title="Toggle Navigation Menu">
                    <i class="fa fa-bars"></i>
                </a>
                <a href="index.php" class="w3-bar-item w3-button w3-hide-small">HOME</a>
                <a href="grupos.php" class="w3-bar-item w3-button w3-hide-small">GRUPOS</a>
                <a href="eventos.php" class="w3-bar-item w3-button w3-hide-small">EVENTOS</a>
                <a href="registro.php" class="w3-bar-item w3-button w3-hide-small">REGISTRATE</a>

                <div class="w3-dropdown-hover w3-right"><a class="w3-bar-item w3-button w3-hide-small ">LOGIN</a>
                    <div class="w3-dropdown-content w3-card-4 w3-bar-block" style="width:300px">
                        <div id="content_inputs_login">
                            <form method="POST" class="login_form">
                                <input type="text" placeholder="Usuario" name="user"/>
                                <input type="password" placeholder="Password" name="password"/>
                                <input class="login_submit" type="submit" value="Login" name="envio_login" />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Navbar on small screens -->
            <div id="navDemo" class="w3-bar-block w3-white w3-hide w3-hide-large w3-hide-medium">
                <a href="index.php" class="w3-bar-item w3-button" onclick="toggleFunction()">HOME</a>
                <a href="grupos.php" class="w3-bar-item w3-button" onclick="toggleFunction()">GRUPOS</a>
                <a href="eventos.php" class="w3-bar-item w3-button" onclick="toggleFunction()">EVENTOS</a>
                <div class="w3-dropdown-hover"><a href="#contact" class="w3-bar-item w3-button" onclick="toggleFunction()">LOGIN</a>
                    <div class="w3-dropdown-content w3-card-4 w3-bar-block" style="width:300px">
                        <div id="content_inputs_login">
                            <form method="POST" class="login_form">
                                <input type="text" placeholder="Usuario" name="user"/>
                                <input type="password" placeholder="Password" name="password"/>
                                <input class="login_submit" type="submit" value="Login" name="envio_login" />
                            </form>
                        </div>
                    </div>
                </div>
                <a href="registro.php" class="w3-bar-item w3-button" onclick="toggleFunction()">REGISTRARSE</a>
            </div>
        </nav>