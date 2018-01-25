<?php
session_start();
require("./remote_php/funcionesPersistencia.php");
include('./cabecera.php');
?>

<!-- Header Image with Logo Text -->
<header class="bgimg-1 w3-display-container w3-opacity-min bgimg-general" id="home">
    <div class="w3-display-middle" style="white-space:nowrap;">
        <span id="logo" class="w3-center w3-padding-large w3-black w3-xlarge w3-wide w3-animate-opacity" style="font-family: 'Lobster Two', cursive">Groupo</span>
    </div>
</header>

<article>        
    <!-- Sobre nosotros -->
    <section class="w3-content w3-container w3-padding-64" id="about">
        <h3 class="w3-center">Amistad</h3>
        <p class="w3-center">La amistad es una de las relaciones interpersonales más comunes que la mayoría de las personas tienen en la vida. La amistad se da en distintas etapas de la vida y en diferentes grados de importancia y trascendencia. La amistad nace cuando las personas encuentran inquietudes y sentimientos comunes al igual que confianza mutua. Hay amistades que nacen a los pocos minutos de relacionarse y otras que tardan años en hacerlo.</p>
    </section>

    <!-- Explora -->
    <section class="w3-content w3-container w3-padding-64" id="explora">
        <h3 class="w3-center">EXPLORA</h3>                

        <!-- categorias -->
        <div class="w3-row w3-grayscale-min" id="categoriasExplorar">

            <?php
            $categorias = leerCategorias();

            foreach ($categorias as $categoria) {
                echo '<a href="gruposCategoria.php?categoria=' . $categoria['nombre'] . '">';
                echo '<div class = "w3-quarter">';
                echo '<img src = "' . $categoria['imagen'] . '" style = "width:100%" alt = "' . $categoria['descripcion'] . '">';
                echo '</div>';
                echo '</a>';
            }
            ?>

        </div>

        <!-- Pagination -->
        <div class="w3-center w3-padding-32">
            <div class="w3-bar">
                <a href="#" class="w3-bar-item w3-button w3-hover-black">«</a>
                <a href="#" class="w3-bar-item w3-black w3-button">1</a>
                <a href="#" class="w3-bar-item w3-button w3-hover-black">2</a>
                <a href="#" class="w3-bar-item w3-button w3-hover-black">3</a>
                <a href="#" class="w3-bar-item w3-button w3-hover-black">4</a>
                <a href="#" class="w3-bar-item w3-button w3-hover-black">»</a>
            </div>
        </div>
    </section>
</article>
<?php include('./footer.php'); ?>

