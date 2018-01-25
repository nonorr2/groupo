<?php
//Borra un evento de la base de datos, en la tabla evento.
session_start();

//Solo se puede realizar dicha relación si esta logeado y no es administrador.
if (isset($_SESSION['usuario_groupo']) && $_SESSION['tipo_groupo'] == 0) {

    include('./funcionesPersistenciaLogeado.php');
    $data = [];
    
    //Se filtra la entrada.
    $evento = trim(filter_var($_POST['name'], FILTER_SANITIZE_STRING));
    
    //Para borrar un elemento del sistemas de ficheros.
    unlink("../images/eventos/$evento.jpeg");
    
    //Se comprueba si el evento existe y se procede a borrarlo de la base de datos.
    if (exiteEvento($evento)) {
        $con = crearConexion();

        $query = "DELETE FROM evento WHERE nombre = '$evento'";

        $result = mysqli_query($con, $query);

        cerrarConexion($con);

        //Para enviar los datos a ajax de jquery en formato json
        echo json_encode(TRUE);
    }
} else {
    echo '<h3>No tienes permiso para acceder al recurso</h3>';
}

