<?php
// Obtiene los eventos a los que está inscrito.
session_start();
include 'funcionesPersistenciaLogeado.php';

if (isset($_SESSION['usuario_groupo']) && $_SESSION['tipo_groupo'] == 0) {
    $data = leerMisEventos($_SESSION['usuario_groupo']);
    echo json_encode($data);
} else {
    echo '<h3>No tienes permiso para acceder al recurso</h3>';
}

