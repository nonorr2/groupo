<?php
//Crea una nueva localización en la base de datos, en la tabla localizacion.

session_start();
include './funcionesPersistenciaLogeado.php';

//Solo se puede realizar dicha relación si esta logeado y es administrador.
if (isset($_SESSION['usuario_groupo']) && $_SESSION['tipo_groupo'] == 1) {
    
    //Se sanean las entradas.
    $nombre = trim(filter_var($_POST['nom'], FILTER_SANITIZE_STRING));
    
    //Se comprueba la ciudad, si ya existe o el campo esta vacio no se crea la nueva localización
    if (!compruebaCiudad($nombre) || $nombre=="") {
        echo json_encode(FALSE);
    } else {
    //en el caso contrario, insertamos la nueva localización en la base de datos.    
        $link = crearConexion();
        $query = "INSERT INTO `localizacion`(`ciudad`) VALUES ('$nombre')";
        mysqli_query($link, $query);
        cerrarConexion($link);
        
        //Para enviar los datos a ajax de jquery en formato json
        echo json_encode(TRUE);
    }
} else {
    echo '<h3>No tienes permiso para acceder al recurso</h3>';
}