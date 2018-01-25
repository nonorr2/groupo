<?php
// Elimina la censura de una publicación.
session_start();
include('./funcionesPersistenciaLogeado.php');

if (isset($_SESSION['usuario_groupo']) && $_SESSION['tipo_groupo'] == 1) {
    $id = trim(filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT));

    $link = crearConexion();
    $query = "UPDATE `publicacion` SET censurada='0' WHERE `id`='$id'";
    mysqli_query($link, $query);
    cerrarConexion($link);
    
    echo json_encode(TRUE);
} else {
    echo '<h3>No tienes permiso para acceder al recurso</h3>';
}