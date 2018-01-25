<?php
//Censurar una publicación en un grupo de un usuario.

session_start();
include './funcionesPersistenciaLogeado.php';

//Solo se puede realizar dicha relación si esta logeado y es administrador.
if (isset($_SESSION['usuario_groupo']) && $_SESSION['tipo_groupo'] == 1) {
    
    //Se sanenan las entradas
    $id = trim(filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT));

    //Se realiza la modificación del campo censurada en la tabla publicacion, 
    //poniendolo a 1 para indicar que dicha publicación ha sido censurado.
    $link = crearConexion();
    $query = "UPDATE `publicacion` SET censurada='1' WHERE `id`='$id'";
    mysqli_query($link, $query);
    cerrarConexion($link);
    
    //Para enviar los datos a ajax de jquery en formato json
    echo json_encode(TRUE);
} else {
    echo '<h3>No tienes permiso para acceder al recurso</h3>';
}
