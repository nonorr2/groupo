<?php
// Búsqueda de categorías para el usuario administrador.
session_start();
include('./funcionesPersistenciaLogeado.php');

if (isset($_SESSION['usuario_groupo']) && $_SESSION['tipo_groupo'] == 1) {
    $data = FALSE;
    $categoria = trim(filter_var($_POST['cat'], FILTER_SANITIZE_STRING));

    $link = crearConexion();

    $query = "SELECT nombre FROM `categoria` WHERE nombre='$categoria'";

    $result = mysqli_query($link, $query);
    cerrarConexion($link);
    if (mysqli_num_rows($result) > 0) {
        $data = TRUE;
    }
    echo json_encode($data);
} else {
    echo '<h3>No tienes permiso para acceder al recurso</h3>';
}