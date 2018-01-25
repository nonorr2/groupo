<?php
//Muestra los eventos por categorias
session_start();
require("./remote_php/funcionesPersistencia.php");
include('./cabecera.php');

$eventos = leerEventosPorCategoria($_GET['categoria']);
?>
<!-- Header Image with Logo Text -->
<header class="bgimg-2 w3-display-container w3-opacity-min bgimg-general"  id="top">
    <div class="w3-display-middle" style="white-space:nowrap;">
        <span class="w3-center w3-padding-large w3-black w3-xlarge w3-wide w3-animate-opacity" style="font-family: 'Lobster Two', cursive"><?php echo $_GET['categoria']; ?></span>
    </div>
</header>

<!-- Categorias de Grupos -->
<article id = "categoriaGrupo">   
    <?php
    foreach ($eventos as $evento) {
        
        echo '<div class="gruposCategoria">';
        echo '<div class="imagenCabecera" style="background-image:url(' . $evento['imagen'] . ')"></div>';
        echo '<img src="' . $evento['imagen'] . '" alt="' . $evento['nombre'] . '" class="imgCircular"/>';
        echo '<div class="contenidoCategoria">';
        echo '<h3 id="nombreCategoriaGrupo">' . $evento['nombre'] . '</h3>';
        echo '<p id="descripcionCategoriaGrupo">' . $evento['descripcion'] . '</p>';
        echo '</div>';
        echo '</div>';
    }
    ?>
</article>
<?php include('./footer.php'); ?>


