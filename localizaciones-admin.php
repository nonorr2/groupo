<?php
session_start();
require("./remote_php/funcionesPersistenciaLogeado.php");
//Si el usuario esta logueado o no lo esta se redirigen a otra página
if (!isset($_SESSION['usuario_groupo'])) {
    header("Location: index.php");
} else if ($_SESSION['tipo_groupo'] != 1) {
    header("Location: inicioLogeado.php");
}
?>
<?php include ('./cabecera-admin.php');
?>
<header class="bgimg-2 w3-display-container w3-opacity-min bgimg-general"  id="top">
    <div class="w3-display-middle" style="white-space:nowrap;">
        <span class="w3-center w3-padding-large w3-black w3-xlarge w3-wide w3-animate-opacity" style="font-family: 'Lobster Two', cursive">Localizaciones</span>
    </div>
</header>

<nav class="menuLateral">
    <input type="search" id="admin_search_location" placeholder="Localizacion" onkeyup="muestraLocalizaciones()"/>
    <input type="text" id="admin_new_location" placeholder="Nombre"/>
    <input type="button" id="create_location" class="create_location" value="Crear" onclick="creaLocalizacion()"/>
</nav>

<article id="list_locations" class="contenedor_derecha">

</article>

<script>
    $(document).ready(function () {
        muestraLocalizaciones();
    });
</script>
<?php include ('./footer.php'); ?>