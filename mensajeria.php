<?php
// Inserta un nuevo mensaje si el usuario está logueado y no es de tipo administrador.
session_start();
include('./funcionesPersistenciaLogeado.php');

if (isset($_SESSION['usuario_groupo']) && $_SESSION['tipo_groupo'] == 0) {
    $data = [];
    $destinatario = trim(filter_var($_POST['dest'], FILTER_SANITIZE_STRING));
    $remitente = trim(filter_var($_SESSION['usuario_groupo'], FILTER_SANITIZE_STRING));
    $texto = trim(filter_var($_POST['text'], FILTER_SANITIZE_STRING));
    if (exiteUsuario($destinatario)) {
        $link = crearConexion();
        $query = "INSERT INTO `mensaje`(`usu_remitente`, `usu_destinatario`, `texto`) VALUES ('$remitente','$destinatario','$texto')";
        mysqli_query($link, $query);
        cerrarConexion($link);
    }
    $data['nombre'] = $remitente;
    $data['texto'] = $texto;
    echo json_encode($data);
} else {
    echo '<h3>No tienes permiso para acceder al recurso</h3>';
}
