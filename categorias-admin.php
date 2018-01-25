<!-- Muestra la opcion categoria, a dicha opcion solo puede acceder el administrador-->
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
<?php include ('./cabecera-admin.php'); ?>
<header class="bgimg-2 w3-display-container w3-opacity-min bgimg-general"  id="top">
    <div class="w3-display-middle" style="white-space:nowrap;">
        <span class="w3-center w3-padding-large w3-black w3-xlarge w3-wide w3-animate-opacity" style="font-family: 'Lobster Two', cursive">Categorias</span>
    </div>
</header>

<!-- Menu latereal que presenta un cuadro de texto para poder buscar las categorías -->
<nav class="menuLateral">
    <input type="search" id="admin_search_category" placeholder="Nombre" onkeyup="muestraCategorias()"/>
</nav>

<!-- icono '+' mediente el cual se puede crear una nueva categoría -->
<article class="contenedor_derecha">
    <a class="add" href="crear-categoria.php"><img src="images/add.png"></a>
    <section id="list_categories">
    </section>
</article>

<script>
    $(document).ready(function(){
        muestraCategorias();
    });
</script>
<?php include ('./footer.php'); ?>