<?php
//Cabecera para el usuario logeado
session_start();
require("./remote_php/funcionesPersistenciaLogeado.php");
//Si el usuario no esta logeado o es administrador se redirigen a otra página
if (!isset($_SESSION['usuario_groupo'])) {
    header("Location: index.php");
}else if($_SESSION['tipo_groupo'] == 1){
    header("Location: usuarios-admin.php");
}
?>
<?php
//Muestra los eventos para el usuario logeado
include('./cabecera-login.php');
?>

<header class="bgimg-2 w3-display-container w3-opacity-min bgimg-general" id="homeLogeado">
    <div class="w3-display-middle" style="white-space:nowrap;">
        <span class="w3-center w3-padding-large w3-black w3-xlarge w3-wide w3-animate-opacity" style="font-family: 'Lobster Two', cursive">Eventos</span>
    </div>
</header>


<!-- Menú lateral -->
<nav class="menuLateral">
    <ul>
        <li><a href="#" onclick="mostrarMisEventos()">Mis eventos</a></li>
        <li><a href="#" onclick="mostrarExplorarEventos()">Explorar</a></li>
        <li><a href="crearEventoLogeado.php">Crear evento</a></li>
    </ul>

    <input type="search" id="busquedaEvento" placeholder="Busqueda.." onkeyup="busquedaEvento()" />
</nav>

<!-- Mis eventos -->
<article class="contenedor_derecha" id="contenedorEventos">
    <script>
        $(document).ready(function () {
            mostrarMisEventos();
        });
    </script>
</article>

<?php include('./footer.php'); ?>
