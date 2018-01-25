<?php
//GRUPO NO LOGUEADO
session_start();
require("./remote_php/funcionesPersistencia.php");
include('./cabecera.php');
?>
<!-- Header Image with Logo Text -->
<header class="bgimg-2 w3-display-container w3-opacity-min bgimg-general"  id="top">
    <div class="w3-display-middle" style="white-space:nowrap;">
        <span class="w3-center w3-padding-large w3-black w3-xlarge w3-wide w3-animate-opacity" style="font-family: 'Lobster Two', cursive">Grupos</span>
    </div>
</header>

<article>        

    <!-- Grupos -->
    <section class="w3-content w3-container w3-padding-64" id="explora">
        <h3 class="w3-center">DESCUBRE</h3>                

        <!-- categorias -->
        <div class="w3-row w3-grayscale-min" id="categoriasExplorar">

            <?php
            $numCategorias = contarCategorias();
            
            if(isset($_GET['pagina']) && "" != $_GET['pagina'] && !is_nan($_GET['pagina'])){
                $pagina = $_GET['pagina'];
            }else{
                $pagina = 1;
            }
            
            $valoresPaginacion = obtenerValoresPaginacion($pagina, $numCategorias, NUM_CATEGORIAS_POR_PAGINA);            
            $categorias = leerCategoriasPaginadas(NUM_CATEGORIAS_POR_PAGINA, $valoresPaginacion["offset"]);

            foreach ($categorias as $categoria) {
                echo '<div class = "w3-quarter">';
                echo '<a href="gruposCategoria.php?categoria=' . $categoria['nombre'] . '"><img src = "' . $categoria['imagen'] . '" style = "width:100%" alt = "' . $categoria['descripcion'] . '"></a>';
                echo '</div>';
            }
            ?>
        </div>
        <!-- Paginación -->
        <div class="w3-center w3-padding-32">
            <div class="w3-bar">
                <a href="grupos.php?pagina=<?php echo $valoresPaginacion["paginaAnt"] ?>" class="w3-bar-item w3-button w3-hover-black">«</a>
            
            <?php
            for($i = 0; $i < $valoresPaginacion["paginaMaxima"]; $i++){
                echo '<a href="grupos.php?pagina=' .($i + 1). '" class="w3-bar-item w3-black w3-button">' .($i + 1). '</a>';
            }
            
            ?>
                <a href="grupos.php?pagina=<?php echo $valoresPaginacion["paginaSig"] ?>" class="w3-bar-item w3-button w3-hover-black">»</a>
            </div>
        </div>
    </section>
</article>

<?php include('./footer.php'); ?>

