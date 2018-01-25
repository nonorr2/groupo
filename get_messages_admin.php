<?php
// Búsqueda de mensajes para el usuario administrador.
session_start();
include('./funcionesPersistenciaLogeado.php');

if (isset($_SESSION['usuario_groupo']) && $_SESSION['tipo_groupo'] == 1) {
    $data = [];
    $destinatario = trim(filter_var($_POST['dest'], FILTER_SANITIZE_STRING));
    $remitente = trim(filter_var($_POST['rem'], FILTER_SANITIZE_STRING));
    $desde = trim(filter_var($_POST['from'], FILTER_SANITIZE_STRING));
    $hasta = trim(filter_var($_POST['to'], FILTER_SANITIZE_STRING));

    $link = crearConexion();

    if ($desde != "" && $hasta != "" && $destinatario != "" && $remitente != "") {
        $query = "SELECT `usu_remitente`, `usu_destinatario`, `texto`, `fecha_hora`, censurado FROM `mensaje` WHERE `usu_remitente`='$remitente' AND `usu_destinatario`='$destinatario' AND `fecha_hora`>='$desde' AND fecha_hora<='$hasta' ORDER BY fecha_hora ASC";
    } elseif ($desde != "" && $hasta != "" && $destinatario != "" && $remitente == "") {
        $query = "SELECT `usu_remitente`, `usu_destinatario`, `texto`, `fecha_hora`, censurado FROM `mensaje` WHERE `usu_destinatario`='$destinatario' AND `fecha_hora`>='$desde' AND fecha_hora<='$hasta' ORDER BY fecha_hora ASC";
    } elseif ($desde != "" && $hasta != "" && $destinatario == "" && $remitente != "") {
        $query = "SELECT `usu_remitente`, `usu_destinatario`, `texto`, `fecha_hora`, censurado FROM `mensaje` WHERE `usu_remitente`='$remitente' AND `fecha_hora`>='$desde' AND fecha_hora<='$hasta' ORDER BY fecha_hora ASC";
    } elseif ($desde != "" && $hasta != "" && $destinatario == "" && $remitente == "") {
        $query = "SELECT `usu_remitente`, `usu_destinatario`, `texto`, `fecha_hora`, censurado FROM `mensaje` WHERE `fecha_hora`>='$desde' AND fecha_hora<='$hasta' ORDER BY fecha_hora ASC";
    } elseif ($desde != "" && $hasta == "" && $destinatario != "" && $remitente != "") {
        $query = "SELECT `usu_remitente`, `usu_destinatario`, `texto`, `fecha_hora`, censurado FROM `mensaje` WHERE `usu_remitente`='$remitente' AND `usu_destinatario`='$destinatario' AND `fecha_hora`>='$desde' ORDER BY fecha_hora ASC";
    } elseif ($desde != "" && $hasta == "" && $destinatario != "" && $remitente == "") {
        $query = "SELECT `usu_remitente`, `usu_destinatario`, `texto`, `fecha_hora`, censurado FROM `mensaje` WHERE `usu_destinatario`='$destinatario' AND `fecha_hora`>='$desde' ORDER BY fecha_hora ASC";
    } elseif ($desde != "" && $hasta == "" && $destinatario == "" && $remitente != "") {
        $query = "SELECT `usu_remitente`, `usu_destinatario`, `texto`, `fecha_hora`, censurado FROM `mensaje` WHERE `usu_remitente`='$remitente' AND `fecha_hora`>='$desde' ORDER BY fecha_hora ASC";
    } elseif ($desde != "" && $hasta == "" && $destinatario == "" && $remitente == "") {
        $query = "SELECT `usu_remitente`, `usu_destinatario`, `texto`, `fecha_hora`, censurado FROM `mensaje` WHERE `fecha_hora`>='$desde' ORDER BY fecha_hora ASC";
    } elseif ($desde == "" && $hasta != "" && $destinatario != "" && $remitente != "") {
        $query = "SELECT `usu_remitente`, `usu_destinatario`, `texto`, `fecha_hora`, censurado FROM `mensaje` WHERE `usu_remitente`='$remitente' AND `usu_destinatario`='$destinatario' AND fecha_hora<='$hasta' ORDER BY fecha_hora ASC";
    } elseif ($desde == "" && $hasta != "" && $destinatario != "" && $remitente == "") {
        $query = "SELECT `usu_remitente`, `usu_destinatario`, `texto`, `fecha_hora`, censurado FROM `mensaje` WHERE `usu_destinatario`='$destinatario' AND fecha_hora<='$hasta' ORDER BY fecha_hora ASC";
    } elseif ($desde == "" && $hasta != "" && $destinatario == "" && $remitente != "") {
        $query = "SELECT `usu_remitente`, `usu_destinatario`, `texto`, `fecha_hora`, censurado FROM `mensaje` WHERE `usu_remitente`='$remitente' AND fecha_hora<='$hasta' ORDER BY fecha_hora ASC";
    } elseif ($desde == "" && $hasta != "" && $destinatario == "" && $remitente == "") {
        $query = "SELECT `usu_remitente`, `usu_destinatario`, `texto`, `fecha_hora`, censurado FROM `mensaje` WHERE fecha_hora<='$hasta' ORDER BY fecha_hora ASC";
    } elseif ($desde == "" && $hasta == "" && $destinatario != "" && $remitente != "") {
        $query = "SELECT `usu_remitente`, `usu_destinatario`, `texto`, `fecha_hora`, censurado FROM `mensaje` WHERE `usu_remitente`='$remitente' AND `usu_destinatario`='$destinatario' ORDER BY fecha_hora ASC";
    } elseif ($desde != "" && $hasta != "" && $destinatario == "" && $remitente != "") {
        $query = "SELECT `usu_remitente`, `usu_destinatario`, `texto`, `fecha_hora`, censurado FROM `mensaje` WHERE `usu_remitente`='$remitente' AND `fecha_hora`>='$desde' AND fecha_hora<='$hasta' ORDER BY fecha_hora ASC";
    } elseif ($desde == "" && $hasta == "" && $destinatario == "" && $remitente != "") {
        $query = "SELECT `usu_remitente`, `usu_destinatario`, `texto`, `fecha_hora`, censurado FROM `mensaje` WHERE `usu_remitente`='$remitente' ORDER BY fecha_hora ASC";
    }

    $result = mysqli_query($link, $query);
    cerrarConexion($link);
    if (mysqli_num_rows($result) > 0) {
        while ($linea = mysqli_fetch_array($result)) {
            $aux['rem'] = $linea['usu_remitente'];
            $aux['dest'] = $linea['usu_destinatario'];
            $aux['texto'] = $linea['texto'];
            $aux['fecha_hora'] = $linea['fecha_hora'];
            $aux['censurado'] = $linea['censurado'];

            $data[] = $aux;
        }
    } else {
        $data = FALSE;
    }
    echo json_encode($data);
} else {
    echo '<h3>No tienes permiso para acceder al recurso</h3>';
}