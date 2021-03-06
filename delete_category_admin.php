<?php
//Borra una categoría en la base de datos de la tabla catgoria.

session_start();
include './funcionesPersistenciaLogeado.php';

//Solo se puede realizar dicha relación si esta logeado y es administrador.
if (isset($_SESSION['usuario_groupo']) && $_SESSION['tipo_groupo'] == 1) {
    
    //Se sanean las entradas.
    $categoria = trim(filter_var($_POST['cat'], FILTER_SANITIZE_STRING));
    
    //Para borrar un elemento del sistemas de ficheros.
    unlink("../images/categorias/$categoria.jpg");
    
    //se borra la categoria de la base de datos.
    $link = crearConexion();
    $query = "DELETE FROM `categoria` WHERE `nombre`='$categoria'";
    mysqli_query($link, $query);
    cerrarConexion($link);
    
    //Para enviar los datos a ajax de jquery en formato json
    echo json_encode(TRUE);
} else {
    echo '<h3>No tienes permiso para acceder al recurso</h3>';
}