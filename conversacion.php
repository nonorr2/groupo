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
//Representa el chat entre dos usuario logeados
include ('./cabecera-login.php');
?>

<header class="bgimg-2 w3-display-container w3-opacity-min bgimg-general"  id="top">
    <div class="w3-display-middle" style="white-space:nowrap;">
        <span class="w3-center w3-padding-large w3-black w3-xlarge w3-wide w3-animate-opacity" style="font-family: 'Lobster Two', cursive"><?php echo $_GET['usuario']; ?></span>
    </div>
</header>

<article>
    <section id="conversacion">
        <?php
        $conversacion = getConversacion($_SESSION['usuario_groupo'], $_GET['usuario']);
        foreach ($conversacion as $msj) {
            if ($msj[0] == $_SESSION['usuario_groupo']) {
                echo '<div class="texto_enviado mensaje">';
                echo '<h2>' . $msj[0] . '</h2>';
                if ($msj[2]) {
                    echo '<p>************** Mensaje censurado por el administrador **************';
                } else {
                    echo '<p>' . $msj[1] . '</p>';
                }

                echo '</div>';
            } else {
                echo '<div class="texto_recibido mensaje">';
                echo '<h2>' . $msj[0] . '</h2>';
                if ($msj[2]) {
                    echo '<p>************** Mensaje censurado por el administrador **************';
                } else {
                    echo '<p>' . $msj[1] . '</p>';
                }
                echo '</div>';
            }
        }
        ?>
    </section>

    <section id="escribe">
        <textarea id="texto_mensaje" class="texto_mensaje"></textarea>         
        <input type="hidden" value="<?php echo $_GET['usuario']; ?>" id="destinatario">
        <input type="submit" id="envio_mensaje" value="Enviar">
    </section>

</article>



<script type="text/javascript">
    $("#envio_mensaje").click(function () {
        var destinatario = document.getElementById("destinatario").value;
        var texto = document.getElementById("texto_mensaje").value;
        if (texto != "") {
            $.ajax({
                type: 'POST',
                url: "remote_php/mensajeria.php",
                data: {dest: destinatario, text: texto},
                success: function (data) {
                    $("#conversacion").append('<div class="texto_enviado mensaje"><h2>' + data['nombre'] + '</h2><p>' + data['texto'] + '</p></div>');
                },
                dataType: "json"
            });
            $("#texto_mensaje").val("");
        }
    });
</script>


<?php include ('./footer.php'); ?>