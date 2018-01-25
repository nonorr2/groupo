<?php
//Gestiona la creación y modificarión de los grupos. Tanto para el administrador como para el usuario logeado
session_start();
require("./remote_php/funcionesPersistenciaLogeado.php");

if (isset($_GET['nombre_admin'])) {
    //usuario administrador
    if (!isset($_SESSION['usuario_groupo'])) {
        header("Location: index.php");
    } else if ($_SESSION['tipo_groupo'] != 1) {
        header("Location: inicioLogeado.php");
    }
    $admin = TRUE;
    $_GET['nombre'] = $_GET['nombre_admin'];
} else {
    //usuario logeado
    if (!isset($_SESSION['usuario_groupo'])) {
        header("Location: index.php");
    } else if ($_SESSION['tipo_groupo'] == 1) {
        header("Location: usuarios-admin.php");
    }
}

//Crear un nuevo grupo
if (isset($_POST['envioCrearGrupo'])) {
    $errores = comprobarCrearModificarGrupo(FALSE);
    $error_foto = compruebaFoto();

    if (sizeof($errores) == 0 && $error_foto == "") {
        insertGrupo($_SESSION['usuario_groupo']);
        header("Location: grupoLogeado.php");
    }
//Modificar un grupo ya existente    
} else if (isset($_POST['envioModificarGrupo'])) {
    $errores = comprobarCrearModificarGrupo(TRUE);

    if ($_FILES['imagen']['size'] > 0) {
        $error_foto = compruebaFoto();

        if (sizeof($errores) == 0 && $error_foto == "") {
            updateGrupo();

            if (isset($admin)) {
                header("Location: grupos-admin.php");
            } else {
                header("Location: grupoLogeado.php");
            }
        }
    } else {
        if (sizeof($errores) == 0) {
            updateGrupoSinFoto();
            if (isset($admin)) {
                header("Location: grupos-admin.php");
            } else {
                header("Location: grupoLogeado.php");
            }
        }
    }
}

//Si se pulsa el icono para modificar, se obtienen todos los datos del grupo, mediante el nombre de dicho grupo
if (isset($_GET['nombre'])) {
    $propietrario = compruebaMiGrupo($_GET['nombre'], $_SESSION['usuario_groupo']);

    if (!isset($admin)) {
        if (!$propietrario) {
            header("Location: grupoLogeado.php");
        }
    }

    $grupo = obtenerGrupoAmodificar($_GET['nombre']);
}

if (isset($_GET['nombre_admin'])) {
    //usuario administrador
    include('./cabecera-admin.php');
} else {
    //usuario logeado
    include('./cabecera-login.php');
}
?>

<header class="bgimg-2 w3-display-container w3-opacity-min bgimg-general"  id="top">
    <div class="w3-display-middle" style="white-space:nowrap;">
        <span class="w3-center w3-padding-large w3-black w3-xlarge w3-wide w3-animate-opacity" style="font-family: 'Lobster Two', cursive">
            <?php
            if (isset($grupo)) {
                echo $grupo['nombre'];
            } else {
                echo 'Crear grupo';
            }
            ?>
        </span>
    </div>
</header>

<article id="crearModificarGrupo">

    <?php
    //div para mostrar los posibles errores en el formulario
    if (isset($errores) && (sizeof($errores) > 0 || $error_foto != "")) {
        echo '<div><ul>';

        foreach ($errores as $error) {
            echo '<li>' . $error . '</li>';
        }

        if ($error_foto != "") {
            echo '<li>' . $error_foto . '</li>';
        }
        echo '</ul></div>';
    }
    ?>

    <form method="POST" enctype="multipart/form-data" onsubmit="return compruebaErroresGrupo()" class="crear_grupo_evento">
        <div>
            <label class="labelFoto">Foto</label>
            <input type="file" id="imagenGru" class="img_grupo_evento" name="imagen" value="Imagen del grupo" style="width: auto!important;">
        </div>
        <div>
            <label id="labelNombre">Nombre</label>
            <input type="text" name="nombreGrupo" id="nombreGrupo" placeholder="Nombre del grupo"
            <?php
            if (isset($grupo)) {
                echo 'value="' . $grupo['nombre'] . '" readonly';
            }
            ?>
                   />
        </div>
        <div>
            <label id="labelCategoria">Categor&iacute;a</label>        
            <select name="selectCategoria" id="selectCategoria">
                <?php
                $categorias = leerCategoriasLogueado();

                foreach ($categorias as $categoria) {
                    if (isset($grupo) && $grupo['categoria'] == $categoria) {
                        echo '<option value="' . $categoria . '" selected>' . $categoria . '</option>';
                    } else {
                        echo '<option value="' . $categoria . '">' . $categoria . '</option>';
                    }
                }
                ?>
            </select>
        </div>
        <div>
            <label id="labelDescripcion">Descripci&oacute;n</label> 
            <?php
            if (isset($grupo)) {
                echo '<textarea id="textoDescripcionGru" class="textoDescripcion" name="textoDescripcion" placeholder="Descripci&oacute;n del evento">' . $grupo['descripcion'] . '</textarea>';
                echo '<input type="submit" value="Modificar" name="envioModificarGrupo" id="envioModificarGrupo" />';
            } else {
                echo '<textarea id="textoDescripcionGru" class="textoDescripcion" name="textoDescripcion" placeholder="Descripci&oacute;n del evento"></textarea>';
                echo '<input type="submit" value="Crear grupo" name="envioCrearGrupo" id="envioCrearGrupo" />';
            }
            ?>
        </div>
    </form>    

</article>     

<?php include('./footer.php'); ?>
