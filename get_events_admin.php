<?php
// Búsqueda de eventos para el usuario administrador.
session_start();
include './funcionesPersistenciaLogeado.php';
if (isset($_SESSION['usuario_groupo']) && $_SESSION['tipo_groupo'] == 1) {
    
    $data = [];
    $evento = trim(filter_var($_POST['event'], FILTER_SANITIZE_STRING));
    $categoria = trim(filter_var($_POST['cat'], FILTER_SANITIZE_STRING));
    $desde = trim(filter_var($_POST['from'], FILTER_SANITIZE_STRING));
    $hasta = trim(filter_var($_POST['to'], FILTER_SANITIZE_STRING));

    $link = crearConexion();
    if ($desde != "" && $hasta != "") {
        $query = "SELECT id, nombre, fecha_hora, imagen, descripcion FROM evento, evento_categoria WHERE evento.id=evento_categoria.evento AND evento.nombre LIKE '%$evento%' AND evento.fecha_hora>='$desde' AND evento.fecha_hora<='$hasta' AND evento_categoria.categoria LIKE '%$categoria%' ORDER BY fecha_hora DESC";
    } else if ($desde != "" && $hasta == "") {
        $query = "SELECT id, nombre, fecha_hora, imagen, descripcion FROM evento, evento_categoria WHERE evento.id=evento_categoria.evento AND evento.nombre LIKE '%$evento%' AND evento.fecha_hora>='$desde' AND evento_categoria.categoria LIKE '%$categoria%' ORDER BY fecha_hora DESC";  
    } else if ($desde == "" && $hasta != "") {
        $query = "SELECT id, nombre, fecha_hora, imagen, descripcion FROM evento, evento_categoria WHERE evento.id=evento_categoria.evento AND evento.nombre LIKE '%$evento%' AND evento.fecha_hora<='$hasta' AND evento_categoria.categoria LIKE '%$categoria%' ORDER BY fecha_hora DESC";
    } else {
        $query = "SELECT id, nombre, fecha_hora, imagen, descripcion FROM evento, evento_categoria WHERE evento.id=evento_categoria.evento AND evento.nombre LIKE '%$evento%' AND evento_categoria.categoria LIKE '%$categoria%' ORDER BY fecha_hora DESC";
    }
    $result = mysqli_query($link, $query);
    cerrarConexion($link);
    if (mysqli_num_rows($result) > 0) {
        while ($linea = mysqli_fetch_array($result)) {
            $aux['id'] = $linea['id'];
            $aux['nombre'] = $linea['nombre'];
            $aux['fecha_hora'] = $linea['fecha_hora'];
            $aux['imagen'] = $linea['imagen'];
            $aux['descripcion'] = $linea['descripcion'];

            $data[] = $aux;
        }
    } else {
        $data = FALSE;
    }
    echo json_encode($data);
} else {
    echo '<h3>No tienes permiso para acceder al recurso</h3>';
}