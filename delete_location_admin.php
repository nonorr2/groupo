<?php
//Se borra una localización de la base de datos.

session_start();
include './funcionesPersistenciaLogeado.php';

//Solo se puede realizar dicha relación si esta logeado y es administrador.
if (isset($_SESSION['usuario_groupo']) && $_SESSION['tipo_groupo'] == 1) {
    
    //Se sanean las entradas.
    $localizacion = trim(filter_var($_POST['loc'], FILTER_SANITIZE_NUMBER_INT));

    //Se realiza la eliminación de un localización en la base de datos.
    $link = crearConexion();
    $query = "DELETE FROM `localizacion` WHERE `id`='$localizacion'";
    mysqli_query($link, $query);
    cerrarConexion($link);
    
    //Para enviar los datos a ajax de jquery en formato json
    echo json_encode(TRUE);
} else {
    echo '<h3>No tienes permiso para acceder al recurso</h3>';
}