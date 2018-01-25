<?php

require ('funcionesPersistencia.php');

// Obtiene los grupos en los que ha habido novedades desde el último acceso.
function leerGruposNovedades($usuario, $ultima_sesion) {
    $usuarioSaneado = trim(filter_var($usuario, FILTER_SANITIZE_STRING));
    $ultima_sesion_saneado = trim(filter_var($ultima_sesion, FILTER_SANITIZE_STRING));

    $contenido = [];
    $con = crearConexion();
    $query = "SELECT usuario_grupo.grupo, COUNT(publicacion.titulo) AS numero, grupo.imagen, grupo.descripcion FROM usuario_grupo,publicacion,grupo,usuario WHERE usuario='$usuarioSaneado' AND usuario.username=usuario_grupo.usuario AND usuario_grupo.grupo=grupo.nombre AND grupo.nombre=publicacion.grupo AND publicacion.fecha_hora>'$ultima_sesion_saneado' LIMIT 3";
    $result = mysqli_query($con, $query);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $nombre = $row['grupo'];
            $numero = $row['numero'];
            $imagen = $row['imagen'];
            $desc = $row['descripcion'];
            $contenido[] = [$nombre, $numero, $imagen, $desc];
        }
    } else {
        $contenido = FALSE;
    }

    cerrarConexion($con);

    return $contenido;
}

// Obtiene los eventos para los que no está inscrito el usuario.
function eventosNoInscrito($usuario) {
    $usuarioSaneado = trim(filter_var($usuario, FILTER_SANITIZE_STRING));

    $eventos = [];
    $con = crearConexion();
    $query = "SELECT id, nombre, imagen, fecha_hora, descripcion FROM evento ORDER BY fecha_hora ASC LIMIT 3";
    $result = mysqli_query($con, $query);
    while ($linea = mysqli_fetch_array($result)) {
        $eventos[] = [$linea['id'], $linea['nombre'], $linea['fecha_hora'], $linea['descripcion'], $linea['imagen']];
    }
    cerrarConexion($con);
    return $eventos;
}

// Comprueba si el usuario existe en el sistema.
function exiteUsuario($usuario) {
    $usuarioSaneado = trim(filter_var($usuario, FILTER_SANITIZE_STRING));

    $link = crearConexion();
    $query = "SELECT `username` FROM `usuario` WHERE `username`='$usuarioSaneado'";
    $result = mysqli_query($link, $query);
    cerrarConexion($link);
    return mysqli_num_rows($result) > 0;
}

// Actualiza los datos de un usuario.
function modificarUsuario($nombre, $apellidos, $email, $sexo, $fech_nac, $ruta, $ciudad, $userActual) {
    $nombreSaneado = trim(filter_var($nombre, FILTER_SANITIZE_STRING));
    $apellidosSaneado = trim(filter_var($apellidos, FILTER_SANITIZE_STRING));
    $emailSaneado = trim(filter_var($email, FILTER_SANITIZE_EMAIL));
    $sexoSaneado = trim(filter_var($sexo, FILTER_SANITIZE_STRING));
    $fech_nacSaneado = trim(filter_var($fech_nac, FILTER_SANITIZE_STRING));
    $rutaSaneado = trim(filter_var($ruta, FILTER_SANITIZE_STRING));
    $ciudadSaneado = trim(filter_var($ciudad, FILTER_SANITIZE_STRING));
    $userActualSaneado = trim(filter_var($userActual, FILTER_SANITIZE_STRING));

    $con = crearConexion();
    $query = "UPDATE `usuario` SET `nombre`='$nombreSaneado',`apellidos`='$apellidosSaneado',`email`='$emailSaneado',`sexo`='$sexoSaneado',`f_nacimiento`='$fech_nacSaneado',`foto`='$rutaSaneado',`ciudad`='$ciudadSaneado',`tipo`= 0 WHERE `username` = '$userActualSaneado'";
    if (mysqli_query($con, $query)) {
        return TRUE;
    } else {
        return FALSE;
    }
    cerrarConexion($con);
}

// Compueba el formulario de modificación de usuario y si todo es correcto, lo persiste.
function comprobarPerfil($userActual) {

    $nombre = trim(filter_var($_POST['nombre'], FILTER_SANITIZE_STRING));
    $apellidos = trim(filter_var($_POST['apellidos'], FILTER_SANITIZE_STRING));
    $usuario = trim(filter_var($_POST['usuario'], FILTER_SANITIZE_STRING));
    $email = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
    $sexo = trim(filter_var($_POST['sexo'], FILTER_SANITIZE_STRING));
    $fech_nac = trim(filter_var($_POST['fech_nac'], FILTER_SANITIZE_STRING));
    $ciudad = trim(filter_var($_POST['ciudad'], FILTER_SANITIZE_STRING));

    if (!existeCiudad($ciudad)) {
        $error[] = "No se ha selecionado ninguna ciudad";
    }
    if ($nombre == "") {
        $error[] = "Indique el nombre";
    }
    if ($apellidos == "") {
        $error[] = "Indique los apellidos";
    }
    if ($usuario == "") {
        $error[] = "Indique el nombre de usuario";
    }
    if ($email == "") {
        $error[] = "Indique el email";
    }
    if ($sexo == "") {
        $error[] = "Indique el sexo";
    }
    if ($fech_nac == "") {
        $error[] = "Indique la fecha de nacimiento";
    }

    if ($_FILES['imagen_usu']['size'] > 0) {
        $tiposAceptados = Array('image/jpeg', 'image/png');
        if (array_search($_FILES['imagen_usu']['type'], $tiposAceptados) === false) {
            $error[] = "El tipo de la imagen no es correcto";
        }

        if (comprobarSizeFoto($_FILES['imagen_usu']['size'])) {
            $error[] = "El tamaño debe ser menor a 1M";
        }
    }


    if (isset($error)) {
        echo '<p style="color:red">Errores...</p>';
        echo '<ul style="color:red">';
        foreach ($error as $e) {
            echo "<li>$e</li>";
        }
        echo '</ul>';
    }

    if (!isset($error)) {
        if (isset($no_imagen)) {
            $ruta = "images/default_user.png";
        } else {
            $ruta = "images/user_images/$usuario.jpeg";
            move_uploaded_file($_FILES['imagen_usu']['tmp_name'], $ruta);
        }

        return modificarUsuario($nombre, $apellidos, $email, $sexo, $fech_nac, $ruta, $ciudad, $userActual);
    }
}

// Gestiona la acción de cambio de contraseña de un usuario.
function botCambiarPassword($userActual) {
    $passwordActual = trim(filter_var($_POST['passwordActual'], FILTER_SANITIZE_STRING));
    $passwordNueva = trim(filter_var($_POST['passwordNueva'], FILTER_SANITIZE_STRING));
    $repetirPassword = trim(filter_var($_POST['repetirPassword'], FILTER_SANITIZE_STRING));

    if ($passwordActual == "") {
        $error['passwordActual'] = "El campo contraseña esta vacío";
    }
    if ($passwordNueva == "") {
        $error['passwordNueva'] = "El campo nueva contraseña esta vacío";
    }
    if ($repetirPassword == "") {
        $error['repetirPassword'] = "El campo repetir contraseña esta vacio";
    }

    if (isset($error)) {
        echo '<p style="color:red">Errores...</p>';
        echo '<ul style="color:red">';
        foreach ($error as $e) {
            echo "<li>$e</li>";
        }
        echo '</ul>';
    }

    if (!isset($error)) {
        $con = crearConexion();
        $query = "SELECT `password` FROM `usuario` WHERE `username`='$userActual'";
        $result = mysqli_query($con, $query);
        while ($row = mysqli_fetch_array($result)) {
            $password = $row['password'];
        }
        cerrarConexion($con);

        if (!password_verify($passwordActual, $password)) {
            echo 'La contraseña actual no coincide';
        } else {
            if ($passwordNueva == $repetirPassword) {
                $hash = password_hash($passwordNueva, PASSWORD_DEFAULT);
                $con = crearConexion();
                $query = "UPDATE `usuario` SET `password`='$hash' WHERE `username`='$userActual'";
                if (mysqli_query($con, $query)) {
                    return TRUE;
                } else {
                    return FALSE;
                }
                cerrarConexion($con);
            }
        }
    }
}

// Obtiene las conversaciones entre dos usuarios.
function getConversacion($usuario, $otro_usuario) {
    $usuario_ok = trim(filter_var($usuario, FILTER_SANITIZE_STRING));
    $otro_usuario_ok = trim(filter_var($otro_usuario, FILTER_SANITIZE_STRING));
    $link = crearConexion();
    $query = "SELECT `usu_remitente`, `usu_destinatario`, `texto`, `censurado` FROM `mensaje` WHERE (mensaje.usu_remitente='$usuario_ok' AND mensaje.usu_destinatario='$otro_usuario_ok') OR (mensaje.usu_destinatario='$usuario' AND mensaje.usu_remitente='$otro_usuario') ORDER BY mensaje.fecha_hora ASC";
    $result = mysqli_query($link, $query);
    cerrarConexion($link);
    $conversaciones = [];
    while ($linea = mysqli_fetch_array($result)) {
        $conversaciones[] = [$linea['usu_remitente'], $linea['texto'], $linea['censurado']];
    }
    return $conversaciones;
}

// Obtiene las conversaciones de un grupo determinado.
function getConversacionGrupal($entrada) {
    $grupo = trim(filter_var($entrada, FILTER_SANITIZE_STRING));
    $conversacion = [];
    $link = crearConexion();
    $query = "SELECT publicacion.titulo, publicacion.contenido, publicacion.fecha_hora, publicacion.censurada, usuario.username, usuario.foto FROM publicacion, usuario WHERE publicacion.grupo='" . $grupo . "' AND publicacion.autor=usuario.username ORDER BY publicacion.fecha_hora ASC";
    $result = mysqli_query($link, $query);
    cerrarConexion($link);
    while ($linea = mysqli_fetch_array($result)) {
        $fila['titulo'] = $linea['titulo'];
        $fila['contenido'] = $linea['contenido'];
        $fila['fecha_hora'] = $linea['fecha_hora'];
        $fila['usuario'] = $linea['username'];
        $fila['foto'] = $linea['foto'];
        $fila['censurada'] = $linea['censurada'];

        $conversacion[] = $fila;
    }
    return $conversacion;
}

// Obtiene los grupos de un usuario determinado.
function leerMisGrupos($entrada) {
    $grupos = [];
    $usuario = trim(filter_var($entrada, FILTER_SANITIZE_STRING));

    $con = crearConexion();

    $query = "SELECT propietario, usuario, grupo, nombre, imagen, descripcion FROM usuario_grupo, grupo WHERE usuario = '$usuario' AND usuario_grupo.grupo = grupo.nombre";

    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) < 1) {
        return FALSE;
    } else {
        while ($row = mysqli_fetch_array($result)) {
            $fila = [];

            $fila['propietario'] = $row['propietario'];
            $fila['usuario'] = $row['usuario'];
            $fila['grupo'] = $row['grupo'];
            $fila['nombre'] = $row['nombre'];
            $fila['imagen'] = $row['imagen'];
            $fila['descripcion'] = $row['descripcion'];

            $grupos[] = $fila;
        }

        cerrarConexion($con);
        return $grupos;
    }
}

// Comprueba si un determinado grupo existe.
function exiteGrupo($entrada) {
    $grupo = trim(filter_var($entrada, FILTER_SANITIZE_STRING));

    $con = crearConexion();
    $query = "SELECT nombre FROM grupo WHERE nombre='$grupo'";
    $result = mysqli_query($con, $query);

    cerrarConexion($con);

    return mysqli_num_rows($result) > 0;
}

// Obtiene las categorías.
function leerCategoriasLogueado() {
    $categorias = [];

    $con = crearConexion();

    $query = "SELECT nombre FROM categoria";

    $result = mysqli_query($con, $query);

    while ($row = mysqli_fetch_array($result)) {
        $categorias[] = $row['nombre'];
    }

    cerrarConexion($con);

    return $categorias;
}

// Obtiene los grupos de un usuario determinado.
function leerGrupos($entrada) {
    $grupos = [];

    $usuario = trim(filter_var($entrada, FILTER_SANITIZE_STRING));

    $con = crearConexion();

    $query = "SELECT completa.id, completa.nombre, completa.imagen, completa.fecha_hora, completa.descripcion "
            . "FROM (SELECT `nombre`, `imagen`, `descripcion` FROM `grupo`) AS completa WHERE NOT EXISTS "
            . "(SELECT `nombre`, `imagen`, `descripcion` FROM `grupo`, usuario_grupo WHERE `nombre`=usuario_grupo.grupo AND usuario_grupo.usuario='$usuario') "
            . "ORDER BY completa.fecha_hora ASC LIMIT 20";

    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) < 1) {
        return FALSE;
    } else {
        while ($row = mysqli_fetch_array($result)) {
            $fila = [];

            $fila['propietario'] = $row['propietario'];
            $fila['usuario'] = $row['usuario'];
            $fila['grupo'] = $row['grupo'];
            $fila['nombre'] = $row['nombre'];
            $fila['imagen'] = $row['imagen'];
            $fila['descripcion'] = $row['descripcion'];

            $grupos[] = $fila;
        }

        cerrarConexion($con);
        return $grupos;
    }
}

// Obtiene los datos de un usuario.
function leerUsuarioLogin($entrada) {
    $user = trim(filter_var($entrada, FILTER_SANITIZE_STRING));
    $con = crearConexion();
    $query = "SELECT `username`, `nombre`, `apellidos`, `password`, `email`, `sexo`, `f_nacimiento`, `foto`, `ciudad`, `tipo`, `ultimo_acceso` FROM `usuario` WHERE `username` = '$user'";
    $result = mysqli_query($con, $query);

    $row = mysqli_fetch_array($result);

    cerrarConexion($con);
    return $row;
}

// Obtiene las ciudades.
function leerCiudades() {
    $ciudades = [];
    $con = crearConexion();
    $query = "SELECT * FROM `localizacion`";
    $result = mysqli_query($con, $query);
    while ($row = mysqli_fetch_array($result)) {
        $fila = [];
        $fila['ciudad'] = $row['ciudad'];
        $fila['id'] = $row['id'];
        $ciudades[] = $fila;
    }
    cerrarConexion($con);
    return $ciudades;
}

// Obtiene los usuario de tipo administrador.
function getUsersAdmin() {
    $usuarios = [];

    $con = crearConexion();

    $query = "SELECT `username` FROM `usuario` WHERE `tipo`='0'";

    $result = mysqli_query($con, $query);

    while ($row = mysqli_fetch_array($result)) {
        $usuarios[] = $row['username'];
    }

    cerrarConexion($con);
    return $usuarios;
}

// Obtiene los grupos a los cuales no está inscrito un usuario.
function leerGruposNoInscrito($entrada) {
    $usuario = trim(filter_var($entrada, FILTER_SANITIZE_STRING));
    $grupos = [];

    $con = crearConexion();

    $query = "SELECT completa.nombre, completa.descripcion, completa.imagen FROM (SELECT nombre, f_creacion, imagen, descripcion FROM `grupo`) AS completa WHERE completa.nombre NOT IN (SELECT grupo.nombre FROM grupo, usuario_grupo WHERE grupo.nombre = usuario_grupo.grupo AND usuario_grupo.usuario = '$usuario') GROUP BY completa.nombre ORDER BY completa.f_creacion DESC";

    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) < 1) {
        return FALSE;
    } else {
        while ($row = mysqli_fetch_array($result)) {
            $fila = [];

            $fila['nombre'] = $row['nombre'];
            $fila['imagen'] = $row['imagen'];
            $fila['descripcion'] = $row['descripcion'];

            $grupos[] = $fila;
        }

        cerrarConexion($con);
        return $grupos;
    }
}

// Gestiona la modificación de un grupo.
function comprobarCrearModificarGrupo($modifica) {
    $errores = [];

    if (!isset($_POST['nombreGrupo']) || !isset($_POST['textoDescripcion']) || !isset($_POST['selectCategoria'])) {
        $errores[] = "Los campos nombre, descripci&oacute;n y categori son obligatorios";
    } else {
        $nombreGrupo = trim(filter_var($_POST['nombreGrupo'], FILTER_SANITIZE_STRING));
        $descripcionGrupo = trim(filter_var($_POST['textoDescripcion'], FILTER_SANITIZE_STRING));
        $categoria = trim(filter_var($_POST['selectCategoria'], FILTER_SANITIZE_STRING));

        if ($nombreGrupo == "") {
            $errores[] = "El nombre es obligatorio";
        } else if (existeGrupo($nombreGrupo) && !$modifica) {
            $errores[] = "Nombre ya existente";
        }

        if ($descripcionGrupo == "") {
            $errores[] = "La descripci&oacute;n del grupo es obligatoria";
        }

        if (!compruebaCategoria($categoria)) {
            $errores[] = "La categoria seleccionada no es valida";
        }
    }

    return $errores;
}

// Comprueba si la imagen de un formulario es válida.
function compruebaFoto() {
    $error = "";
    if ($_FILES['imagen']['error'] < 1) {
        if ($_FILES['imagen']['type'] != 'image/jpeg' && $_FILES['imagen']['type'] != 'image/png') {
            $error = "El formato de la imagen no es correrto, tiene que ser jpeg - png";
        }
    } else {
        $error = "La imagen es requerida";
    }
    return $error;
}

// Inserta en el sistema un grupo.
function insertGrupo($entrada) {
    $usuario = trim(filter_var($entrada, FILTER_SANITIZE_STRING));
    $nombreGrupo = trim(filter_var($_POST['nombreGrupo'], FILTER_SANITIZE_STRING));
    $descripcionGrupo = trim(filter_var($_POST['textoDescripcion'], FILTER_SANITIZE_STRING));
    $categoria = trim(filter_var($_POST['selectCategoria'], FILTER_SANITIZE_STRING));

    $ruta = "images/grupos/$nombreGrupo" . '.jpeg';
    move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta);

    crearGrupo($nombreGrupo, $descripcionGrupo, $ruta);
    crearGrupoCategori($nombreGrupo, $categoria);
    crearUsuarioGrupo($nombreGrupo, $usuario);
}

// Actualiza un grupo.
function updateGrupo() {
    $nombreGrupo = trim(filter_var($_POST['nombreGrupo'], FILTER_SANITIZE_STRING));
    $descripcionGrupo = trim(filter_var($_POST['textoDescripcion'], FILTER_SANITIZE_STRING));
    $categoria = trim(filter_var($_POST['selectCategoria'], FILTER_SANITIZE_STRING));

    $ruta = "images/grupos/$nombreGrupo" . '.jpeg';
    move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta);

    $con = crearConexion();

    $query1 = "UPDATE `grupo` SET `imagen`='$ruta',`descripcion`='$descripcionGrupo' WHERE nombre='$nombreGrupo'";
    $query2 = "UPDATE `grupo_categoria` SET `categoria`='$categoria' WHERE grupo='$nombreGrupo'";

    mysqli_query($con, $query1);
    mysqli_query($con, $query2);

    cerrarConexion($con);
}

// Actualiza un grupo sin incluir la foto.
function updateGrupoSinFoto() {
    $nombreGrupo = trim(filter_var($_POST['nombreGrupo'], FILTER_SANITIZE_STRING));
    $descripcionGrupo = trim(filter_var($_POST['textoDescripcion'], FILTER_SANITIZE_STRING));
    $categoria = trim(filter_var($_POST['selectCategoria'], FILTER_SANITIZE_STRING));

    $con = crearConexion();

    $query1 = "UPDATE `grupo` SET `descripcion`='$descripcionGrupo' WHERE nombre='$nombreGrupo'";
    $query2 = "UPDATE `grupo_categoria` SET `categoria`='$categoria' WHERE grupo='$nombreGrupo'";

    mysqli_query($con, $query1);
    mysqli_query($con, $query2);

    cerrarConexion($con);
}

// Actualiza una categoría.
function updateCategoria() {
    $nombre = trim(filter_var($_POST['nombre'], FILTER_SANITIZE_STRING));
    $descripcion = trim(filter_var($_POST['texto_descripcion'], FILTER_SANITIZE_STRING));

    $ruta = "images/categorias/$nombre.jpeg";
    move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta);

    $con = crearConexion();

    $query = "UPDATE `categoria` SET `descripcion`='$descripcion',`imagen`='$ruta' WHERE nombre='$nombre'";
    mysqli_query($con, $query);

    cerrarConexion($con);
}

// Actualiza una categoría sin foto.
function updateCategoriaSinFoto() {
    $nombre = trim(filter_var($_POST['nombre'], FILTER_SANITIZE_STRING));
    $descripcion = trim(filter_var($_POST['texto_descripcion'], FILTER_SANITIZE_STRING));

    $con = crearConexion();

    $query = "UPDATE `categoria` SET `descripcion`='$descripcion' WHERE nombre='$nombre'";
    mysqli_query($con, $query);

    cerrarConexion($con);
}

// Comprueba si una categoría existe.
function compruebaCategoria($entrada) {
    $categoria = trim(filter_var($entrada, FILTER_SANITIZE_STRING));
    $con = crearConexion();

    $query = "SELECT nombre FROM categoria WHERE nombre='$categoria'";

    $result = mysqli_query($con, $query);

    cerrarConexion($con);

    return mysqli_num_rows($result) > 0;
}

// Comprueba si una ciudad existe.
function compruebaCiudad($entrada) {
    $ciudad = trim(filter_var($entrada, FILTER_SANITIZE_STRING));
    $con = crearConexion();

    $query = "SELECT `ciudad` FROM `localizacion` WHERE ciudad='$ciudad'";

    $result = mysqli_query($con, $query);

    cerrarConexion($con);

    return mysqli_num_rows($result) == 0;
}

// Crea un nuevo grupo.
function crearGrupo($entrada1, $entrada2, $entrada3) {
    $nombre = trim(filter_var($entrada1, FILTER_SANITIZE_STRING));
    $descripcionGrupo = trim(filter_var($entrada2, FILTER_SANITIZE_STRING));
    $ruta = trim(filter_var($entrada3, FILTER_SANITIZE_STRING));

    $con = crearConexion();

    $query = "INSERT INTO grupo (nombre, f_creacion, imagen, descripcion) VALUES ('$nombre', NOW(), '$ruta', '$descripcionGrupo')";

    $result = mysqli_query($con, $query);

    cerrarConexion($con);
}

// Inserta la relación entre un grupo y una categoría.
function crearGrupoCategori($entrada1, $entrada2) {
    $grupo = trim(filter_var($entrada1, FILTER_SANITIZE_STRING));
    $categoria = trim(filter_var($entrada2, FILTER_SANITIZE_STRING));

    $con = crearConexion();

    $query = "INSERT INTO grupo_categoria (grupo, categoria ) VALUES ('$grupo', '$categoria')";

    $result = mysqli_query($con, $query);

    cerrarConexion($con);
}

// Inserta la relación entre un usuario y un grupo.
function crearUsuarioGrupo($entrada1, $entrada2) {
    $grupo = trim(filter_var($entrada1, FILTER_SANITIZE_STRING));
    $usuario = trim(filter_var($entrada2, FILTER_SANITIZE_STRING));

    $con = crearConexion();

    $query = "INSERT INTO usuario_grupo (propietario, usuario, grupo ) VALUES (1,'$usuario', '$grupo')";

    $result = mysqli_query($con, $query);

    cerrarConexion($con);
}

// Obtiene los eventos de un usuario determinado.
function leerMisEventos($entrada1) {
    $usuario = trim(filter_var($entrada1, FILTER_SANITIZE_STRING));
    $eventos = [];

    $con = crearConexion();

    $query = "SELECT id, propietario, usuario, evento, nombre, fecha_hora, descripcion, imagen FROM usuario_evento, evento WHERE usuario = '$usuario' AND usuario_evento.evento = evento.id";

    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) < 1) {
        return FALSE;
    } else {
        while ($row = mysqli_fetch_array($result)) {
            $fila = [];

            $fila['propietario'] = $row['propietario'];
            $fila['id'] = $row['id'];
            $fila['usuario'] = $row['usuario'];
            $fila['evento'] = $row['evento'];
            $fila['nombre'] = $row['nombre'];
            $fila['fecha_hora'] = $row['fecha_hora'];
            $fila['imagen'] = $row['imagen'];
            $fila['descripcion'] = $row['descripcion'];

            $eventos[] = $fila;
        }

        cerrarConexion($con);
        return $eventos;
    }
}

// Comprueba si un evento determinado existe.
function exiteEvento($entrada1) {
    $evento = trim(filter_var($entrada1, FILTER_SANITIZE_STRING));
    $con = crearConexion();

    $query = "SELECT nombre FROM evento WHERE nombre='$evento'";

    $result = mysqli_query($con, $query);

    cerrarConexion($con);

    return mysqli_num_rows($result) > 0;
}

// Obtiene el identificador de un evento.
function obtenerIdEvento($entrada) {
    $evento = trim(filter_var($entrada, FILTER_SANITIZE_STRING));
    $con = crearConexion();

    $query = "SELECT id FROM evento WHERE nombre = '$evento'";
    $result = mysqli_query($con, $query);

    $row = mysqli_fetch_array($result);

    cerrarConexion($con);

    return $row['id'];
}

// Obtiene los eventos a los cuales no está inscrito un usuario determinado.
function leerEventosNoInscrito($entrada) {
    $usuario = trim(filter_var($entrada, FILTER_SANITIZE_STRING));
    $eventos = [];

    $con = crearConexion();

    $query = "SELECT completa.id ,completa.nombre, completa.descripcion, completa.imagen, completa.fecha_hora FROM (SELECT id, nombre, fecha_hora, imagen, descripcion FROM evento) AS completa WHERE completa.id NOT IN (SELECT evento.id FROM evento, usuario_evento WHERE evento.id = usuario_evento.evento AND usuario_evento.usuario = '$usuario') GROUP BY completa.nombre ORDER BY completa.fecha_hora DESC";

    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) < 1) {
        return FALSE;
    } else {
        while ($row = mysqli_fetch_array($result)) {
            $fila = [];

            $fila['nombre'] = $row['nombre'];
            $fila['imagen'] = $row['imagen'];
            $fila['fecha_hora'] = $row['fecha_hora'];
            $fila['descripcion'] = $row['descripcion'];

            $eventos[] = $fila;
        }

        cerrarConexion($con);
        return $eventos;
    }
}

// Comprueba si un usuario pertenece a un grupo.
function perteneceGrupo($entrada1, $entrada2) {
    $usuario = trim(filter_var($entrada1, FILTER_SANITIZE_STRING));
    $grupo = trim(filter_var($entrada2, FILTER_SANITIZE_STRING));
    $con = crearConexion();

    $query = "SELECT usuario FROM `usuario_grupo` WHERE `usuario`='$usuario' AND `grupo`='$grupo'";

    $result = mysqli_query($con, $query);
    cerrarConexion($con);
    return mysqli_num_rows($result) > 0;
}

// Gestiona la validación del perfil de usuario administrador. 
function comprobarPerfilDeUsuario_admin($entrada) {
    $user = trim(filter_var($entrada, FILTER_SANITIZE_STRING));
    $nombre = trim(filter_var($_POST['nombre'], FILTER_SANITIZE_STRING));
    $apellidos = trim(filter_var($_POST['apellidos'], FILTER_SANITIZE_STRING));
    $usuario = trim(filter_var($_POST['usuario'], FILTER_SANITIZE_STRING));
    $password = trim(filter_var($_POST['password'], FILTER_SANITIZE_STRING));
    $email = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
    $sexo = trim(filter_var($_POST['sexo'], FILTER_SANITIZE_STRING));
    $fech_nac = trim(filter_var($_POST['fech_nac'], FILTER_SANITIZE_STRING));
    $ciudad = trim(filter_var($_POST['ciudad'], FILTER_SANITIZE_STRING));

    if (!existeCiudad($ciudad)) {
        $error[] = "No se a selecionado ninguna ciudad";
    }
    if ($nombre == "") {
        $error[] = "Indiqeue el nombre";
    }
    if ($apellidos == "") {
        $error[] = "Indique los apellidos";
    }
    if ($usuario == "") {
        $error[] = "Indique el nombre de usuario";
    }
    if ($email == "") {
        $error[] = "Indique el email";
    }
    if ($sexo == "") {
        $error[] = "Indique el sexo";
    }
    if ($fech_nac == "") {
        $error[] = "Indique la fecha de nacieminto";
    }

    if ($_FILES['imagen_usu']['size'] > 0) {
        $tiposAceptados = Array('image/jpeg', 'image/png');
        if (array_search($_FILES['imagen_usu']['type'], $tiposAceptados) === false) {
            $error[] = "El tipo de la imagen no es correcto";
        }

        if (comprobarSizeFoto($_FILES['imagen_usu']['size'])) {
            $error[] = "El tamaño debe ser menor a 1M";
        }
    }
    if (isset($error)) {
        echo '<p style="color:red">Errores...</p>';
        echo '<ul style="color:red">';
        foreach ($error as $e) {
            echo "<li>$e</li>";
        }
        echo '</ul>';
    }

    if (!isset($error)) {

        $hash = password_hash($password, PASSWORD_DEFAULT);

        if ($password == "" && $_FILES['imagen_usu']['size'] > 0) {
            $ruta = "images/user_images/$usuario.jpeg";
            move_uploaded_file($_FILES['imagen_usu']['tmp_name'], $ruta);
            $query = "UPDATE `usuario` SET `nombre`='$nombre',`apellidos`='$apellidos',`email`='$email',`sexo`='$sexo',`f_nacimiento`='$fech_nac',`foto`='$ruta',`ciudad`='$ciudad',`tipo`= 0 WHERE `username` = '$user'";
        } else if ($password != "" && $_FILES['imagen_usu']['size'] == 0) {
            $query = "UPDATE `usuario` SET `nombre`='$nombre',`apellidos`='$apellidos',`password`='$hash',`email`='$email',`sexo`='$sexo',`f_nacimiento`='$fech_nac',`ciudad`='$ciudad',`tipo`= 0 WHERE `username` = '$user'";
        } else if ($password != "" && $_FILES['imagen_usu']['size'] > 0) {
            $ruta = "images/user_images/$usuario.jpeg";
            move_uploaded_file($_FILES['imagen_usu']['tmp_name'], $ruta);
            $query = "UPDATE `usuario` SET `nombre`='$nombre',`apellidos`='$apellidos',`password`='$hash',`email`='$email',`sexo`='$sexo',`f_nacimiento`='$fech_nac',`foto`='$ruta',`ciudad`='$ciudad',`tipo`= 0 WHERE `username` = '$user'";
        } else {
            $query = "UPDATE `usuario` SET `nombre`='$nombre',`apellidos`='$apellidos',`email`='$email',`sexo`='$sexo',`f_nacimiento`='$fech_nac',`ciudad`='$ciudad',`tipo`= 0 WHERE `username` = '$user'";
        }

        $con = crearConexion();
        if (mysqli_query($con, $query)) {
            return TRUE;
        } else {
            return FALSE;
        }
        cerrarConexion($con);
    }
}

// Comprueba si una categoría determinada existe.
function existeCategoria($entrada) {
    $nombre = trim(filter_var($entrada, FILTER_SANITIZE_STRING));
    $link = crearConexion();

    $query = "SELECT nombre FROM `categoria` WHERE nombre='$nombre'";
    $result = mysqli_query($link, $query);
    cerrarConexion($link);
    if (mysqli_num_rows($result) > 0) {
        return TRUE;
    } else {
        return FALSE;
    }
}

// Obtiene todos los grupos.
function leerTodosGrupos() {
    $link = crearConexion();
    $grupos = [];

    $query = "SELECT nombre, imagen, descripcion FROM grupo";
    $result = mysqli_query($link, $query);
    cerrarConexion($link);
    while ($linea = mysqli_fetch_array($result)) {
        $fila['nombre'] = $linea['nombre'];
        $fila['imagen'] = $linea['imagen'];
        $fila['descripcion'] = $linea['descripcion'];

        $grupos[] = $fila;
    }

    return $grupos;
}

// Obtiene el grupo con un nombre determinado
function obtenerGrupoAmodificar($nombreGrupo) {
    $nombre = trim(filter_var($nombreGrupo, FILTER_SANITIZE_STRING));
    $grupo = [];

    $con = crearConexion();

    $query = "SELECT nombre, imagen, descripcion, categoria FROM grupo, grupo_categoria WHERE grupo.nombre = grupo_categoria.grupo AND grupo.nombre = '$nombre'";

    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_array($result);
    $grupo['imagen'] = $row['imagen'];
    $grupo['nombre'] = $row['nombre'];
    $grupo['descripcion'] = $row['descripcion'];
    $grupo['categoria'] = $row['categoria'];

    cerrarConexion($con);

    return $grupo;
}

// Obtiene una categoría con nombre determinado.
function obtenerCategoriaAmodificar($nombreCategoria) {
    $nombre = trim(filter_var($nombreCategoria, FILTER_SANITIZE_STRING));
    $categoria = [];

    $con = crearConexion();

    $query = "SELECT nombre,`descripcion`, `imagen` FROM `categoria` WHERE nombre='$nombre'";

    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_array($result);
    $categoria['nombre'] = $row['nombre'];
    $categoria['descripcion'] = $row['descripcion'];
    $categoria['imagen'] = $row['imagen'];

    cerrarConexion($con);

    return $categoria;
}

// Obtiene un evento por identificador.
function obtenerEventoPorId($id) {
    $idEvento = trim(filter_var($id, FILTER_SANITIZE_NUMBER_INT));
    $evento = [];

    $con = crearConexion();

    $query = "SELECT evento.id, evento.nombre, evento.fecha_hora, evento.descripcion, evento.imagen, evento_categoria.categoria, localizacion.ciudad, localizacion.id AS id_ciudad, cp, direccion FROM `evento`, evento_categoria, localizacion_evento, localizacion WHERE evento.id = '$idEvento' AND evento.id = evento_categoria.evento AND evento.id = localizacion_evento.evento AND localizacion_evento.ciudad=localizacion.id";

    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_array($result);
    $evento['id'] = $row['id'];
    $evento['nombre'] = $row['nombre'];
    $evento['descripcion'] = $row['descripcion'];
    $evento['categoria'] = $row['categoria'];
    $evento['fecha_hora'] = $row['fecha_hora'];
    $evento['imagen'] = $row['imagen'];
    $evento['ciudad'] = $row['ciudad'];
    $evento['id_ciudad'] = $row['id_ciudad'];
    $evento['cp'] = $row['cp'];
    $evento['direccion'] = $row['direccion'];

    cerrarConexion($con);

    return $evento;
}

// Gestiona la validación de una categoría.
function comprobarCrearModificarCategoria($modifica) {
    $errores = [];

    if (!isset($_POST['nombre']) || !isset($_POST['texto_descripcion'])) {
        "Los campos nombre y descripci&oacute;n son obligatorios";
    } else {
        $nombre = trim(filter_var($_POST['nombre'], FILTER_SANITIZE_STRING));
        $descripcion = trim(filter_var($_POST['texto_descripcion'], FILTER_SANITIZE_STRING));

        if ($nombre == "") {
            $errores[] = "El nombre es obligatorio";
        } else if (existeCategoria($nombre) && !$modifica) {
            $errores[] = "Nombre ya existente";
        }

        if ($descripcion == "") {
            $errores[] = "La descripci&oacute;n es obligatoria";
        }
    }

    return $errores;
}

// Crea una nueva categoría.
function crearCategoria(){
    $nombre = trim(filter_var($_POST['nombre'], FILTER_SANITIZE_STRING));
    $descripcion = trim(filter_var($_POST['texto_descripcion'], FILTER_SANITIZE_STRING));      
    $ruta = "images/categorias/$nombre.jpeg";
    move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta);
    
    $con = crearConexion();
        
    $query = "INSERT INTO `categoria`(`nombre`, `descripcion`, `imagen`) VALUES ('$nombre','$descripcion','$ruta')";
    mysqli_query($con, $query);
    
    cerrarConexion($con);
}

// Gestiona la validación de un evento a crear o modificar.
function comprobarCrearModificarEvento($modifica) {
    $errores = [];

    if (!isset($_POST['nombreEvento']) || !isset($_POST['textoDescripcion']) || !isset($_POST['selectCategoria']) || !isset($_POST['fecha']) || !isset($_POST['hora']) || !isset($_POST['selectCiudad']) || !isset($_POST['cp']) || !isset($_POST['direccion'])) {
        $errores[] = "Los campos nombre, descripci&oacute;n, fecha y hora, categoria, ciudad, codigo postal y direccion son obligatorios";
    } else {
        $nombreEvento = trim(filter_var($_POST['nombreEvento'], FILTER_SANITIZE_STRING));
        $descripcionEvento = trim(filter_var($_POST['textoDescripcion'], FILTER_SANITIZE_STRING));
        $categoria = trim(filter_var($_POST['selectCategoria'], FILTER_SANITIZE_STRING));
        $fecha = trim(filter_var($_POST['fecha'], FILTER_SANITIZE_STRING));
        $hora = trim(filter_var($_POST['hora'], FILTER_SANITIZE_STRING));
        $fechaHora = $fecha . " " . $hora;
        $ciudad = trim(filter_var($_POST['selectCiudad'], FILTER_SANITIZE_NUMBER_INT));
        $cp = trim(filter_var($_POST['cp'], FILTER_SANITIZE_STRING));
        $direccion = trim(filter_var($_POST['direccion'], FILTER_SANITIZE_STRING));


        if ($nombreEvento == "") {
            $errores[] = "El nombre es obligatorio";
        } else if (existeEvento($nombreEvento) && !$modifica) {
            $errores[] = "Nombre ya existente";
        }

        if ($descripcionEvento == "") {
            $errores[] = "La descripci&oacute;n del evento es obligatoria";
        }

        if (!compruebaCategoria($categoria)) {
            $errores[] = "La categoria seleccionada no es valida";
        }

        if ($fechaHora == "") {
            $errores[] = "La fecha y hora son obligatorias";
        }

        if (!compruebaCiudad($ciudad)) {
            $errores[] = "La ciudad seleccionada no es valida";
        }

        if ($cp == "") {
            $errores[] = "El c&oacute;digo postal es obligatorio";
        } else if (!preg_match('/^\d{5}$/', $cp)) {
            $errores[] = "El formato del c&oacute;digo postal no es correcto";
        }

        if ($direccion == "") {
            $errores[] = "La direcci&oacute;n es obligatoria";
        }
    }

    return $errores;
}

// Inserta un nuevo evento.
function insertEvento($entrada) {
    $usuario = trim(filter_var($entrada, FILTER_SANITIZE_STRING));
    $nombreEvento = trim(filter_var($_POST['nombreEvento'], FILTER_SANITIZE_STRING));
    $descripcionEvento = trim(filter_var($_POST['textoDescripcion'], FILTER_SANITIZE_STRING));
    $categoria = trim(filter_var($_POST['selectCategoria'], FILTER_SANITIZE_STRING));
    $fecha = trim(filter_var($_POST['fecha'], FILTER_SANITIZE_STRING));
    $hora = trim(filter_var($_POST['hora'], FILTER_SANITIZE_STRING));
    $fechaHora = $fecha . " " . $hora;
    $ciudad = trim(filter_var($_POST['selectCiudad'], FILTER_SANITIZE_NUMBER_INT));
    $cp = trim(filter_var($_POST['cp'], FILTER_SANITIZE_STRING));
    $direccion = trim(filter_var($_POST['direccion'], FILTER_SANITIZE_STRING));

    $ruta = "images/eventos/$nombreEvento" . '.jpeg';
    move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta);

    $con = crearConexion();

    $query1 = "INSERT INTO `evento`(`nombre`, `fecha_hora`, `descripcion`, `imagen`) VALUES ('$nombreEvento','$fechaHora','$descripcionEvento','$ruta')";
    mysqli_query($con, $query1);

    $idEvento = obtenerIdEvento($nombreEvento);

    $query2 = "INSERT INTO `evento_categoria`(`evento`, `categoria`) VALUES ($idEvento,'$categoria')";
    $query3 = "INSERT INTO `usuario_evento`(`usuario`, `evento`, `propietario`) VALUES ('$usuario',$idEvento,1)";
    $query4 = "INSERT INTO `localizacion_evento`(`ciudad`, `evento`, `cp`, `direccion`) VALUES ('$ciudad','$idEvento','$cp','$direccion')";

    mysqli_query($con, $query2);
    mysqli_query($con, $query3);
    mysqli_query($con, $query4);

    cerrarConexion($con);
}

// Actualiza un evento.
function updateEvento($id) {
    $id = trim(filter_var($entrada, FILTER_SANITIZE_NUMBER_INT));
    $descripcionEvento = trim(filter_var($_POST['textoDescripcion'], FILTER_SANITIZE_STRING));
    $categoria = trim(filter_var($_POST['selectCategoria'], FILTER_SANITIZE_STRING));
    $fecha = trim(filter_var($_POST['fecha'], FILTER_SANITIZE_STRING));
    $hora = trim(filter_var($_POST['hora'], FILTER_SANITIZE_STRING));
    $fechaHora = $fecha . " " . $hora;
    $ciudad = trim(filter_var($_POST['selectCiudad'], FILTER_SANITIZE_NUMBER_INT));
    $cp = trim(filter_var($_POST['cp'], FILTER_SANITIZE_STRING));
    $direccion = trim(filter_var($_POST['direccion'], FILTER_SANITIZE_STRING));

    $ruta = "images/eventos/$nombreEvento" . '.jpeg';
    move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta);

    $con = crearConexion();

    $query1 = "UPDATE `evento` SET `fecha_hora`='$fechaHora',`descripcion`='$descripcionEvento',`imagen`='$ruta' WHERE id=$id";
    $query2 = "UPDATE `evento_categoria` SET `categoria`='$categoria' WHERE evento = $id";
    $query3 = "UPDATE `localizacion_evento` SET `ciudad`='$ciudad',`cp`='$cp',`direccion`='$direccion' WHERE evento=$id";

    mysqli_query($con, $query1);
    mysqli_query($con, $query2);
    mysqli_query($con, $query3);

    cerrarConexion($con);
}

// Actualiza un evento sin foto.
function updateEventoSinFoto($entrada) {
    $id = trim(filter_var($entrada, FILTER_SANITIZE_NUMBER_INT));
    $descripcionEvento = trim(filter_var($_POST['textoDescripcion'], FILTER_SANITIZE_STRING));
    $categoria = trim(filter_var($_POST['selectCategoria'], FILTER_SANITIZE_STRING));
    $fecha = trim(filter_var($_POST['fecha'], FILTER_SANITIZE_STRING));
    $hora = trim(filter_var($_POST['hora'], FILTER_SANITIZE_STRING));
    $fechaHora = $fecha . " " . $hora;
    $ciudad = trim(filter_var($_POST['selectCiudad'], FILTER_SANITIZE_NUMBER_INT));
    $cp = trim(filter_var($_POST['cp'], FILTER_SANITIZE_STRING));
    $direccion = trim(filter_var($_POST['direccion'], FILTER_SANITIZE_STRING));

    $con = crearConexion();

    $query1 = "UPDATE `evento` SET `fecha_hora`='$fechaHora',`descripcion`='$descripcionEvento' WHERE id=$id";
    $query2 = "UPDATE `evento_categoria` SET `categoria`='$categoria' WHERE evento = $id";
    $query3 = "UPDATE `localizacion_evento` SET `ciudad`='$ciudad',`cp`='$cp',`direccion`='$direccion' WHERE evento=$id";

    mysqli_query($con, $query1);
    mysqli_query($con, $query2);
    mysqli_query($con, $query3);

    cerrarConexion($con);
}

// Comprueba si un grupo existe.
function existeGrupo($entrada) {
    $grupo = trim(filter_var($entrada, FILTER_SANITIZE_STRING));
    $con = crearConexion();

    $query = "SELECT `nombre` FROM `grupo` WHERE nombre = '$grupo'";
    $result = mysqli_query($con, $query);

    cerrarConexion($con);
    return mysqli_num_rows($result) > 0;
}

// Comprueba si existe un evento.
function existeEvento($entrada) {
    $evento = trim(filter_var($entrada, FILTER_SANITIZE_STRING));
    $con = crearConexion();

    $query = "SELECT `nombre` FROM `evento` WHERE nombre='$evento'";
    $result = mysqli_query($con, $query);

    cerrarConexion($con);
    return mysqli_num_rows($result) > 0;
}

// Obtiene los datos de un grupo por nombre.
function getDatosGrupo($entrada) {
    $nombre = trim(filter_var($entrada, FILTER_SANITIZE_STRING));
    $link = crearConexion();
    $query = "SELECT grupo.nombre, grupo.descripcion, grupo.imagen, grupo_categoria.categoria FROM grupo, grupo_categoria WHERE grupo.nombre=grupo_categoria.grupo AND grupo.nombre='$nombre'";
    $result = mysqli_query($link, $query);
    $linea = mysqli_fetch_array($result);
    $datos['nombre'] = $linea['nombre'];
    $datos['imagen'] = $linea['imagen'];
    $datos['descripcion'] = $linea['descripcion'];
    $datos['categoria'] = $linea['categoria'];
    cerrarConexion($link);
    return $datos;
}

// Obtiene los datos de un evento.
function getDatosEvento($id, $usuario) {
    $id_evento = trim(filter_var($id, FILTER_SANITIZE_NUMBER_INT));
    $usuario_evento = trim(filter_var($usuario, FILTER_SANITIZE_STRING));

    $link = crearConexion();
    $query = "SELECT evento.nombre, evento.fecha_hora, evento.descripcion, evento.imagen, localizacion.ciudad, localizacion_evento.cp, localizacion_evento.direccion FROM evento,usuario_evento, localizacion_evento, localizacion WHERE evento.id='$id_evento' AND evento.id=usuario_evento.evento AND usuario_evento.usuario='$usuario_evento' AND evento.id=localizacion_evento.evento AND localizacion_evento.ciudad=localizacion.id";
    $result = mysqli_query($link, $query);
    cerrarConexion($link);
    if (mysqli_num_rows($result) == 0) {
        return FALSE;
    } else {
        $linea = mysqli_fetch_array($result);
        $data['nombre'] = $linea['nombre'];
        $data['fecha_hora'] = $linea['fecha_hora'];
        $data['descripcion'] = $linea['descripcion'];
        $data['imagen'] = $linea['imagen'];
        $data['ciudad'] = $linea['ciudad'];
        $data['cp'] = $linea['cp'];
        $data['direccion'] = $linea['direccion'];
        return $data;
    }
}

// Comprueba si un evento pertenece a un usuario.
function compruebaMiEvento($entrada1, $entrada2) {
    $id = trim(filter_var($entrada1, FILTER_SANITIZE_NUMBER_INT));
    $usuario = trim(filter_var($entrada2, FILTER_SANITIZE_STRING));
    $con = crearConexion();

    $query = "SELECT propietario FROM usuario_evento WHERE evento = $id AND usuario = '$usuario' AND propietario='1'";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        return TRUE;
    } else {
        return FALSE;
    }

    cerrarConexion($con);
}

// Comprueba si un grupo pertenece a un usuario.
function compruebaMiGrupo($entrada1, $entrada2) {
    $grupo =trim(filter_var($entrada1, FILTER_SANITIZE_STRING));
    $usuario =trim(filter_var($entrada2, FILTER_SANITIZE_STRING));
    $con = crearConexion();

    $query = "SELECT propietario FROM usuario_grupo WHERE grupo = '$grupo' AND usuario = '$usuario' AND propietario='1'";

    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        return TRUE;
    } else {
        return FALSE;
    }

    cerrarConexion($con);
}
