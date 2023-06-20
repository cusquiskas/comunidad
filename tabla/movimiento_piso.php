<?php
    class View_MOVIMIENTO_PISO {
        public function saldo_pisos($comunidad) {
            $link = new ConexionSistema();
            $resu = $link->consulta("select sum(mov_importe) saldo, 
                                            pis_nombre piso
                                       from MOVIMIENTO, PISO
                                      where mov_comunidad = ?
                                        and mov_piso = pis_piso
                                      group 
                                         by pis_nombre
                                      ORDER
                                         by pis_piso", 
                                    [0 => ['tipo' => 's', 'dato' => $comunidad]
                                    ]);
            $link->close();
            unset($link);
            return $resu;
        }

        public function __construct() {
            
        }
    }
?>