<?php

class Cache
{
    public static function ruta($url)
    {
        require_once 'conex/conf.php';  //información crítica del sistema
        $timestamp = '';
        $conf = new ConfiguracionSistema();
        $pa = $conf->getHome().$url;
        unset($conf);
        $pa .= "$url";
        //die $pa;
        if (file_exists($pa)) {
            $timestamp = filectime($pa);
        }

        return "$url?$timestamp";
    }
}

class Fecha
{
    public static function formatea($fecha, $output)
    {
        $dt = new DateTime($fecha);

        return $dt->format($output);
    }

    public static function nombreMesCorto ($mes) {
        $array = [
            '01' => 'Ene',
            '02' => 'Feb',
            '03' => 'Mar',
            '04' => 'Abr',
            '05' => 'May',
            '06' => 'Jun',
            '07' => 'Jul',
            '08' => 'Ago',
            '09' => 'Sep',
            '10' => 'Oct',
            '11' => 'Nov',
            '12' => 'Dic'
        ];
        return $array[$mes];
    } 
}

?>