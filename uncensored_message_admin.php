<?php
// Elimina la censura de un mensaje.
session_start();
include('./funcionesPersistenciaLogeado.php');

if (isset($_SESSION['usuario_groupo']) && $_SESSION['tipo_groupo'] == 1) {
    $mensaje = explode("_", trim(filter_var($_POST['men'], FILTER_SANITIZE_STRING)));

    $link = crearConexion();
    $query = "UPDATE `mensaje` SET `censurado`='0' WHERE `usu_remitente`='$mensaje[0]' AND `usu_destinatario`='$mensaje[1]' AND `fecha_hora`='$mensaje[2]'";
    mysqli_query($link, $query);
    cerrarConexion($link);
    
    echo json_encode(TRUE);
} else {
    echo '<h3>No tienes permiso para acceder al recurso</h3>';
}