<?php
session_start();
require("./remote_php/funcionesPersistenciaLogeado.php");

if (isset($_GET['usuario'])) {
    if (!isset($_SESSION['usuario_groupo'])) {
        header("Location: index.php");
    } else if ($_SESSION['tipo_groupo'] != 1) {
        header("Location: inicioLogeado.php");
    }
    $admin = true;
} else {
    if (!isset($_SESSION['usuario_groupo'])) {
        header("Location: index.php");
    } else if ($_SESSION['tipo_groupo'] == 1) {
        header("Location: usuarios-admin.php");
    }
}


if (isset($_POST['modificar_perfil'])) {
    if (comprobarPerfil($_SESSION['usuario_groupo'])) {
        header("Location: inicioLogeado.php");
    } else {
        ?>
        <script type="text/javascript">
            alert('No ha podido ser modificado el usuario');
        </script>
        <?php
    }
}

if (isset($_POST['modificar_perfil_admin'])) {
    if (comprobarPerfilDeUsuario_admin($_GET['usuario'])) {
        header("Location: usuarios-admin.php");
    } else {
        ?>
        <script type="text/javascript">
            alert('No ha podido ser modificado el usuario');
        </script>
        <?php
    }
}

if (isset($_POST['enviar_password'])) {
    if (botCambiarPassword($_SESSION['usuario_groupo'])) {
        ?>
        <script type="text/javascript">
            window.alert('La contraseña ha sido cambiada correctamente');
        </script>
        <?php
        header("Location: inicioLogeado.php");
    }
}

if (isset($_GET['usuario'])) {
    //usuario administrador
    include('./cabecera-admin.php');
} else {
    //usuario logeado
    include('./cabecera-login.php');
}
?>
<script>

    function botCambiarPassword() {
        $("#perfil").hide();
        var contenedor = document.getElementById("form_perfil");
        var divFormulario = document.createElement("div");
        divFormulario.setAttribute("id", "divFormCambioPasswod");
        contenedor.appendChild(divFormulario);
        var titulo = document.createElement("h2");
        titulo.setAttribute("id", "tituloCambiarPassw");
        divFormulario.appendChild(titulo);
        var cerrar = document.createElement("img");
        cerrar.setAttribute("src", "images/remove.png");
        cerrar.setAttribute("id", "cerrar");
        cerrar.setAttribute("onclick", "cerrarVentanaCambiarPassw()");
        divFormulario.appendChild(cerrar);
        var texto = document.createTextNode("Cambiar Contraseña");
        titulo.appendChild(texto);
        var formulario = document.createElement("form");
        formulario.setAttribute("id", "formCambioPassword");
        formulario.setAttribute("method", "POST");
        formulario.setAttribute("enctype", "multipart/form-data");
        formulario.setAttribute("onsubmit", "return comprobarErroresPasword()");
        var inputs = document.createElement("div");
        inputs.setAttribute("id", "contenedorInputs");
        var password = document.createElement("input");
        password.setAttribute("type", "password");
        password.setAttribute("id", "passwordActual");
        password.setAttribute("placeholder", "Contraseña Actual");
        password.setAttribute("name", "passwordActual");
        var nuevaPassword = document.createElement("input");
        nuevaPassword.setAttribute("type", "password");
        nuevaPassword.setAttribute("id", "passwordNueva");
        nuevaPassword.setAttribute("placeholder", "Nueva Contraseña");
        nuevaPassword.setAttribute("name", "passwordNueva");
        var repetirPassword = document.createElement("input");
        repetirPassword.setAttribute("type", "password");
        repetirPassword.setAttribute("id", "repetirPassword");
        repetirPassword.setAttribute("placeholder", "Repetir Contraseña");
        repetirPassword.setAttribute("name", "repetirPassword");
        inputs.appendChild(password);
        inputs.appendChild(nuevaPassword);
        inputs.appendChild(repetirPassword);
        formulario.appendChild(inputs);
        var divEnviar = document.createElement("div");
        divEnviar.setAttribute("id", "enviarPassword");
        formulario.appendChild(divEnviar);
        var enviar = document.createElement("input");
        enviar.setAttribute("type", "submit");
        enviar.setAttribute("value", "Enviar");
        enviar.setAttribute("name", "enviar_password");
        divEnviar.appendChild(enviar);
        divFormulario.appendChild(formulario);
    }

    function cerrarVentanaCambiarPassw() {
        $("#cerrar").click(function () {
            $("#perfil").show();
            $("#divFormCambioPasswod").remove();
        });
    }

</script>
<!-- Header Image with Logo Text -->
<header class="bgimg-2 w3-display-container w3-opacity-min bgimg-general"  id="top">
    <div class="w3-display-middle" style="white-space:nowrap;">
        <span class="w3-center w3-padding-large w3-black w3-xlarge w3-wide w3-animate-opacity" style="font-family: 'Lobster Two', cursive">Perfil</span>
    </div>
</header>
<article id="registro">
    <?php
    if (isset($error)) {
        echo 'Errores en el formulario';
    }
    ?>
    <div id = "form_perfil">        
        <form id="perfil" method="POST" 
              <?php
              if (isset($admin)) {
                  echo 'onsubmit="return comprobarErroresPerfilAdmin()"';
              } else {
                  echo 'onsubmit="return comprobarErroresPerfil()"';
              }
              ?>
              enctype="multipart/form-data">
                  <?php
                  if ($_SESSION['tipo_groupo'] == 1) {
                      $usuarioLog = leerUsuarioLogin($_GET['usuario']);
                  } else {
                      $usuarioLog = leerUsuarioLogin($_SESSION['usuario_groupo']);
                  }
                  echo '<div id = "selectArchivo">';
                  echo '<img class="elemento_img_derecha" src="' . $usuarioLog['foto'] . '?' . time() . '" alt="' . $usuarioLog['nombre'] . '"/>';
                  echo '<input id="botonFoto" type="file" name="imagen_usu" value="Imagen de usuario">';
                  echo '</div>';
                  echo '<div class = "inputPerfil">';
                  echo '<div class="inputIzquierda">';
                  echo '<p>Nombre</p>';
                  echo '<input type="text" name="nombre" value="' . $usuarioLog['nombre'] . '" id="nombre" >';
                  echo '</div>';
                  echo '<div class="inputDerecha">';
                  echo '<p>Apellidos</p>';
                  echo '<input type="text" name="apellidos" value="' . $usuarioLog['apellidos'] . '" id="apellidos" >';
                  echo '</div>';
                  echo '</div>';
                  echo '<div class = "inputPerfil">';
                  echo '<div class="inputIzquierda">';
                  echo '<p>Nombre Usuario</p>';
                  echo '<input type="text" name="usuario" value="' . $usuarioLog['username'] . '" id="usuario" readonly>';
                  echo '</div>';
                  if ($_SESSION['tipo_groupo'] == 1) {
                      echo '<div class="inputDerecha">';
                      echo '<p>Contraseña</p>';
                      echo '<input type="password" name="password" id="password">';
                      echo '</div>';
                  } else {
                      echo '<div class="inputDerecha">';
                      echo '<button class="botCambiarPassword" type="button" onclick="botCambiarPassword()"  name="bot_cambiar_password" >Cambiar contraseña </button>';
                      echo '</div>';
                  }
                  echo '</div>';
                  echo '<div class = "inputPerfil">';
                  echo '<div class="inputIzquierda">';
                  echo '<p>Email</p>';
                  echo '<input type="email" name="email" value="' . $usuarioLog['email'] . '" id="email" >';
                  echo '</div>';
                  echo '<div class="inputDerecha">';
                  echo '<p>Fecha de Nacimiento</p>';
                  echo '<input type="date" name="fech_nac" value="' . $usuarioLog['f_nacimiento'] . '" id="fecha" >';
                  echo '</div>';
                  echo '</div>';
                  echo '<div class = "inputPerfil">';
                  echo '<div class="inputIzquierda">';
                  echo '<p>Ciudad</p>';
                  echo '<select name="ciudad" id="ciudad" >';
                  $ciudades = leerCiudades();
                  foreach ($ciudades as $ciudad) {
                      echo '<option value="' . $ciudad['id'] . '" selected="' . $usuarioLog['id'] . '">' . $ciudad['ciudad'] . '</option>';
                  }
                  echo '</select>';
                  echo '</div>';
                  echo '<div class="inputDerecha">';
                  echo '<p>Sexo</p>';
                  if ($usuarioLog['sexo'] == 'Hombre') {
                      echo '<input type="radio" name="sexo" value="Hombre" checked>Hombre';
                      echo '<input class="sexoMujer" type="radio" name="sexo" value="Mujer">Mujer';
                  } else {
                      echo '<input type="radio" name="sexo" value="Hombre">Hombre';
                      echo '<input class="sexoMujer" type="radio" name="sexo" value="Mujer" checked>Mujer';
                  }
                  echo '</div>';
                  echo '</div>';
                  if ($_SESSION['tipo_groupo'] == 1) {
                      echo '<input id="enviarPerfil" type="submit" value="Modificar" name="modificar_perfil_admin">';
                  } else {
                      echo '<input id="enviarPerfil" type="submit" value="Modificar" name="modificar_perfil">';
                  }
                  ?>
        </form>
    </div>
</article>
<?php include('./footer.php'); ?>
