<?php
//Cabecera para el usuario logeado
session_start();
require("./remote_php/funcionesPersistenciaLogeado.php");
//Si el usuario no esta logeado o es administrador se redirigen a otra página
if (!isset($_SESSION['usuario_groupo'])) {
    header("Location: index.php");
}else if($_SESSION['tipo_groupo'] == 1){
    header("Location: usuarios-admin.php");
}
?>
<?php
//Representa un evento concreto, muestra la información de dicho evento

if (!isset($_GET['id'])) {
    header("Location: inicioLogeado.php");
} else {
    $data = getDatosEvento($_GET['id'], $_SESSION['usuario_groupo']);
    if (!$data) {
        header("Location: inicioLogeado.php");
    }
}

include ('./cabecera-login.php');
?>

<header class="bgimg-2 w3-display-container w3-opacity-min bgimg-general"  id="top">
    <div class="w3-display-middle" style="white-space:nowrap;">
        <span class="w3-center w3-padding-large w3-black w3-xlarge w3-wide w3-animate-opacity" style="font-family: 'Lobster Two', cursive"><?php echo $data['nombre']; ?></span>
    </div>
</header>

<article>
    <?php
    echo '<div id="contenedor_evento">';
    echo '<div class="cabecera" style="background-image:url(' . $data['imagen'] . ')"></div>';
    echo '<p>' . $data['descripcion'] . '</p>';
    echo '<p><b>Ciudad:</b> ' . $data['ciudad'] . '</p>';
    echo '<p><b>Dirección:</b> ' . $data['direccion'] . '</p>';
    echo '<p><b>CP:</b> ' . $data['cp'] . '</p>';
    echo '<p><b>Fecha y Hora:</b> ' . $data['fecha_hora'] . '</p>';
    echo '</div>';
    ?>
</article>
<?php include ('./footer.php'); ?>