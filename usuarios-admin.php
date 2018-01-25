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
        <span class="w3-center w3-padding-large w3-black w3-xlarge w3-wide w3-animate-opacity" style="font-family: 'Lobster Two', cursive">Usuarios</span>
    </div>
</header>

<nav class="menuLateral">
    <input type="search" id="admin_search_user" placeholder="Usuario" onkeyup="muestraUsuarios()"/>
    <input type="search" id="admin_search_email" placeholder="Email" onkeyup="muestraUsuarios()"/>
    <input type="search" id="admin_search_name" placeholder="Nombre" onkeyup="muestraUsuarios()"/>
    <input type="search" id="admin_search_surname" placeholder="Apellidos" onkeyup="muestraUsuarios()"/>
    <select name="ciudad" id="admin_search_city" onchange="muestraUsuarios()">
        <option value="">Todas las ciudades</option>
        <?php
        $link = crearConexion();
        $query = "SELECT * FROM `localizacion`";
        $result = mysqli_query($link, $query);
        while ($linea = mysqli_fetch_array($result)) {
            echo '<option value="' . $linea['id'] . '">' . $linea['ciudad'] . '</option>';
        }
        cerrarConexion($link);
        ?>
    </select>
</nav>

<article id="list_users" class="contenedor_derecha">

</article>

<script>
    $(document).ready(function () {
        muestraUsuarios();
    });
</script>
<?php include ('./footer.php'); ?>