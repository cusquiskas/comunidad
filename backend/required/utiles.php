<?php
    function buscarEnArray($array, $valorBuscado, $claveValor, $claveBuscada) {
        foreach ($array as $elemento) {
            if ($elemento[$claveValor] == $valorBuscado) {
                return $elemento[$claveBuscada];
            }
        }
        return null;
    }

?>