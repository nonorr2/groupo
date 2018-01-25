<?php
//Se borra un mensaje de la base de datos.

session_start();
include './funcionesPersistenciaLogeado.php';

//Solo se puede realizar dicha relación si esta logeado y es administrador.
if (isset($_SESSION['usuario_groupo']) && $_SESSION['tipo_groupo'] == 1) {
    
    //Se sanean las entradas.
    $mensaje = explode("_", trim(filter_var($_POST['men'], FILTER_SANITIZE_STRING)));

    //Se realiza la eliminación de un mensaje entre dos usuarios de la base de datos.
    $link = crearConexion();
    $query = "DELETE FROM `mensaje` WHERE `usu_remitente`='$mensaje[0]' AND `usu_destinatario`='$mensaje[1]' AND `fecha_hora`='$mensaje[2]'";
    mysqli_query($link, $query);
    cerrarConexion($link);
    
    //Para enviar los datos a ajax de jquery en formato json
    echo json_encode(TRUE);
} else {
    echo '<h3>No tienes permiso para acceder al recurso</h3>';
}
