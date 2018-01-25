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
        <span class="w3-center w3-padding-large w3-black w3-xlarge w3-wide w3-animate-opacity" style="font-family: 'Lobster Two', cursive">Mensajes</span>
    </div>
</header>

<nav class="menuLateral">
    <p class="user_select_text">Remitente</p>
    <select name="categoria" id="admin_search_user_rem" onchange="muestraMensajes()">
        <option value=""></option>
        <?php
        $link = crearConexion();
        $query = "SELECT username FROM usuario";
        $result = mysqli_query($link, $query);
        while ($linea = mysqli_fetch_array($result)) {
            echo '<option value="' . $linea['username'] . '">' . $linea['username'] . '</option>';
        }
        ?>
    </select>
    <p class="user_select_text">Destinatario</p>
    <select name="categoria" id="admin_search_user_dest" onchange="muestraMensajes()">
        <option value=""></option>
        <?php
        $result = mysqli_query($link, $query);
        while ($linea = mysqli_fetch_array($result)) {
            echo '<option value="' . $linea['username'] . '">' . $linea['username'] . '</option>';
        }
        cerrarConexion($link);
        ?>
    </select>
    <p class="date_picker_text">Desde</p>
    <input type="date" id="fecha_desde" class="fecha_desde_hasta" onchange="muestraMensajes()"/>
    <input type="time" id="hora_desde" class="hora_desde_hasta" onchange="muestraMensajes()"/>
    <p  class="date_picker_text">Hasta</p>
    <input type="date" id="fecha_hasta" class="fecha_desde_hasta" onchange="muestraMensajes()"/>
    <input type="time" id="hora_hasta" class="hora_desde_hasta" onchange="muestraMensajes()"/>
</nav>

<article id="list_messages" class="contenedor_derecha">
    <h1>No has filtrado los resultados</h1>
</article>

<?php include ('./footer.php'); ?>