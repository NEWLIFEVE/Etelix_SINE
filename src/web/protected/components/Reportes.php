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
    public function SOA($grupo,$fecha,$Si_disp,$grupoName)
    {
        $var=SOA::reporte($grupo,$fecha,$Si_disp,$grupoName);
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
    public function balance($grupo,$fecha,$Si_disp,$grupoName)
    {
        $var=balance::reporte($grupo,$fecha,$Si_disp,$grupoName);
        return $var;
    }
    /**
     * define si la consulta traera las disputas o no
     * si es diferente de null, el sql es standar, es decir, traera las disputas, sino, entonces el sql no traera las disputas, puesto que le esta indicando la condicion de "NOT IN (5,6)"
     * @param type $Si_disp
     * @return string
     */
    public static function define_disp($Si_disp)
    {
        if($Si_disp!=NULL)
           $disp_sql="";
        else 
           $disp_sql="and a.id_type_accounting_document NOT IN (5,6)";
        return $disp_sql;
    }
    /**
     * fucnion encargada de determinar el due_date apartir de termino pago y issue_date
     * @param type $tp
     * @param type $fecha
     * @return type
     */
    public static function define_due_date($tp, $fecha)
    {
        $tpdia='+'.$tp.' day';
        $due_date=date('Y-m-d', strtotime($tpdia, strtotime ($fecha))) ;
        
        return $due_date;
    }
    public static function define_description($model)
    {
        switch ($model->id_type_accounting_document){
            case "3":
            case "4":
                $description="WT";
                break;
            case "9":
                $description="Saldo al ".Utility::formatDateSINE($model->issue_date,"Y");
                break;
            default:
                $description = $model->doc_number." (".Utility::formatDateSINE($model->from_date,"M-").Utility::formatDateSINE($model->from_date,"d-").Utility::formatDateSINE($model->to_date,"d").")";

        }
        return $description;
    }
        
    public static function define_fact_env($model)
    {
        if ($model->id_type_accounting_document==1){
            return $model->currency.$model->amount;
        }elseif($model->id_type_accounting_document==7){
            return $model->currency."-".$model->amount;
        }else{
            return "";
        }
    }
    
    public static function define_fact_rec($model)
    {
        if ($model->id_type_accounting_document==2 || $model->id_type_accounting_document==9){
            return $model->currency.$model->amount;
        }elseif($model->id_type_accounting_document==8){
            return $model->currency."-".$model->amount;
        }else{
            return "";
        }
    }
        
    public static function define_pagos($model)
    {
        if ($model->id_type_accounting_document==3){
            return $model->currency.$model->amount;
        }else{
            return "";
        }
    }
    
    public static function define_cobros($model)
    {
        if ($model->id_type_accounting_document==4){
            return $model->currency.$model->amount;
        }else{
            return "";
        }
    }
    public static function define_balance_amount($model,$acumulado)
    {
        switch ($model->id_type_accounting_document){
            case "9":
                return $model->amount;
                break;
            case "1":
                return $acumulado + $model->amount;
                break;
            case "2":
                return $acumulado - $model->amount;
                break;
            case "3":
                return $acumulado + $model->amount;
                break;
            case "4":
                return $acumulado - $model->amount;
            case "7":
                return $acumulado - $model->amount;
            case "8":
                return $acumulado + $model->amount;
                break;
            
        }
    }
    
    public static function define_dias_TP($termino_pago)
    {
        switch ($termino_pago) {
              case "P-Semanales":
              case "7/7":
              case "15/7":
              case "30/7":
                   $tp=7;
                  break;
              case "P-Mensuales":
              case "30/30":
                   $tp=30;
                  break;
              case "7/3":
                   $tp=3;
                  break;
              case "7/5":
              case "15/5":
                   $tp=5;
                  break;
              case "15/15":
                   $tp=15;
                  break;
        }return $tp;
    }
    
        public static function cabecera($etiquetas,$estilos)
    {
        $cabecera="<tr>";
        if(count($etiquetas)>1)
        {
            if(count($estilos)>1)
            {
                foreach($etiquetas as $key => $value)
                {
                    $cabecera.="<th style='".$estilos[$key]."'>".$value."</th>";
                }
            }
            else
            {
                foreach ($etiquetas as $key => $value)
                {
                    $cabecera.="<th style='".$estilos."'>".$value."</th>";
                }
            }
        }
        else
        {
            $cabecera.="<th style='".$estilos."'>".$etiquetas[0]."</th>";
        }
        $cabecera.="</tr>";
        return $cabecera;
    }
}
?>
