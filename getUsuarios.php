<?php

session_start();
include('./funcionesPersistenciaLogeado.php');

if (isset($_SESSION['usuario_groupo']) && $_SESSION['tipo_groupo'] == 0) {
    $data = [];
    $user = trim(filter_var($_POST['name'], FILTER_SANITIZE_STRING));
    $link = crearConexion();
    $query = "SELECT `username`, `foto` FROM `usuario` WHERE `username` LIKE '%$user%' and `username`!='" . $_SESSION['usuario_groupo'] . "' and tipo='0'";
    $result = mysqli_query($link, $query);
    cerrarConexion($link);
    while ($linea = mysqli_fetch_array($result)) {
        $data[] = [$linea['username'], $linea['foto']];
    }
    echo json_encode($data);
} else {
    echo '<h3>No tienes permiso para acceder al recurso</h3>';
}

