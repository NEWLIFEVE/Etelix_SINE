<?php
/**
 * @package components
 */
class Reportes extends CApplicationComponent
{
    public function init() 
    {

    }

    public function mail($grupo,$operador,$fecha,$si_prov,$No_prov)
    {
        $var=SOA::reporte('GRUPO '.$grupo.'<br>OPERADOR '.$operador.'<br>FECHA '.$fecha.'<br>SI '.$si_prov.'<br>NO'.$No_prov);
        return $var;
    }
    public function SOA($grupo)
    {
        $var=SOA::reporte($grupo);
        return $var;
    }
}
?>
