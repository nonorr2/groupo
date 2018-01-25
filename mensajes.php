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
<?php include('./cabecera-login.php'); ?>

<header class="bgimg-2 w3-display-container w3-opacity-min bgimg-general"  id="top">
    <div class="w3-display-middle" style="white-space:nowrap;">
        <span class="w3-center w3-padding-large w3-black w3-xlarge w3-wide w3-animate-opacity" style="font-family: 'Lobster Two', cursive">Mensajes</span>
    </div>
</header>
<script>
    mostrado = false;
</script>
<article id="activa">
    <div class="add" onclick="muestraListado()"><img src="images/add.png"></div>
        <?php
        $link = crearConexion();
        $query = "SELECT usuario.username, usuario.foto FROM usuario, mensaje WHERE (mensaje.usu_remitente='" . $_SESSION['usuario_groupo'] . "' AND usuario.username=mensaje.usu_destinatario) OR (mensaje.usu_destinatario='" . $_SESSION['usuario_groupo'] . "' AND usuario.username=mensaje.usu_remitente) GROUP BY usuario.username";
        $result = mysqli_query($link, $query);
        cerrarConexion($link);
        while ($linea = mysqli_fetch_array($result)) {
            echo '<a href="conversacion.php?usuario=' . $linea['username'] . '">';
            echo '<div class="caja_mensaje">';
            echo '<div class="caja_img_mensaje">';
            echo '<img src="' . $linea['foto'] . '">';
            echo '</div>';
            echo '<p>' . $linea['username'] . '</p>';
            echo '</div>';
            echo '</a>';
        }
        ?>
</article>
<?php include ('./footer.php'); ?>
