<?php

// Devuelve los valores necesarios para realizar la paginación.
// Pagina máxima: número máximo de páginas de la paginación. Depende del número total de elementos.
// Página actual: deberá ser mayor que 1 y menor que la página máxima.
// Página anterior: para volver a la página anterior. Sólo se permite si la página actual es mayor a 1.
// Página siguiente: para ir a la siguiente página. Sólo se permite si la página actual es menor que la máxima.
// Offset: para obtener los elementos a trozos de la base de datos.
function obtenerValoresPaginacion($paginaSolicitada, $numTotalElementos, $numElementosPorPagina) {
    $paginaMaxima = ceil($numTotalElementos / $numElementosPorPagina);    
    
    if ($paginaSolicitada > 1 && $paginaSolicitada <= $paginaMaxima) {
        $pagina = $paginaSolicitada;
    }else{
        $pagina = 1;
    }

    $paginaAnt = $pagina > 1 ? $pagina - 1 : $pagina;
    $paginaSig = $pagina < $paginaMaxima ? $pagina + 1 : $pagina;
    $offset = $numElementosPorPagina * ($pagina - 1);
    
    $valores["pagina"] = $pagina;
    $valores["paginaMaxima"] = $paginaMaxima;
    $valores["paginaAnt"] = $paginaAnt;
    $valores["paginaSig"] = $paginaSig;
    $valores["offset"] = $offset;
    
    return $valores;
}
