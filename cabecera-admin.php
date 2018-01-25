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
                <a href="usuarios-admin.php" class="w3-bar-item w3-button">USUARIOS</a>
                <a href="grupos-admin.php" class="w3-bar-item w3-button w3-hide-small">GRUPOS</a>
                <a href="eventos-admin.php" class="w3-bar-item w3-button w3-hide-small">EVENTOS</a>
                <a href="mensajes-admin.php" class="w3-bar-item w3-button w3-hide-small">MENSAJES</a>
                <a href="categorias-admin.php" class="w3-bar-item w3-button w3-hide-small">CATEGORIAS</a>
                <a href="publicaciones-admin.php" class="w3-bar-item w3-button w3-hide-small">PUBLICACIONES</a>
                <a href="localizaciones-admin.php" class="w3-bar-item w3-button w3-hide-small">LOCALIZACIONES</a>

                <div class="w3-dropdown-hover w3-right"><a href="#"><img id ="user_image" src="<?php echo $_SESSION['foto_groupo']; ?>" /></a>
                    <div class="w3-dropdown-content w3-card-4 w3-bar-block" style="width:150px">
                        <a href="logout.php" class="w3-bar-item w3-button">Salir</a>
                    </div>
                </div>
            </div>

            <!-- Navbar on small screens -->
            <div id="navDemo" class="w3-bar-block w3-white w3-hide w3-hide-large w3-hide-medium">
                <a href="usuarios-admin.php" class="w3-bar-item w3-button" onclick="toggleFunction()">USUARIOS</a>
                <a href="grupos-admin.php" class="w3-bar-item w3-button" onclick="toggleFunction()">GRUPOS</a>
                <a href="eventos-admin.php" class="w3-bar-item w3-button" onclick="toggleFunction()">EVENTOS</a>
                <a href="mensajes-admin.php" class="w3-bar-item w3-button" onclick="toggleFunction()">MENSAJES</a>
                <a href="categorias-admin.php" class="w3-bar-item w3-button" onclick="toggleFunction()">CATEGORIAS</a>
                <a href="publicaciones-admin.php" class="w3-bar-item w3-button" onclick="toggleFunction()">PUBLICACIONES</a>
                <a href="localizaciones-admin.php" class="w3-bar-item w3-button" onclick="toggleFunction()">LOCALIZACIONES</a>
            </div>
        </nav>