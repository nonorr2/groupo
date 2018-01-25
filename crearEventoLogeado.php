<?php
//Gestiona la creación y modificarión de los eventos. Tanto para el administrador como para el usuario logeado
session_start();
require("./remote_php/funcionesPersistenciaLogeado.php");
if (isset($_GET['evento'])) {
    //usuario administrador
    if (!isset($_SESSION['usuario_groupo'])) {
        header("Location: index.php");
    } else if ($_SESSION['tipo_groupo'] != 1) {
        header("Location: inicioLogeado.php");
    }
    $admin = TRUE;
    $_GET['id'] = $_GET['evento'];
} else {
    //usuario logeado
    if (!isset($_SESSION['usuario_groupo'])) {
        header("Location: index.php");
    } else if ($_SESSION['tipo_groupo'] == 1) {
        header("Location: usuarios-admin.php");
    }
}

//Crear un nuevo evento
if (isset($_POST['envioCrearEvento'])) {
    $errores = comprobarCrearModificarEvento(FALSE);
    $error_foto = compruebaFoto();

    if (sizeof($errores) == 0 && $error_foto == "") {
        insertEvento($_SESSION['usuario_groupo']);
        header("Location: eventoLogeado.php");
    }

//Modificar un evento ya existente    
} else if (isset($_POST['envioModificarEvento'])) {
    $errores = comprobarCrearModificarEvento(TRUE);

    if ($_FILES['imagen']['size'] > 0) {
        $error_foto = compruebaFoto();

        if (sizeof($errores) == 0 && $error_foto == "") {
            updateEvento($_GET['id']);

            if (isset($admin)) {
                header("Location: eventos-admin.php");
            } else {
                header("Location: eventoLogeado.php");
            }
        }
    } else {
        if (sizeof($errores) == 0) {
            updateEventoSinFoto($_GET['id']);

            if (isset($admin)) {
                header("Location: eventos-admin.php");
            } else {
                header("Location: eventoLogeado.php");
            }
        }
    }
}

//Si se pulsa el icono para modificar, se obtienen todos los datos del evnto, mediante el id de dicho evento
if (isset($_GET['id'])) {
    $propietrario = compruebaMiEvento($_GET['id'], $_SESSION['usuario_groupo']);
    if (!isset($admin)) {
        if (!$propietrario) {
            header("Location: eventoLogeado.php");
        }
    }

    $evento = obtenerEventoPorId($_GET['id']);
}

if (isset($_GET['evento'])) {
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
            if (isset($evento)) {
                echo $evento['nombre'];
            } else {
                echo 'Crear evento';
            }
            ?>
        </span>
    </div>
</header>

<article id="crearModificarEvento">
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

    <form method="POST" enctype="multipart/form-data" class="crear_grupo_evento" onsubmit="return compruebaErroresEvento()">
        <div>
            <label class="labelFoto">Foto</label>
            <input type="file" id="imagen" class="img_grupo_evento" name="imagen" value="Imagen del grupo" style="width: auto!important;">          
        </div>
        <div>
            <label id="labelNombre">Nombre</label>
            <input type="text" name="nombreEvento" id="nombreEvento"
            <?php
            if (isset($evento)) {
                echo 'value="' . $evento['nombre'] . '" readonly';
            }
            ?>
                   />
        </div>
        <div>
            <label id="labelFechaHora">Fecha y hora</label>
            <input type="date" name="fecha" id="fecha" 
            <?php
            if (isset($evento)) {
                $fecha = explode(" ", $evento['fecha_hora'])[0];
                echo 'value="' . $fecha . '"';
            }
            ?>
                   />
            <input type="time" name="hora" id="hora" style="width: 80px!important;"
            <?php
            if (isset($evento)) {
                $hora = explode(" ", $evento['fecha_hora'])[1];
                $hora_rota = explode(":", $hora);
                echo 'value="' . $hora_rota[0] . ':' . $hora_rota[1] . '"';
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
                    if (isset($evento) && $evento['categoria'] == $categoria) {
                        echo '<option value="' . $categoria . '" selected>' . $categoria . '</option>';
                    } else {
                        echo '<option value="' . $categoria . '">' . $categoria . '</option>';
                    }
                }
                ?>
            </select>
        </div>
        <div>
            <label id="labelCiudad">Ciudad</label>        
            <select name="selectCiudad" id="selectCiudad">
                <?php
                $ciudades = leerCiudades();

                foreach ($ciudades as $ciudad) {
                    if (isset($evento) && $evento['id_ciudad'] == $ciudad['id']) {
                        echo '<option value="' . $ciudad['id'] . '" selected>' . $ciudad['ciudad'] . '</option>';
                    } else {
                        echo '<option value="' . $ciudad['id'] . '">' . $ciudad['ciudad'] . '</option>';
                    }
                }
                ?>
            </select>        
        </div>
        <div>
            <label id="labelCp">C&oacute;digo postal</label>
            <input type="text" name="cp" id="cp"
            <?php
            if (isset($evento)) {
                echo 'value="' . $evento['cp'] . '"';
            }
            ?>
                   />        
        </div>
        <div>
            <label id="labelDireccion">Direcci&oacute;n</label>
            <input type="text" name="direccion" id="direccion" 
            <?php
            if (isset($evento)) {
                echo 'value="' . $evento['direccion'] . '"';
            }
            ?>
                   />      
        </div>
        <div>
            <label id="labelDescripcion">Descripci&oacute;n</label> 
            <?php
            if (isset($evento)) {
                echo '<textarea id="textoDescripcion" class="textoDescripcion" name="textoDescripcion">' . $evento['descripcion'] . '</textarea>';
                echo '<input type="submit" value="Modificar" name="envioModificarEvento" id="envioModificarEvento" />';
            } else {
                echo '<textarea id="textoDescripcion" class="textoDescripcion" name="textoDescripcion" ></textarea>';
                echo '<input type="submit" value="Crear evento" name="envioCrearEvento" id="envioCrearEvento" />';
            }
            ?>
        </div>
    </form>
</article>

<?php include ('./footer.php'); ?>
