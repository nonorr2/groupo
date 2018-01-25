<?php
session_start();
require("./remote_php/funcionesPersistencia.php");
if (isset($_POST['envio_registro'])) {
    $nombre = trim(filter_var($_POST['nombre'], FILTER_SANITIZE_STRING));
    $apellidos = trim(filter_var($_POST['apellidos'], FILTER_SANITIZE_STRING));
    $usuario = trim(filter_var($_POST['usuario'], FILTER_SANITIZE_STRING));
    $password = trim(filter_var($_POST['password'], FILTER_SANITIZE_STRING));
    $email = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
    $sexo = trim(filter_var($_POST['sexo'], FILTER_SANITIZE_STRING));
    $fech_nac = trim(filter_var($_POST['fech_nac'], FILTER_SANITIZE_STRING));
    $ciudad = trim(filter_var($_POST['ciudad'], FILTER_SANITIZE_NUMBER_INT));

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
    if ($password == "") {
        $error['password'] = "El campo contraseña esta vacio";
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

    $link = crearConexion();
    $query = "SELECT * FROM `usuario` WHERE `username`='" . $usuario . "'";
    $result = mysqli_query($link, $query);
    cerrarConexion($link);
    if (mysqli_num_rows($result) > 0) {
        $error['usuario_existente'] = "Usuario ya registrado";
    }

    if (!isset($error)) {

        if ($_FILES['imagen_usu']['size']<=0) {
            $ruta = "images/default_user.png";
        } else {
            echo '<script type="text/javascript">';
            echo "</script>";
            $ruta = "images/user_images/$usuario.jpeg";
            move_uploaded_file($_FILES['imagen_usu']['tmp_name'], $ruta);
        }

        registraUsuario($usuario, $nombre, $apellidos, $password, $email, $sexo, $fech_nac, $ruta, $ciudad);
        header("Location: index.php");
    }
}

include('./cabecera.php');
?>

<script type="text/javascript">
    function compruebaErrores() {
        var nombre = document.getElementById("nombre").getAttribute("value");
        var apellidos = document.getElementById("apellidos").getAttribute("value");
        var usuario = document.getElementById("usuario").getAttribute("value");
        var password = document.getElementById("password").getAttribute("value");
        var email = document.getElementById("email").getAttribute("value");
        var date = document.getElementById("date").getAttribute("value");
        var ciudad = document.getElementById("ciudad").getAttribute("value");

        return (compruebaTexto(nombre) && compruebaTexto(apellidos) && compruebaUsuario(usuario) && compruebaTexto(password) && compruebaEmail(email) && compruebaFecha(date));
    }
</script>
<!-- Header Image with Logo Text -->
<header class="bgimg-2 w3-display-container w3-opacity-min bgimg-general"  id="top">
    <div class="w3-display-middle" style="white-space:nowrap;">
        <span class="w3-center w3-padding-large w3-black w3-xlarge w3-wide w3-animate-opacity" style="font-family: 'Lobster Two', cursive">Registrate</span>
    </div>
</header>
<article id="registro">
    <div class="erroresFormulario">
        <?php
        if (isset($error)) {
            echo '<p style="color:red">Errores...</p>';
            echo '<ul style="color:red">';
            foreach ($error as $e) {
                echo "<li>$e</li>";
            }
            echo '</ul>';
        }
        ?>
    </div>    

    <div id = "form_perfil">
        <form id="perfil" method="POST" onsubmit="return comprobarErroresRegistro()" enctype="multipart/form-data">
            <div class = "inputPerfil">
                <div class="inputIzquierda">
                    <p>Nombre</p>
                    <input type="text" name="nombre" id="nombre" >
                </div>
                <div class="inputDerecha">
                    <p>Apellidos</p>
                    <input type="text" name="apellidos" id="apellidos" >
                </div>
            </div>
            <div class = "inputPerfil">
                <div class="inputIzquierda">
                    <p>Nombre Usuario</p>
                    <input type="text" name="usuario" id="usuario" >
                </div>
                <div class="inputDerecha">
                    <p>Contraseña</p>
                    <input type="password" name="password" id="password" >
                </div>
            </div>
            <div class = "inputPerfil">
                <div class="inputIzquierda">
                    <p>Email</p>
                    <input type="email" name="email" id="email" >
                </div>
                <div class="inputDerecha">
                    <p>Fecha de Nacimiento</p>
                    <input type="date" name="fech_nac" id="fecha" >
                </div>
            </div>
            <div class = "inputPerfil">
                <div class="inputIzquierda">
                    <p>Ciudad</p>
                    <select name="ciudad" id="ciudad" >
                        <?php
                        $link = crearConexion();
                        $query = "SELECT * FROM `localizacion`";
                        $result = mysqli_query($link, $query);
                        while ($linea = mysqli_fetch_array($result)) {
                            echo '<option value="' . $linea['id'] . '">' . $linea['ciudad'] . '</option>';
                        }
                        cerrarConexion($link);
                        ?>
                    </select>
                </div>
                <div class="inputDerecha">
                    <p>Sexo</p>
                    <p>
                    <input type="radio" name="sexo" value="Hombre">Hombre
                    <input class="sexoMujer" type="radio" name="sexo" value="Mujer">Mujer
                    </p>
                </div>

            </div>
            <div class = "inputPerfil">
                <div class="inputIzquierda">
                    <p>Seleccionar foto de Perfil</p>
                    <input type="file" name="imagen_usu" value="Imagen de usuario">
                </div>
                <div class="inputDerecha">
                    <input id="enviarRegistro" type="submit" vaue="Registrarse" name="envio_registro">
                </div>
            </div>
        </form>
    </div>
</article>
<?php include('./footer.php'); ?>