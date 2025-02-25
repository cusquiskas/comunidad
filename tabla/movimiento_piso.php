<?php
    class View_MOVIMIENTO_PISO {
        public function saldo_pisos($comunidad) {
            $link = new ConexionSistema();
            $resu = $link->consulta("
            select sum(importe) importe, cpiso
              from (
                select mov_importe as importe,
                       mov_piso    as cpiso
                from MOVIMIENTO
                where mov_comunidad = ? 
                  and mov_piso IS NOT NULL
                  and mov_movimiento NOT IN  (select spl_movimiento from SPLIT where spl_comunidad = ?)
                UNION ALL
                select spl_importe as importe,
                       spl_piso    as cpiso
                from SPLIT
                where spl_comunidad = ?
                  and spl_piso IS NOT NULL
                UNION ALL
                select dud_importe as importe,
                       dud_piso    as cpiso 
                from DEUDA
                where dud_comunidad = ? 
              ) as datos
              group by cpiso
              order by cpiso
            ",
                                    [0 => ['tipo' => 'i', 'dato' => $comunidad],
                                     1 => ['tipo' => 'i', 'dato' => $comunidad],
                                     2 => ['tipo' => 'i', 'dato' => $comunidad],
                                     3 => ['tipo' => 'i', 'dato' => $comunidad]
                                    ]
            );
            for ($i=0; $i<count($resu); $i++) {
                $propietario = $link->consulta("SELECT CONCAT(pro_nombre, ' ', pro_apellidos) nombre  
                                                  FROM PROPIETARIO_PISO,
                                                       PROPIETARIO
                                                 WHERE ppi_propietario = pro_propietario
                                                   and ppi_desde <= STR_TO_DATE('".date('Y-m-d')."', '%Y-%m-%d')
                                                   and ppi_hasta >= STR_TO_DATE('".date('Y-m-d')."', '%Y-%m-%d')
                                                   and ppi_piso   = ".$resu[$i]["cpiso"],
                                                []);
                
                //$propietario = [];
                $resu[$i]["propietario"] = (count($propietario) > 0)?$propietario[0]['nombre']:"";

                $piso = $link->consulta("select pis_nombre as nombre from PISO where pis_piso = ".$resu[$i]["cpiso"], []);

                $resu[$i]["piso"] = $piso[0]['nombre'];
            }
            
            $link->close();
            unset($link);
            
            return $resu;
        }

        public function __construct() {
            
        }
    }
?>