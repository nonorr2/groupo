<?php
// Actualiza una localización.
session_start();
include './funcionesPersistenciaLogeado.php';

if (isset($_SESSION['usuario_groupo']) && $_SESSION['tipo_groupo'] == 1) {
    $nombre = trim(filter_var($_POST['nom'], FILTER_SANITIZE_STRING));
    $id = trim(filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT));
    if (!compruebaCiudad($nombre) || $nombre=="") {
        echo json_encode(FALSE);
    } else {
        $link = crearConexion();
        $query = "UPDATE `localizacion` SET `ciudad`='$nombre' WHERE `id`='$id'";
        mysqli_query($link, $query);
        cerrarConexion($link);
        echo json_encode(TRUE);
    }
} else {
    echo '<h3>No tienes permiso para acceder al recurso</h3>';
}