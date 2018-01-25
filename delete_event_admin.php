<?php
//Se borra un evento de la base de datos.

session_start();
include './funcionesPersistenciaLogeado.php';

//Solo se puede realizar dicha relación si esta logeado y es administrador.
if (isset($_SESSION['usuario_groupo']) && $_SESSION['tipo_groupo'] == 1) {
    
    //Se sanean las entradas.
    $evento = trim(filter_var($_POST['event'], FILTER_SANITIZE_NUMBER_INT));
    $nombre = trim(filter_var($_POST['nom'], FILTER_SANITIZE_STRING));
    
    //Para borrar un elemento del sistemas de ficheros.
    unlink("../images/eventos/$nombre.jpeg");

    //Se realiza la consulta para borrar un evento de la base de datos.
    $link = crearConexion();
    $query = "DELETE FROM `evento` WHERE `id`='$evento'";
    mysqli_query($link, $query);
    cerrarConexion($link);

    //Para enviar los datos a ajax de jquery en formato json
    echo json_encode(TRUE);
} else {
    echo '<h3>No tienes permiso para acceder al recurso</h3>';
}