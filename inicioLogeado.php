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
include('./cabecera-login.php');
?>

<header class="bgimg-2 w3-display-container w3-opacity-min bgimg-general"  id="top">
    <div class="w3-display-middle" style="white-space:nowrap;">
        <span class="w3-center w3-padding-large w3-black w3-xlarge w3-wide w3-animate-opacity" style="font-family: 'Lobster Two', cursive">Groupo</span>
    </div>
</header>

<article id="artculo_novedades">
    <section id="novedades">
        <h2>Mientras no estabas</h2>    

        <?php
        $grupos = leerGruposNovedades($_SESSION['usuario_groupo'], $_SESSION['ultima_sesion_groupo']);

        foreach ($grupos as $grupo) {
            if ($grupo[1] > 0) {
                echo '<div class="grupoNovedades">';
                echo '<a href="grupo.php?grupo=' . $grupo[0] . '">';
                echo '<img src="' . $grupo[2] . '" alt="' . $grupo[3] . '" class="imgCircular" />';
                echo '<span id="nombreNovedades">' . $grupo[0] . '</span>';
                echo '<span id="publicacionesNovedades">' . $grupo[1] . ' publicaciones nuevas</span>';
                echo '</a>';
                echo '</div>';
            } else {
                echo '<h2>NO HAY NOVEDADES!</h2>';
            }
        }
        ?>
    </section>
    <section id="quizasAsistir">
        <h2>Quiz&aacute;s quieras asistir</h2>

        <?php
        $eventos = eventosNoInscrito($_SESSION['usuario_groupo']);
        echo '<div id="eventosRecomendados">';
if(sizeof($eventos)==0){
            echo '<h2>No hay ningún evento pendiente</h2>';
        }
        foreach ($eventos as $evento) {
            $aux = "url('$evento[4]')";
            echo '<div class="quizasAsistirEvento" id="' . $evento[0] . '" style="background-image: ' . $aux . '">';
            echo '<h2>' . $evento[1] . '</h2>';
            echo '</div>';
        }
        echo '</div>';
        ?>
    </section>
</article>
<?php include('./footer.php'); ?>
