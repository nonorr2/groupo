<?php
//Crea la conexión con la base de datos
function crearConexion() {
    $con = mysqli_connect("sql205.epizy.com", "epiz_21448463", "28664994w");
    if (!$con) {
        die("No conexión DB.");
    }

    $db = mysqli_select_db($con, "epiz_21448463_groupo");
    if (!$db) {
        mysqli_close($con);
        die("No se ha podido seleccionar la BD.");
    }

    mysqli_set_charset($con, "utf8");

    return $con;
}

//Cierra la conexión con la base de datos
function cerrarConexion($con) {
    mysqli_close($con);
}

// Obtiene las categorías.
function leerCategorias() {
    $contenido = [];

    $con = crearConexion();

    $query = "SELECT nombre, descripcion, imagen FROM categoria";
    $result = mysqli_query($con, $query);

    while ($row = mysqli_fetch_array($result)) {
        $fila = [];

        $fila['nombre'] = $row['nombre'];
        $fila['descripcion'] = $row['descripcion'];
        $fila['imagen'] = $row['imagen'];

        $contenido[] = $fila;
    }

    cerrarConexion($con);

    return $contenido;
}

// Obtiene las categorías de forma paginada.
// Recibe el número de categorías a devolver y el offset a partir del cual se obtendrán.
function leerCategoriasPaginadas($numCategorias, $offset) {
    $contenido = [];

    $con = crearConexion();

    $query = "SELECT nombre, descripcion, imagen FROM categoria limit $numCategorias offset $offset";
    $result = mysqli_query($con, $query);

    while ($row = mysqli_fetch_array($result)) {
        $fila = [];

        $fila['nombre'] = $row['nombre'];
        $fila['descripcion'] = $row['descripcion'];
        $fila['imagen'] = $row['imagen'];

        $contenido[] = $fila;
    }

    cerrarConexion($con);

    return $contenido;
}

// Obtiene el número de categorías.
function contarCategorias() {
    $con = crearConexion();

    $query = "SELECT count(*) FROM categoria";
    $result = mysqli_query($con, $query);
    
    $numCategorias = mysqli_fetch_row($result)[0];

    cerrarConexion($con);

    return $numCategorias;
}

//Devuelve los grupos pertenecientes a una categoría dada
function leerGruposPorCategoria($categoria) {
    $grupos = [];

    $con = crearConexion();
    $query = "SELECT nombre, f_creacion, imagen, descripcion FROM grupo WHERE nombre IN (SELECT grupo FROM grupo_categoria WHERE categoria = '$categoria')";

    $result = mysqli_query($con, $query);

    while ($row = mysqli_fetch_array($result)) {
        $fila = [];

        $fila['nombre'] = $row['nombre'];
        $fila['f_creacion'] = $row['f_creacion'];
        $fila['imagen'] = $row['imagen'];
        $fila['descripcion'] = $row['descripcion'];

        $grupos[] = $fila;
    }

    cerrarConexion($con);
    return $grupos;
}

//Devuelve los eventos de una categoría dada
function leerEventosPorCategoria($categoria) {
    $eventos = [];

    $con = crearConexion();
    $query = "SELECT id, nombre, fecha_hora, descripcion, imagen FROM evento WHERE id IN (SELECT evento FROM evento_categoria WHERE categoria= '$categoria')";

    $result = mysqli_query($con, $query);

    while ($row = mysqli_fetch_array($result)) {
        $fila = [];

        $fila['id'] = $row['id'];
        $fila['nombre'] = $row['nombre'];
        $fila['fecha_hora'] = $row['fecha_hora'];
        $fila['descripcion'] = $row['descripcion'];
        $fila['imagen'] = $row['imagen'];


        $eventos[] = $fila;
    }

    cerrarConexion($con);
    return $eventos;
}

//Comprueba si una ciudad existe
function existeCiudad($ciudad) {
    $link = crearConexion();
    $query = "SELECT * FROM `localizacion` WHERE `id`='$ciudad'";
    $result = mysqli_query($link, $query);
    cerrarConexion($link);

    if (mysqli_num_rows($result) < 1) {
        return FALSE;
    } else {
        return TRUE;
    }
}

//Inserta en la base de datos un nuevo usuario
function registraUsuario($usuario, $nombre, $apellidos, $password, $email, $sexo, $fech_nac, $ruta, $ciudad) {
    $link = crearConexion();
    $query = "INSERT INTO `usuario`(`username`, `nombre`, `apellidos`, `password`, `email`, `sexo`, `f_nacimiento`, `foto`, `ciudad`, `tipo`) VALUES ('$usuario','$nombre','$apellidos','" . password_hash($password, PASSWORD_BCRYPT) . "','$email','$sexo','$fech_nac','$ruta','$ciudad','0')";
    mysqli_query($link, $query);
    cerrarConexion($link);
}

function registro() {
    $nombre = trim(filter_var($_POST['nombre'], FILTER_SANITIZE_STRING));
    $apellidos = trim(filter_var($_POST['apellidos'], FILTER_SANITIZE_STRING));
    $usuario = trim(filter_var($_POST['usuario'], FILTER_SANITIZE_STRING));
    $password = trim(filter_var($_POST['password'], FILTER_SANITIZE_STRING));
    $email = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
    $sexo = trim(filter_var($_POST['sexo'], FILTER_SANITIZE_STRING));
    $fech_nac = trim(filter_var($_POST['fech_nac'], FILTER_SANITIZE_STRING));
    $ciudad = trim(filter_var($_POST['ciudad'], FILTER_SANITIZE_NUMBER_INT));

    if (!existeCiudad($ciudad)) {
        $error['ciudad'] = TRUE;
    }
    if ($nombre == "") {
        $error['nombre'] = TRUE;
    }
    if ($apellidos == "") {
        $error['apellidos'] = TRUE;
    }
    if ($usuario == "") {
        $error['usuario'] = TRUE;
    }
    if ($password == "") {
        $error['password'] = TRUE;
    }
    if ($email == "") {
        $error['email'] = TRUE;
    }
    if ($sexo == "") {
        $error['sexo'] = TRUE;
    }
    if ($fech_nac == "") {
        $error['fech_nac'] = TRUE;
    }

    if ($_FILES['imagen_usu']['error'] < 1) {
        if ($_FILES['imagen_usu']['size'] > 2000) {
            $error['imagen_tam'] = TRUE;
        } else if ($_FILES['imagen_usu']['type'] != 'image/jpeg' && $_FILES['imagen_usu']['type'] != 'image/png') {
            $error['imagen_tipo'] = TRUE;
        }
    } else {
        $no_imagen = TRUE;
    }

    $link = crearConexion();
    $query = "SELECT * FROM `usuario` WHERE `username`='" . $usuario . "'";
    $result = mysqli_query($link, $query);
    cerrarConexion($link);
    if (mysqli_num_rows($result) > 0) {
        $error['usuario_existente'] = TRUE;
    }

    if (!isset($error)) {
        if (isset($no_imagen)) {
            $ruta = "images/default_user.png";
        } else {
            $ruta = "images/user_images/$usuario.jpeg";
            move_uploaded_file($_FILES['imagen_usu']['tmp_name'], $ruta);
        }

        registraUsuario($usuario, $nombre, $apellidos, $password, $email, $sexo, $fech_nac, $ruta, $ciudad);
        header('Location: index.php');
    }
}
function comprobarSizeFoto($img) {
    if ($img > 1000000) {
        return TRUE;
    } else {
        return FALSE;
    }
}
