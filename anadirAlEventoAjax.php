<?php
//Relaciona un usuario con un evento añadiendo la relación en la base de datos, en la tabla usuario_evento.

session_start();
include('./funcionesPersistenciaLogeado.php');

//Solo se puede realizar dicha relación si esta logeado y no es administrador.
if (isset($_SESSION['usuario_groupo']) && $_SESSION['tipo_groupo'] == 0) {
    $data = [];

    //Se sanenan las entradas
    $evento = trim(filter_var($_POST['name'], FILTER_SANITIZE_STRING));
    $usuario = $_SESSION['usuario_groupo'];

    //Comprobamos si el evento existe y si existe realizamos la relación
    if (exiteEvento($evento)) {
        $idEvento = obtenerIdEvento($evento);
        
        $con = crearConexion();

        $query = "INSERT INTO usuario_evento(usuario, evento, propietario) VALUES ('$usuario', $idEvento, 0)";

        $result = mysqli_query($con, $query);

        cerrarConexion($con);

        //Para enviar los datos a ajax de jquery en formato json
        echo json_encode(TRUE);
    }
} else {
    echo '<h3>No tienes permiso para acceder al recurso</h3>';
}

