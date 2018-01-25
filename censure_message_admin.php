<?php
//Censurar un mensaje de un usuario.

session_start();
include './funcionesPersistenciaLogeado.php';

//Solo se puede realizar dicha relación si esta logeado y es administrador.
if (isset($_SESSION['usuario_groupo']) && $_SESSION['tipo_groupo'] == 1) {
    
    //Se sanenan las entradas
    $mensaje = explode("_", trim(filter_var($_POST['men'], FILTER_SANITIZE_STRING)));

    //Se realiza la modificación del campo censurado en la tabla mesaje, 
    //poniendolo a 1 para indicar que dicho mensaje ha sido censurado.
    $link = crearConexion();
    $query = "UPDATE `mensaje` SET `censurado`='1' WHERE `usu_remitente`='$mensaje[0]' AND `usu_destinatario`='$mensaje[1]' AND `fecha_hora`='$mensaje[2]'";
    mysqli_query($link, $query);
    cerrarConexion($link);
    
    //Para enviar los datos a ajax de jquery en formato json
    echo json_encode(TRUE);
} else {
    echo '<h3>No tienes permiso para acceder al recurso</h3>';
}
