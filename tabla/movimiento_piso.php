<?php
    class View_MOVIMIENTO_PISO {
        public function saldo_pisos($comunidad) {
            $link = new ConexionSistema();
            $resu = $link->consulta("select sum(saldo) saldo,
                                            piso,
                                            cpiso
                                       from (
                                             select mov_importe saldo, 
                                                    pis_nombre  piso,
                                                    pis_piso    cpiso
                                               from MOVIMIENTO, PISO
                                              where mov_comunidad = ?
                                                and mov_piso      = pis_piso
                                                and mov_movimiento not in (select spl_movimiento
                                                                             from SPLIT
                                                                            where spl_comunidad = ?)
                                              UNION      
                                             select spl_importe saldo, 
                                                    pis_nombre  piso,
                                                    pis_piso    cpiso
                                               from SPLIT, PISO
                                              where spl_comunidad = ?
                                               and spl_piso       = pis_piso
                                              UNION
                                             SELECT dud_importe saldo,
                                                    pis_nombre PISO,
                                                    pis_piso   cpiso
                                               from DEUDA, PISO
                                              WHERE dud_comunidad = ?
                                                and  dud_piso = pis_piso
                                            ) datos
                                      group 
                                        by piso,
                                           cpiso
                                     order
                                        by piso", 
                                    [0 => ['tipo' => 'i', 'dato' => $comunidad],
                                     1 => ['tipo' => 'i', 'dato' => $comunidad],
                                     2 => ['tipo' => 'i', 'dato' => $comunidad],
                                     3 => ['tipo' => 'i', 'dato' => $comunidad]
                                    ]);
            
            for ($i=0; $i<count($resu); $i++) {
                $propietario = $link->consulta("SELECT CONCAT(pro_nombre, ' ', pro_apellidos) nombre  
                                                  FROM PROPIETARIO_PISO,
                                                       PROPIETARIO
                                                 WHERE ppi_propietario = pro_propietario
                                                   and ppi_desde <= STR_TO_DATE('".date('Y-m-d')."', '%Y-%m-%d')
                                                   and ppi_hasta >= STR_TO_DATE('".date('Y-m-d')."', '%Y-%m-%d')
                                                   and ppi_piso   = ".$resu[$i]["cpiso"],
                                                []);
                
                $resu[$i]["propietario"] = (count($propietario) > 0)?$propietario[0]['nombre']:"";
                
            }
            
            $link->close();
            unset($link);
            
            return $resu;
        }

        public function __construct() {
            
        }
    }
?>