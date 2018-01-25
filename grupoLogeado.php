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
include('./cabecera-login.php');
?>

<header class="bgimg-2 w3-display-container w3-opacity-min bgimg-general"  id="top">
    <div class="w3-display-middle" style="white-space:nowrap;">
        <span class="w3-center w3-padding-large w3-black w3-xlarge w3-wide w3-animate-opacity" style="font-family: 'Lobster Two', cursive">Grupos</span>
    </div>
</header>


<!-- Menú lateral -->
<nav class="menuLateral">
    <ul>
        <li><a href="#" onclick="mostrarMisGrupos()">Mis grupos</a></li>
        <li><a href="#" onclick="mostrarExplorarGrupos()">Explorar</a></li>
        <li><a href="crearGrupoLogeado.php">Crear grupo</a></li>
    </ul>

    <input type="search" id="busquedaGrupo" placeholder="Busqueda.." onkeyup="busquedaGrupo()" />
</nav>

<!-- Mis grupos -->
<article class="misGrupos" id="contenedorGrupos">
    <script>
        $(document).ready(function () {
            mostrarMisGrupos();
        });
    </script>
</article>

<?php include('./footer.php'); ?>