<?php
// Elimina un grupo si el usuario está logueado y no es de tipo administrador.
session_start();
if (isset($_SESSION['usuario_groupo']) && $_SESSION['tipo_groupo'] == 0) {

    include('./funcionesPersistenciaLogeado.php');
    $data = [];

    $grupo = trim(filter_var($_POST['name'], FILTER_SANITIZE_STRING));
    unlink("../images/grupos/$grupo.jpeg");
    if (exiteGrupo($grupo)) {
        $con = crearConexion();

        $query = "DELETE FROM grupo WHERE nombre = '$grupo'";

        $result = mysqli_query($con, $query);

        cerrarConexion($con);
        
        echo json_encode(TRUE);
    }
} else {
    echo '<h3>No tienes permiso para acceder al recurso</h3>';
}