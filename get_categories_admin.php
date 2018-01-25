<?php
// Búsqueda de categorías para el usuario administrador.
session_start();
include './funcionesPersistenciaLogeado.php';

if (isset($_SESSION['usuario_groupo']) && $_SESSION['tipo_groupo'] == 1) {
    
    $data = [];

    $categoria = trim(filter_var($_POST['cat'], FILTER_SANITIZE_STRING));
  
    $link = crearConexion();
    
    $query = "SELECT * FROM `categoria` WHERE `nombre` LIKE '%$categoria%' ORDER BY `nombre` DESC";

    $result = mysqli_query($link, $query);
    
    if (mysqli_num_rows($result) > 0) {
        while ($linea = mysqli_fetch_array($result)) {
            $aux = [];
            $aux['nombre'] = $linea['nombre'];
            $aux['imagen'] = $linea['imagen'];
            $aux['descripcion'] = $linea['descripcion'];

            $data[] = $aux;
        }
        
    } else {
        $data = FALSE;
    }
    cerrarConexion($link);
    echo json_encode($data);
} else {
    echo '<h3>No tienes permiso para acceder al recurso</h3>';
}