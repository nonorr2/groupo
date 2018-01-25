<?php
// Gestiona la salida de un evento para un usuario.
session_start();
if (isset($_SESSION['usuario_groupo']) && $_SESSION['tipo_groupo'] == 0) {
    include('./funcionesPersistenciaLogeado.php');
    $data = [];

    $evento = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $usuario = $_SESSION['usuario_groupo'];

    if (exiteEvento($evento)) {
        $idEvento = obtenerIdEvento($evento);

        $con = crearConexion();

        $query = "DELETE FROM usuario_evento WHERE evento = $idEvento AND usuario = '$usuario'";

        $result = mysqli_query($con, $query);

        cerrarConexion($con);
        
        echo json_encode(TRUE);
    }
} else {
    echo '<h3>No tienes permiso para acceder al recurso</h3>';
}
