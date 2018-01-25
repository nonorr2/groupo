<?php
// Búsqueda de publicaciones para el usuario administrador.
session_start();
include('./funcionesPersistenciaLogeado.php');

if (isset($_SESSION['usuario_groupo']) && $_SESSION['tipo_groupo'] == 1) {
    $data = [];
    $usuario = trim(filter_var($_POST['usu'], FILTER_SANITIZE_STRING));
    $grupo = trim(filter_var($_POST['grup'], FILTER_SANITIZE_STRING));
    $desde = trim(filter_var($_POST['from'], FILTER_SANITIZE_STRING));
    $hasta = trim(filter_var($_POST['to'], FILTER_SANITIZE_STRING));

    $link = crearConexion();

    if ($desde != "" && $hasta != "" && $usuario != "" && $grupo != "") {
        $query = "SELECT `autor`, `grupo`, `fecha_hora`, `id`, `titulo`, `contenido`, `censurada` FROM `publicacion` WHERE `autor`='$usuario' AND `grupo`='$grupo' AND `fecha_hora`>='$desde' AND `fecha_hora`<='$hasta' ORDER BY fecha_hora DESC";
    }elseif ($desde != "" && $hasta != "" && $usuario != "" && $grupo == "") {
        $query = "SELECT `autor`, `grupo`, `fecha_hora`, `id`, `titulo`, `contenido`, `censurada` FROM `publicacion` WHERE `autor`='$usuario' AND `fecha_hora`>='$desde' AND `fecha_hora`<='$hasta' ORDER BY fecha_hora DESC";
    }elseif ($desde != "" && $hasta != "" && $usuario == "" && $grupo != "") {
        $query = "SELECT `autor`, `grupo`, `fecha_hora`, `id`, `titulo`, `contenido`, `censurada` FROM `publicacion` WHERE `grupo`='$grupo' AND `fecha_hora`>='$desde' AND `fecha_hora`<='$hasta' ORDER BY fecha_hora DESC";
    }elseif ($desde != "" && $hasta != "" && $usuario == "" && $grupo == "") {
        $query = "SELECT `autor`, `grupo`, `fecha_hora`, `id`, `titulo`, `contenido`, `censurada` FROM `publicacion` WHERE `fecha_hora`>='$desde' AND `fecha_hora`<='$hasta' ORDER BY fecha_hora DESC";
    }elseif ($desde != "" && $hasta == "" && $usuario != "" && $grupo != "") {
        $query = "SELECT `autor`, `grupo`, `fecha_hora`, `id`, `titulo`, `contenido`, `censurada` FROM `publicacion` WHERE `autor`='$usuario' AND `grupo`='$grupo' AND `fecha_hora`>='$desde' ORDER BY fecha_hora DESC";
    }elseif ($desde != "" && $hasta == "" && $usuario != "" && $grupo == "") {
        $query = "SELECT `autor`, `grupo`, `fecha_hora`, `id`, `titulo`, `contenido`, `censurada` FROM `publicacion` WHERE `autor`='$usuario' AND `fecha_hora`>='$desde' ORDER BY fecha_hora DESC";
    }elseif ($desde != "" && $hasta == "" && $usuario == "" && $grupo != "") {
        $query = "SELECT `autor`, `grupo`, `fecha_hora`, `id`, `titulo`, `contenido`, `censurada` FROM `publicacion` WHERE `grupo`='$grupo' AND `fecha_hora`>='$desde' ORDER BY fecha_hora DESC";
    }elseif ($desde != "" && $hasta == "" && $usuario == "" && $grupo == "") {
        $query = "SELECT `autor`, `grupo`, `fecha_hora`, `id`, `titulo`, `contenido`, `censurada` FROM `publicacion` WHERE `fecha_hora`>='$desde' ORDER BY fecha_hora DESC";
    }elseif ($desde == "" && $hasta != "" && $usuario != "" && $grupo != "") {
        $query = "SELECT `autor`, `grupo`, `fecha_hora`, `id`, `titulo`, `contenido`, `censurada` FROM `publicacion` WHERE `autor`='$usuario' AND `grupo`='$grupo' AND `fecha_hora`<='$hasta' ORDER BY fecha_hora DESC";
    }elseif ($desde == "" && $hasta != "" && $usuario != "" && $grupo == "") {
        $query = "SELECT `autor`, `grupo`, `fecha_hora`, `id`, `titulo`, `contenido`, `censurada` FROM `publicacion` WHERE `autor`='$usuario' AND `fecha_hora`<='$hasta' ORDER BY fecha_hora DESC";
    }elseif ($desde == "" && $hasta != "" && $usuario == "" && $grupo != "") {
        $query = "SELECT `autor`, `grupo`, `fecha_hora`, `id`, `titulo`, `contenido`, `censurada` FROM `publicacion` WHERE `grupo`='$grupo' AND `fecha_hora`<='$hasta' ORDER BY fecha_hora DESC";
    }elseif ($desde == "" && $hasta != "" && $usuario == "" && $grupo == "") {
        $query = "SELECT `autor`, `grupo`, `fecha_hora`, `id`, `titulo`, `contenido`, `censurada` FROM `publicacion` WHERE `fecha_hora`<='$hasta' ORDER BY fecha_hora DESC";
    }elseif ($desde == "" && $hasta == "" && $usuario != "" && $grupo != "") {
        $query = "SELECT `autor`, `grupo`, `fecha_hora`, `id`, `titulo`, `contenido`, `censurada` FROM `publicacion` WHERE `autor`='$usuario' AND `grupo`='$grupo' ORDER BY fecha_hora DESC";
    }elseif ($desde == "" && $hasta == "" && $usuario != "" && $grupo == "") {
        $query = "SELECT `autor`, `grupo`, `fecha_hora`, `id`, `titulo`, `contenido`, `censurada` FROM `publicacion` WHERE `autor`='$usuario' ORDER BY fecha_hora DESC";
    }elseif ($desde == "" && $hasta == "" && $usuario == "" && $grupo != "") {
        $query = "SELECT `autor`, `grupo`, `fecha_hora`, `id`, `titulo`, `contenido`, `censurada` FROM `publicacion` WHERE `grupo`='$grupo' ORDER BY fecha_hora DESC";
    }

    $result = mysqli_query($link, $query);
    cerrarConexion($link);
    if (mysqli_num_rows($result) > 0) {
        while ($linea = mysqli_fetch_array($result)) {
            $aux['autor'] = $linea['autor'];
            $aux['grupo'] = $linea['grupo'];
            $aux['id'] = $linea['id'];
            $aux['fecha_hora'] = $linea['fecha_hora'];
            $aux['titulo'] = $linea['titulo'];
            $aux['contenido'] = $linea['contenido'];
            $aux['censurada'] = $linea['censurada'];

            $data[] = $aux;
        }
    } else {
        $data = FALSE;
    }
    echo json_encode($data);
} else {
    echo '<h3>No tienes permiso para acceder al recurso</h3>';
}