<?php
session_start();
require("./remote_php/funcionesPersistencia.php");
include('./cabecera.php');

$grupos = leerGruposPorCategoria($_GET['categoria']);
?>
<!-- Header Image with Logo Text -->
<header class="bgimg-2 w3-display-container w3-opacity-min bgimg-general"  id="top">
    <div class="w3-display-middle" style="white-space:nowrap;">
        <span class="w3-center w3-padding-large w3-black w3-xlarge w3-wide w3-animate-opacity" style="font-family: 'Lobster Two', cursive"><?php echo $_GET['categoria'] ?></span>
    </div>
</header>

<!-- Categorias de Grupos -->
<article id = "categoriaGrupo">   
    <?php
    foreach ($grupos as $grupo) {
        echo '<div class="gruposCategoria">';
        echo '<div class="imagenCabecera" style="background-image:url(' . $grupo['imagen'] . ')"></div>';
        echo '<img src="' . $grupo['imagen'] . '" alt="' . $grupo['nombre'] . '" class="imgCircular" />';
        echo '<div class="contenidoCategoria">';
        echo '<h3 id="nombreCategoriaGrupo">' . $grupo['nombre'] . '</h3>';
        echo '<p id="descripcionCategoriaGrupo">' . $grupo['descripcion'] . '</p>';
        echo '</div>';
        echo '</div>';
    }
    ?>
</article>
<?php include('./footer.php'); ?>


