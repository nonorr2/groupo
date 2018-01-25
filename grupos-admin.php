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
<?php include ('./cabecera-admin.php'); ?>
<header class="bgimg-2 w3-display-container w3-opacity-min bgimg-general"  id="top">
    <div class="w3-display-middle" style="white-space:nowrap;">
        <span class="w3-center w3-padding-large w3-black w3-xlarge w3-wide w3-animate-opacity" style="font-family: 'Lobster Two', cursive">Grupos</span>
    </div>
</header>

<nav class="menuLateral">
    <input type="search" id="admin_search_group" placeholder="Nombre" onkeyup="muestraGrupos()"/>
    <p class="date_picker_text">Creado desde</p>
    <input type="date" id="fecha_desde" onchange="muestraGrupos()"/>
    <p  class="date_picker_text">Hasta</p>
    <input type="date" id="fecha_hasta" onchange="muestraGrupos()"/>
    <select name="categoria" id="admin_search_categoria" onchange="muestraGrupos()">
        <option value="">Todas las categorias</option>
        <?php
        $link = crearConexion();
        $query = "SELECT nombre FROM categoria";
        $result = mysqli_query($link, $query);
        while ($linea = mysqli_fetch_array($result)) {
            echo '<option value="' . $linea['nombre'] . '">' . $linea['nombre'] . '</option>';
        }
        cerrarConexion($link);
        ?>
    </select>
</nav>

<article id="list_groups" class="contenedor_derecha">

</article>

<script>
    $(document).ready(function () {
        muestraGrupos();
    });
</script>
<?php include ('./footer.php'); ?>