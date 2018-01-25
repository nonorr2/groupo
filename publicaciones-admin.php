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
        <span class="w3-center w3-padding-large w3-black w3-xlarge w3-wide w3-animate-opacity" style="font-family: 'Lobster Two', cursive">Publicaciones</span>
    </div>
</header>

<nav class="menuLateral">
    <select name="usuario" id="admin_search_user" onchange="muestraPublicaciones()">
        <option value="">Todos los usuarios</option>
        <?php
        $usuarios = getUsersAdmin();
        foreach ($usuarios as $v) {
            echo '<option value="' . $v . '">' . $v . '</option>';
        }
        ?>
    </select>
    <select name="grupo" id="admin_search_group" onchange="muestraPublicaciones()">
        <option value="">Todos los grupos</option>
        <?php
        $categorias = leerTodosGrupos();
        foreach ($categorias as $v) {
            echo '<option value="' . $v['nombre'] . '">' . $v['nombre'] . '</option>';
        }
        ?>
    </select>
    <p class="date_picker_text">Desde</p>
    <input type="datetime-local" id="fecha_desde" onchange="muestraPublicaciones()"/>
    <p  class="date_picker_text">Hasta</p>
    <input type="datetime-local" id="fecha_hasta" onchange="muestraPublicaciones()"/>
</nav>

<article id="list_publicaciones" class="contenedor_derecha">
    <h1>No has filtrado los resultados</h1>
</article>

<?php include ('./footer.php'); ?>