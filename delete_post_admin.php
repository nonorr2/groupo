<?php
//Se borra una publicación de la base de datos.

session_start();
include './funcionesPersistenciaLogeado.php';

//Solo se puede realizar dicha relación si esta logeado y es administrador.
if (isset($_SESSION['usuario_groupo']) && $_SESSION['tipo_groupo'] == 1) {
    
    //Se sanean las entradas.
    $id = trim(filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT));

    //Se realiza la eliminación de una publicación de la tabla publicacion, en la base de datos.
    $link = crearConexion();
    $query = "DELETE FROM `publicacion` WHERE `id`='$id'";
    mysqli_query($link, $query);
    cerrarConexion($link);
    
    //Para enviar los datos a ajax de jquery en formato json
    echo json_encode(TRUE);
} else {
    echo '<h3>No tienes permiso para acceder al recurso</h3>';
}