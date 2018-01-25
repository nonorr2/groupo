<?php
// Búsqueda de grupos para el usuario administrador.
session_start();
include('./funcionesPersistenciaLogeado.php');

if (isset($_SESSION['usuario_groupo']) && $_SESSION['tipo_groupo'] == 1) {
    $data = [];
    $grupo = trim(filter_var($_POST['grup'], FILTER_SANITIZE_STRING));
    $categoria = trim(filter_var($_POST['cat'], FILTER_SANITIZE_STRING));
    $desde = trim(filter_var($_POST['from'], FILTER_SANITIZE_STRING));
    $hasta = trim(filter_var($_POST['to'], FILTER_SANITIZE_STRING));

    $link = crearConexion();
    if ($desde != "" && $hasta != "") {
        $query = "SELECT `nombre`, `imagen`, `descripcion` FROM `grupo`,grupo_categoria WHERE grupo.nombre=grupo_categoria.grupo AND grupo.nombre LIKE '%$grupo%' AND grupo_categoria.categoria LIKE '%$categoria%' AND `f_creacion`>='$desde' AND `f_creacion`<='$hasta' ORDER BY nombre DESC";
    } else if ($desde != "" && $hasta == "") {
        $query = "SELECT `nombre`, `imagen`, `descripcion` FROM `grupo`,grupo_categoria WHERE grupo.nombre=grupo_categoria.grupo AND grupo.nombre LIKE '%$grupo%' AND grupo_categoria.categoria LIKE '%$categoria%' AND `f_creacion`>='$desde' ORDER BY nombre DESC";
    } else if ($desde == "" && $hasta != "") {
        $query = "SELECT `nombre`, `imagen`, `descripcion` FROM `grupo`,grupo_categoria WHERE grupo.nombre=grupo_categoria.grupo AND grupo.nombre LIKE '%$grupo%' AND grupo_categoria.categoria LIKE '%$categoria%' AND `f_creacion`<='$hasta' ORDER BY nombre DESC";
    } else {
        $query = "SELECT `nombre`, `imagen`, `descripcion` FROM `grupo`,grupo_categoria WHERE grupo.nombre=grupo_categoria.grupo AND grupo.nombre LIKE '%$grupo%' AND grupo_categoria.categoria LIKE '%$categoria%' ORDER BY nombre DESC";
    }
    $result = mysqli_query($link, $query);
    cerrarConexion($link);
    if (mysqli_num_rows($result) > 0) {
        while ($linea = mysqli_fetch_array($result)) {
            $aux['nombre'] = $linea['nombre'];
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