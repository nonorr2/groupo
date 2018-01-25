<?php
//Se borra un grupo de la base de datos.

session_start();
include './funcionesPersistenciaLogeado.php';

//Solo se puede realizar dicha relación si esta logeado y es administrador.
if (isset($_SESSION['usuario_groupo']) && $_SESSION['tipo_groupo'] == 1) {
    
    //Se sanean las entradas.
    $grupo = trim(filter_var($_POST['grup'], FILTER_SANITIZE_STRING));

    //Se realiza la eliminación de un grupo en la base de datos.
    $link = crearConexion();
    $query = "DELETE FROM `grupo` WHERE `nombre`='$grupo'";
    mysqli_query($link, $query);
    cerrarConexion($link);
    
    //Para borrar un elemento del sistemas de ficheros.
    unlink("../images/grupos/$grupo.jpeg");
    
    //Para enviar los datos a ajax de jquery en formato json
    echo json_encode(TRUE);
} else {
    echo '<h3>No tienes permiso para acceder al recurso</h3>';
}
