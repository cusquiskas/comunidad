<?php
    class View_PROPIETARIO_PISO {
        public function lista_pisos($correo, $comunidad, $fecha, $piso=null) {
            $link = new ConexionSistema();
            $resu = $link->consulta("select *
                                       from PISO,
                                            PROPIETARIO_PISO,
                                            PROPIETARIO
                                      WHERE pis_piso = ppi_piso
                                        and pro_propietario = ppi_propietario
                                        and (pro_correo = ? or isnull(?) = 1)
                                        and pis_comunidad = ?
                                        and STR_TO_DATE(?,'%Y-%m-%d') BETWEEN ppi_desde and ppi_hasta
                                        and (ppi_piso = ? or isnull(?) = 1)", 
                                    [0 => ['tipo' => 's', 'dato' => $correo],
                                     1 => ['tipo' => 's', 'dato' => $correo],
                                     2 => ['tipo' => 'i', 'dato' => $comunidad],
                                     3 => ['tipo' => 's', 'dato' => $fecha],
                                     4 => ['tipo' => 's', 'dato' => $piso],
                                     5 => ['tipo' => 's', 'dato' => $piso]
                                    ]);
            $link->close();
            unset($link);
            return $resu;
        }

        public function __construct() {
            
        }
    }
?>