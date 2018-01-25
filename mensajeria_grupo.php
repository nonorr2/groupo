<?php
// Inserta un nuevo mensaje de grupo si el usuario está logueado y no es de tipo administrador.
session_start();
include('./funcionesPersistenciaLogeado.php');

if (isset($_SESSION['usuario_groupo']) && $_SESSION['tipo_groupo'] == 0) {
    $data = [];
    $titulo = trim(filter_var($_POST['tit'], FILTER_SANITIZE_STRING));
    $texto = trim(filter_var($_POST['text'], FILTER_SANITIZE_STRING));
    $grupo = trim(filter_var($_POST['grup'], FILTER_SANITIZE_STRING));
    $usuario = trim(filter_var($_SESSION['usuario_groupo'],FILTER_SANITIZE_STRING));

    if (perteneceGrupo($usuario, $grupo)) {
        $link = crearConexion();
        $query = "INSERT INTO `publicacion`(`autor`, `grupo`, `titulo`, `contenido`) VALUES ('$usuario','$grupo','$titulo','$texto')";
        mysqli_query($link, $query);
        $query2 = "SELECT `fecha_hora` FROM `publicacion` WHERE `autor`='$usuario' AND `grupo`='$grupo' ORDER BY `fecha_hora` DESC LIMIT 1";
        $result = mysqli_query($link, $query2);
        cerrarConexion($link);
        $data['fecha_hora'] = mysqli_fetch_array($result)['fecha_hora'];
        $data['foto'] = $_SESSION['foto_groupo'];
        $data['usuario'] = $_SESSION['usuario_groupo'];
        $data['titulo'] = $titulo;
        $data['contenido'] = $texto;
        echo json_encode($data);
    } else {
        echo json_encode(FALSE);
    }
} else {
    echo '<h3>No tienes permiso para acceder al recurso</h3>';
}
