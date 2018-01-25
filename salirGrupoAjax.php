<?php
// Gestiona la salida de un grupo para un usuario.
session_start();
if (isset($_SESSION['usuario_groupo']) && $_SESSION['tipo_groupo'] == 0) {
    include('./funcionesPersistenciaLogeado.php');
    $data = [];

    $grupo = trim(filter_var($_POST['name'], FILTER_SANITIZE_STRING));
    $usuario = trim(filter_var($_SESSION['usuario_groupo'], FILTER_SANITIZE_STRING));

    if (exiteGrupo($grupo)) {
        $con = crearConexion();

        $query = "DELETE FROM usuario_grupo WHERE grupo = '$grupo' AND usuario = '$usuario'";

        $result = mysqli_query($con, $query);

        cerrarConexion($con);
        
        echo json_encode(TRUE);
    }
} else {
    echo '<h3>No tienes permiso para acceder al recurso</h3>';
}