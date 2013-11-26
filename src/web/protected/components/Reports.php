<?php
/**
 * @package components
 */
class Reports extends CApplicationComponent
{

    public function init() 
    {

    }

    public function prueba($grupo,$operador,$fecha,$si_prov,$No_prov)
    {
        $var='GRUPO '.$grupo.'<br>OPERADOR '.$operador.'<br>FECHA '.$fecha.'<br>SI '.$si_prov.'<br>NO'.$No_prov;
        return $var;
    }
    public function SOA($grupo)
    {
        $var=SOA::reporte($grupo);
        return $var;
    }
}
?>
