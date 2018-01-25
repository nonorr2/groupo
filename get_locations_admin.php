<?php
// Búsqueda de localizaciones para el usuario administrador.
session_start();
include('./funcionesPersistenciaLogeado.php');

if (isset($_SESSION['usuario_groupo']) && $_SESSION['tipo_groupo'] == 1) {
    $data = [];
    $nombre = trim(filter_var($_POST['nom'], FILTER_SANITIZE_STRING));

    $link = crearConexion();
    $query = "SELECT * FROM `localizacion` WHERE `ciudad` LIKE '%$nombre%'";
    $result = mysqli_query($link, $query);
    cerrarConexion($link);
    if (mysqli_num_rows($result) > 0) {
        while ($linea = mysqli_fetch_array($result)) {
            $row['id'] = $linea['id'];
            $row['ciudad'] = $linea['ciudad'];
            $data[] = $row;
        }
    } else {
        $data = FALSE;
    }

    echo json_encode($data);
} else {
    echo '<h3>No tienes permiso para acceder al recurso</h3>';
}