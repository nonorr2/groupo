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

include ('./cabecera-login.php');
?>

<header class="bgimg-2 w3-display-container w3-opacity-min bgimg-general"  id="top">
    <div class="w3-display-middle" style="white-space:nowrap;">
        <span class="w3-center w3-padding-large w3-black w3-xlarge w3-wide w3-animate-opacity" style="font-family: 'Lobster Two', cursive"><?php echo $_GET['grupo']; ?></span>
    </div>
</header>

<article id="cont_conversacion_grupo">
    <section id="conversacion_grupo">
        <?php
        $conversacion = getConversacionGrupal($_GET['grupo']);
        foreach ($conversacion as $msj) {
            if ($msj['usuario'] == $_SESSION['usuario_groupo']) {
                echo '<div class="mensaje mensaje_grupo">';
                echo '<img class="elemento_img_derecha" src="' . $_SESSION['foto_groupo'] . '">';
                echo '<h2>' . $_SESSION['usuario_groupo'] . '</h2>';
                echo '</br>';
                echo '<h2>' . $msj['titulo'] . '</h2>';
                if ($msj['censurada'] == 0) {
                    echo '<p>' . $msj['contenido'] . '</p>';
                } else {
                    echo '<p>********** El contenido ha sido censurado por el administrador **********</p>';
                }
                echo '<p>' . $msj['fecha_hora'] . '</p>';
                echo '</div>';
            } else {
                echo '<div class="mensaje mensaje_grupo">';
                echo '<img class="elemento_img_derecha" src="' . $msj['foto'] . '">';
                echo '<h2>' . $msj['usuario'] . '</h2>';
                echo '</br>';
                echo '<h2>' . $msj['titulo'] . '</h2>';
                if ($msj['censurada'] == 0) {
                    echo '<p>' . $msj['contenido'] . '</p>';
                } else {
                    echo '<p>********** El contenido ha sido censurado por el administrador **********</p>';
                }
                echo '<p>' . $msj['fecha_hora'] . '</p>';
                echo '</div>';
            }
        }
        ?>
    </section>

    <section id="escribe">
        <?php
        if (perteneceGrupo($_SESSION['usuario_groupo'], $_GET['grupo'])) {
            ?>
            <input type="text" class="titulo_mensaje" id="titulo_mensaje"  placeholder="Titulo" required>           
            <textarea class="texto_mensaje" id="texto_mensaje" placeholder="Mensaje"></textarea>
            <input type="hidden" value="<?php echo $_GET['grupo']; ?>" id="grupo_mensaje">
            <input type="submit" id="envio_mensaje" value="Enviar">
            <?php
        } else {
            echo '<h1>Debes unirte al grupo para hablar!</h1>';
        }
        ?>
    </section>

</article>



<script type="text/javascript">
    $("#envio_mensaje").click(function () {
        var titulo = document.getElementById("titulo_mensaje").value;
        var texto = document.getElementById("texto_mensaje").value;
        var grupo = document.getElementById("grupo_mensaje").value;
        $.ajax({
            type: 'POST',
            url: "./remote_php/mensajeria_grupo.php",
            data: {tit: titulo, text: texto, grup: grupo},
            success: function (data) {
                $("#conversacion_grupo").append('<div class="mensaje_grupo mensaje"><img class="elemento_img_derecha" src="' + data['foto'] + '"><h2>' + data['usuario'] + '</h2><h2>' + data['titulo'] + '</h2><p>' + data['contenido'] + '</p><p>' + data['fecha_hora'] + '</p></div>');
            },
            dataType: "json"
        });
        $("#titulo_mensaje").val("");
        $("#texto_mensaje").val("");
    });
</script>


<?php include ('./footer.php'); ?>