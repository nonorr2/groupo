<!-- Gestiona la creación y la modificación de las categorías -->
<?php
session_start();
require("./remote_php/funcionesPersistenciaLogeado.php");
//Si el usuario esta logueado o no lo esta se redirigen a otra página
if (!isset($_SESSION['usuario_groupo'])) {
    header("Location: index.php");
} else if ($_SESSION['tipo_groupo'] != 1) {
    header("Location: inicioLogeado.php");
}
?>


<?php
//Para crear una nueva categoría
if (isset($_POST['envioCrearCategoria'])) {
    $errores = comprobarCrearModificarCategoria(FALSE);
    $error_foto = compruebaFoto();

    if (sizeof($errores) == 0 && $error_foto == "") {        
        crearCategoria();
        header("Location: categorias-admin.php");
    }
    
//Para modificar una categoría ya existente    
} else if (isset($_POST['envioModificarCategoria'])) {
    $errores = comprobarCrearModificarCategoria(TRUE);
    $reenvio = FALSE;

    if ($_FILES['imagen']['size'] > 0) {
        
          $error_foto = compruebaFoto();

        if (sizeof($errores) == 0 && $error_foto == "") {
            updateCategoria();
            $reenvio = TRUE;
        }
    } else {
        if (sizeof($errores) == 0) {
            updateCategoriaSinFoto();
            $reenvio = TRUE;
        }
    }
    
    if($reenvio){
        header("Location: categorias-admin.php");
    }
}

//Si se pulsa el icono para modificar, se obtienen todos los datos de la categoría, mediante el nombre de dicha categoria
if (isset($_GET['nombre'])) {
    $categoria = obtenerCategoriaAmodificar($_GET['nombre']);
}
?>
<?php include ('./cabecera-admin.php'); ?>
<header class="bgimg-2 w3-display-container w3-opacity-min bgimg-general"  id="top">
    <div class="w3-display-middle" style="white-space:nowrap;">
        <span class="w3-center w3-padding-large w3-black w3-xlarge w3-wide w3-animate-opacity" style="font-family: 'Lobster Two', cursive">
            <?php
            if (isset($categoria)) {
                echo $categoria['nombre'];
            } else {
                echo 'Crear categoria';
            }
            ?>            
        </span>
    </div>
</header>
<article id="crearCategoria">
    <!-- div para mostrar los posibles errores en el formulario -->
    <div class="erroresFormulario">
        <?php
        if (isset($errores) && sizeof($errores) > 0) {
            echo '<div><ul>';

            foreach ($errores as $error) {
                echo '<li>' . $error . '</li>';
            }

            if (isset($error_foto)) {
                echo '<li>' . $error_foto . '</li>';
            }
            echo '</ul></div>';
        }
        ?>
    </div>

    <form method="POST" enctype="multipart/form-data" onsubmit="return compruebaErroresCategoria()" class="form_categoria_admin">
        <div>
        <label class="labelFoto" >Foto</label>
        <input type="file" class="img_crear_categoria" name="imagen" value="Imagen de la categoria">
        </div>
        <div>
        <label id="nombre">Nombre</label><input type="text" name="nombre" id="nombreCategoria" 
        <?php
        if (isset($categoria)) {
            echo 'value="' . $categoria['nombre'] . '" readonly';
        }
        ?>
        />
        </div>
        <div>
        <label id="labeldescripcion">Descripci&oacute;n</label> 
        <?php
        if (isset($categoria)) {
            echo '<textarea id="textoDescripcion" class="textoDescripcion" name="texto_descripcion">' . $categoria['descripcion'] . '</textarea>';
            echo '<input type="submit" value="Modificar" name="envioModificarCategoria" id="envioModificarCategoria" />';
        } else {
            echo '<textarea id="textoDescripcion" class="textoDescripcion" name="texto_descripcion"></textarea>';
            echo '<input type="submit" value="Crear categoria" name="envioCrearCategoria" id="envioCrearCategoria" />';
        }
        ?>
        </div>
    </form>
</article>    

<?php include('./footer.php'); ?>