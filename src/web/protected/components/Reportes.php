<?php
/**
 * @package components
 */
class Reportes extends CApplicationComponent
{
    public function init() 
    {
    }
    /**
     * busca el reporte en componente "SOA" hace la consulta y extrae los atributos necesarios para luego formar el html y enviarlo por correo y/o exportarlo a excel
     * @param type $grupo
     * @param type $fecha
     * @param type $Si_prov
     * @param type $Si_disp
     * @return type
     */
    public function SOA($grupo,$fecha,$Si_prov,$Si_disp)
    {
        $var=SOA::reporte($grupo,$fecha,$Si_disp,$Si_prov);
        return $var;
    }
    /**
     * busca el reporte en componente "balance" hace la consulta y extrae los atributos necesarios para luego formar el html y enviarlo por correo y/o exportarlo a excel
     * @param type $grupo
     * @param type $fecha
     * @param type $Si_prov
     * @param type $Si_disp
     * @return type
     */
    public function balance($grupo,$fecha,$Si_prov,$Si_disp)
    {
        $var=balance::reporte($grupo,$fecha,$Si_prov,$Si_disp);
        return $var;
    }
}
?>
