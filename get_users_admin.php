<?php
// Búsqueda de usuarios para el usuario administrador.
session_start();
include('./funcionesPersistenciaLogeado.php');

if (isset($_SESSION['usuario_groupo']) && $_SESSION['tipo_groupo'] == 1) {
    $data = [];
    $usuario = trim(filter_var($_POST['usu'], FILTER_SANITIZE_STRING));
    $email = trim(filter_var($_POST['mail'], FILTER_SANITIZE_STRING));
    $nombre = trim(filter_var($_POST['nom'], FILTER_SANITIZE_STRING));
    $apellidos = trim(filter_var($_POST['apell'], FILTER_SANITIZE_STRING));
    $ciudad = trim(filter_var($_POST['ciu'], FILTER_SANITIZE_NUMBER_INT));

    $link = crearConexion();
    $query = "SELECT `username`, `nombre`, `apellidos`, `email`, `sexo`, `f_nacimiento`, `foto`, `ciudad` FROM `usuario` WHERE usuario.username LIKE '%$usuario%' AND usuario.nombre LIKE '%$nombre%' and usuario.apellidos LIKE '%$apellidos%' AND usuario.email LIKE '%$email%' AND usuario.ciudad LIKE '%$ciudad%' AND usuario.tipo!='1' ORDER BY usuario.nombre DESC";
    $result = mysqli_query($link, $query);
    cerrarConexion($link);
    if (mysqli_num_rows($result) > 0) {
        while ($linea = mysqli_fetch_array($result)) {
            $aux['usuario'] = $linea['username'];
            $aux['foto'] = $linea['foto'];

            $data[] = $aux;
        }
    } else {
        $data = FALSE;
    }

    echo json_encode($data);
} else {
    echo '<h3>No tienes permiso para acceder al recurso</h3>';
}