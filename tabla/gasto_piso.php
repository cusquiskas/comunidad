<?php
class Tabla_GASTO_PISO {
    private $gpi_gasto = null;
    private $gpi_gasto_signo = '=';
    private $gpi_gasto_case = 'S';
    private $gpi_piso = null;
    private $gpi_piso_signo = '=';
    private $gpi_piso_case = 'S';
    private $empty;
    private $array = [];
    private $error = [];
    public function getDatos(){return [ 'gpi_gasto' => $this->gpi_gasto
    ,'gpi_piso' => $this->gpi_piso
     ];}
    private function setDatos($array) { $this->gpi_gasto =       (array_key_exists('gpi_gasto',       $array) ? (strlen($array['gpi_gasto'])==0?null:(int) $array['gpi_gasto']) : $this->gpi_gasto);
    $this->gpi_gasto_signo = (array_key_exists('gpi_gasto_signo', $array) ? (string) $array['gpi_gasto_signo'] : '=');
    $this->gpi_gasto_case  = (array_key_exists('gpi_gasto_case',  $array) ? (string) strtoupper($array['gpi_gasto_case']) : 'S');
    $this->gpi_piso =       (array_key_exists('gpi_piso',       $array) ? (strlen($array['gpi_piso'])==0?null:(int) $array['gpi_piso']) : $this->gpi_piso);
    $this->gpi_piso_signo = (array_key_exists('gpi_piso_signo', $array) ? (string) $array['gpi_piso_signo'] : '=');
    $this->gpi_piso_case  = (array_key_exists('gpi_piso_case',  $array) ? (string) strtoupper($array['gpi_piso_case']) : 'S');
     return 0;}
    private function select() { 
                $status = 0;
                
    
                $datos = [ 0 => ['tipo' => 'i', 'dato' => $this->gpi_gasto]
    ,1 => ['tipo' => 'i', 'dato' => $this->gpi_piso]
     ];
    
                $query = "select * from GASTO_PISO where 1 = 1 
     and IFNULL(gpi_gasto, '!') $this->gpi_gasto_signo IFNULL( ? , IFNULL(gpi_gasto, '!'))
     and IFNULL(gpi_piso, '!') $this->gpi_piso_signo IFNULL( ? , IFNULL(gpi_piso, '!'))
    ";
                $link = new ConexionSistema(); 
                $this->array = $link->consulta($query, $datos); 
                if ($link->hayError()) {
                    $status = 1;
                    $this->error = $link->getListaErrores(); 
                }
                $link->close(); 
                unset($link); 
                return $status; 
            }
    private function emptyClass() { $this->setDatos($this->empty); return 0; }
    private function clearArray() { $this->array = $this->empty; return 0; }
    private function clearError() { $this->error = []; return 0; }
    public function getArray()    { return $this->array; }
    public function getListaErrores() { return $this->error; }
    public function give($array) { $this->emptyClass(); $this->clearArray();
     $this->clearError();
     $this->setDatos($array);
     return $this->select(); }
    private function insert()
                    {
                        $link = new ConexionSistema();
                        $this->clearError();
                        if (!is_null($this->gpi_gasto)) {$key = $link->consulta('select count(0) as cuenta from GASTO where gas_gasto = \''.$this->gpi_gasto.'\'', []);
    if ($key[0]["cuenta"] < 1) {$this->error[] = ['tipo'=>'Validacion', 'Campo'=>'gpi_gasto', 'Detalle' => 'Referencia no encontrada en GASTO'];}
    }if (!is_null($this->gpi_piso)) {$key = $link->consulta('select count(0) as cuenta from PISO where pis_piso = \''.$this->gpi_piso.'\'', []);
    if ($key[0]["cuenta"] < 1) {$this->error[] = ['tipo'=>'Validacion', 'Campo'=>'gpi_piso', 'Detalle' => 'Referencia no encontrada en PISO'];}
    }
                        
    if (is_null($this->gpi_gasto)) {
                            $this->error[] = ['tipo'=>'Validacion', 'Campo'=>'gpi_gasto', 'Detalle' => 'No puede ser NULO'];
                        }
    if (is_null($this->gpi_piso)) {
                            $this->error[] = ['tipo'=>'Validacion', 'Campo'=>'gpi_piso', 'Detalle' => 'No puede ser NULO'];
                        }
    if (count($this->error) > 0) {$link->close(); return 1;}
    
                        $datos = [0 => ['tipo' => 'i', 'dato' => $this->gpi_gasto]
    ,1 => ['tipo' => 'i', 'dato' => $this->gpi_piso]
    ];
                        $query = "INSERT 
                                    INTO GASTO_PISO 
                                        (gpi_gasto,gpi_piso) 
                                 VALUES (?
    ,?
    )";
                        $link->ejecuta($query, $datos);
                        $this->error = $link->getListaErrores();
                        $satus = ($link->hayError()) ? 1 : 0;
                        
                        $this->array = $this->getDatos();
                        $link->close();
                        unset ($link);
    
                        return $satus;
                    }
    private function update()
            {
                $link = new ConexionSistema();
                if (!is_null($this->gpi_gasto)) {$key = $link->consulta('select count(0) as cuenta from GASTO where gas_gasto = \''.$this->gpi_gasto.'\'', []);
    if ($key[0]["cuenta"] < 1) {$this->error[] = ['tipo'=>'Validacion', 'Campo'=>'gpi_gasto', 'Detalle' => 'Referencia no encontrada en GASTO'];}
    }if (!is_null($this->gpi_piso)) {$key = $link->consulta('select count(0) as cuenta from PISO where pis_piso = \''.$this->gpi_piso.'\'', []);
    if ($key[0]["cuenta"] < 1) {$this->error[] = ['tipo'=>'Validacion', 'Campo'=>'gpi_piso', 'Detalle' => 'Referencia no encontrada en PISO'];}
    }
                
    if (count($this->error) > 0) {$link->close(); return 1;}
    
                $datos = [
                    
                    1000 => ['tipo' => 'i', 'dato' => $this->gpi_gasto]
    ,1001 => ['tipo' => 'i', 'dato' => $this->gpi_piso]
    
                ];
                $query = "UPDATE GASTO_PISO 
                             SET 
                           WHERE 1 = 1
                             and gpi_gasto = ?
    and gpi_piso = ?
    ";
                $link->ejecuta($query, $datos);
                $this->error = $link->getListaErrores();
                $satus = ($link->hayError()) ? 1 : 0;
                $this->array = $this->getDatos();
                $link->close();
                unset ($link);
    
                return $satus;
            }public function save($array)
            {
                $insert = true;
                $this->emptyClass();
                $this->clearArray();
                $this->clearError();
                $arrayUpdate = ['gpi_gasto' => $array['gpi_gasto']
    ,'gpi_piso' => $array['gpi_piso']
    ];
                if ($this->give($arrayUpdate) == 0) {
                    if (count($this->getArray()) == 1) { $this->setDatos($this->getArray()[0]); $insert = false; }
                } else {
                    return 1;
                }
                
                $this->setDatos($array);
                if ($insert) {
                    return $this->insert();
                } else {
                    return $this->update();
                }
            }
    public function delete($array)
            {
                $link = new ConexionSistema();
                $this->emptyClass();
                $this->clearError();
                $this->clearArray();
                $this->setDatos($array);
                $datosPK = [];
                if (is_null($this->gpi_gasto)) { 
                        $this->error[] = ['tipo'=>'Validacion', 'Campo'=>'gpi_gasto', 'Detalle' => 'No puede ser NULO'];
                    } else {
                        $datosPK['gpi_gasto'] = $this->gpi_gasto;
                    }if (is_null($this->gpi_piso)) { 
                        $this->error[] = ['tipo'=>'Validacion', 'Campo'=>'gpi_piso', 'Detalle' => 'No puede ser NULO'];
                    } else {
                        $datosPK['gpi_piso'] = $this->gpi_piso;
                    }
                if (count($this->error) > 0) {$link->close(); return 1;}
    
                $this->emptyClass();
                $this->clearError();
                $this->clearArray();
                if ($this->give($datosPK) != 0) return 1;
                $this->setDatos($this->getArray()[0]);
                
                if (count($this->error) > 0) {$link->close(); return 1;}
    
                $datos = [
                    0 => ['tipo' => 'i', 'dato' => $this->gpi_gasto]
    ,1 => ['tipo' => 'i', 'dato' => $this->gpi_piso]
    
                ];
                $query = 'delete from GASTO_PISO 
                           WHERE 1 = 1
                             and gpi_gasto = ?
    and gpi_piso = ?
    ';
                $link->ejecuta($query, $datos);
                $this->error = $link->getListaErrores();
                $satus = ($link->hayError()) ? 1 : 0;
                $this->array = $this->getDatos();
                $link->close();
                $this->clearArray();
                unset ($link);
    
                return $satus;
            }
    
            public function __construct() { $this->empty = $this->getDatos(); $this->clearError(); return 0; }
    }
?>