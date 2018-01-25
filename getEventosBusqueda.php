<?php
// Realiza la búsqueda de eventos según su nombre.
// Sólo se permitirá si el usuario está logueado y no es administrador.
// Además se comprueba si es el propietario para permitir la modificación. 
session_start();
include('./funcionesPersistenciaLogeado.php');

if (isset($_SESSION['usuario_groupo']) && $_SESSION['tipo_groupo'] == 0) {
    $grupos = [];
    $usuario = $_SESSION['usuario_groupo'];

    $nombre = trim(filter_var($_POST['nom'], FILTER_SANITIZE_STRING));

    $con = crearConexion();

    $query = "SELECT id, nombre, imagen, descripcion, fecha_hora FROM evento WHERE nombre LIKE '%$nombre%' ORDER BY nombre DESC";

    $result = mysqli_query($con, $query);

    while ($row = mysqli_fetch_array($result)) {
        $fila = [];

        $fila['nombre'] = $row['nombre'];
        $fila['imagen'] = $row['imagen'];
        $fila['descripcion'] = $row['descripcion'];
        $fila['fecha_hora'] = $row['fecha_hora'];
        $fila['id'] = $row['id'];

        $query2 = "SELECT propietario FROM usuario_evento WHERE evento='" . $fila['id'] . "' AND usuario='$usuario'";
        $result2 = mysqli_query($con, $query2);

        if (mysqli_num_rows($result2) < 1) {
            $fila['control'] = 2;
        } else {
            $row2 = mysqli_fetch_array($result2);
            if ($row2['propietario'] == 0) {
                $fila['control'] = 0;
            } else {
                $fila['control'] = 1;
            }
        }

        $grupos[] = $fila;
    }

    cerrarConexion($con);

    echo json_encode($grupos);
} else {
    echo '<h3>No tienes permiso para acceder al recurso</h3>';
}

