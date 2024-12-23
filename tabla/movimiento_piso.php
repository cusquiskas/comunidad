<?php
    class View_MOVIMIENTO_PISO {
        public function saldo_pisos($comunidad) {
            $link = new ConexionSistema();
            $resu = $link->consulta("select sum(saldo) saldo,
                                            piso
                                       from (
                                             select mov_importe saldo, 
                                                    pis_nombre  piso
                                               from MOVIMIENTO, PISO
                                              where mov_comunidad = ?
                                                and mov_piso      = pis_piso
                                                and mov_movimiento not in (select spl_movimiento
                                                                             from SPLIT
                                                                            where spl_comunidad = ?)
                                              UNION      
                                             select spl_importe saldo, 
                                                    pis_nombre  piso
                                               from SPLIT, PISO
                                              where spl_comunidad = ?
                                               and spl_piso       = pis_piso
                                              UNION
                                             SELECT dud_importe saldo,
                                                    pis_nombre PISO
                                               from DEUDA, PISO
                                              WHERE dud_comunidad = ?
                                                and  dud_piso = pis_piso
                                            ) datos
                                      group 
                                        by piso
                                     order
                                        by piso", 
                                    [0 => ['tipo' => 'i', 'dato' => $comunidad],
                                     1 => ['tipo' => 'i', 'dato' => $comunidad],
                                     2 => ['tipo' => 'i', 'dato' => $comunidad],
                                     3 => ['tipo' => 'i', 'dato' => $comunidad]
                                    ]);
            $link->close();
            unset($link);
            return $resu;
        }

        public function __construct() {
            
        }
    }
?>