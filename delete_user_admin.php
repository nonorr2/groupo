<?php
//Se borra un usuario de la base de datos.

session_start();
include './funcionesPersistenciaLogeado.php';

//Solo se puede realizar dicha relación si esta logeado y es administrador.
if (isset($_SESSION['usuario_groupo']) && $_SESSION['tipo_groupo'] == 1) {
    
    //Se sanean las entradas.
    $usuario = trim(filter_var($_POST['usu'], FILTER_SANITIZE_STRING));

    //Se elimina un usuario de la base de datos.
    $link = crearConexion();
    $query = "SELECT `foto` FROM `usuario` WHERE `username`='$usuario'";
    $foto = mysqli_query($link, $query);
    $query2 = "DELETE FROM `usuario` WHERE `username`='$usuario'";
    mysqli_query($link, $query2);
    cerrarConexion($link);
    unlink("../images/user_images/$usuario.jpeg");
    
    //Para enviar los datos a ajax de jquery en formato json
    echo json_encode(TRUE);
}else{
    echo '<h3>No tienes permiso para acceder al recurso</h3>';
}
