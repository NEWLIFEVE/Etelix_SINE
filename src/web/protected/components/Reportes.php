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
     * @param type $no_disp
     * @return type
     */
    public function SOA($grupo,$fecha,$no_disp,$grupoName)
    {
        $var=SOA::reporte($grupo,$fecha,$no_disp,$grupoName);
        return $var;
    }
    /**
     * busca el reporte en componente "balance" hace la consulta y extrae los atributos necesarios para luego formar el html y enviarlo por correo y/o exportarlo a excel
     * @param type $grupo
     * @param type $fecha
     * @param type $Si_prov
     * @param type $no_disp
     * @return type
     */
    public function balance($grupo,$fecha,$no_disp,$grupoName)
    {
        $var=balance::reporte($grupo,$fecha,$no_disp,$grupoName);
        return $var;
    }
    /**
     * define si la consulta traera las disputas o no
     * si es diferente de null, el sql es standar, es decir, traera las disputas, sino, entonces el sql no traera las disputas, puesto que le esta indicando la condicion de "NOT IN (5,6)"
     * @param type $no_disp
     * @return string
     */
    public static function define_disp($no_disp)
    {
        if($no_disp=="0")
           $disp_sql="and a.id_type_accounting_document NOT IN (5,6)";
        else  
           $disp_sql="";
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
    /**
     * 
     * @param type $model
     * @return string
     */
    public static function define_description($model)
    {
        switch ($model->id_type_accounting_document){
            case "3":
                $description="WT - Etelix to ".$model->group;
                break;
            case "4":
                $description="WT - ".$model->group." to Etelix";
                break;
            case "9":
                $description="Balance - ".Utility::formatDateSINE($model->issue_date,"M-Y");
                break;
            case "2":
                $description =" #". $model->doc_number." (".Utility::formatDateSINE($model->from_date,"M-").Utility::formatDateSINE($model->from_date,"d-").Utility::formatDateSINE($model->to_date," d").")";
                break;
            case "1":
                $description =$model->carrier ." - ". $model->doc_number." (".Utility::formatDateSINE($model->from_date,"M-").Utility::formatDateSINE($model->from_date,"d-").Utility::formatDateSINE($model->to_date," d").")";
                break;
            default:
                $description = $model->doc_number." (".Utility::formatDateSINE($model->from_date,"M-").Utility::formatDateSINE($model->from_date,"d-").Utility::formatDateSINE($model->to_date," d").")";
        }
        return $description;
    }
    /**
     * la regla es que para pagos y cobros, no hay due date, por lo que se coloca el mismo issue_date, y por defecto para los demas es el dua_date determinado por ...::define_dua_date
     * @param type $model
     * @param type $due_date
     * @return type
     */
    public static function define_to_date($model,$due_date)
    {
        switch ($model->id_type_accounting_document){
            case "3": case "4":case "9":
                $to_date="";
                break;
            default:
                $to_date = Utility::formatDateSINE($due_date,"d-M-y");
        }
        return $to_date;
    }
    /**
     * define el estilo de los tr dependiendo del tipo de documento contable, por los momentos solo define el estilo de pagos-cobros, disputas-notas de credito, donde el primer grupo es background:silver y el segundo grupo es fuente color: red...
     * @param type $model
     * @return string
     */
    public static function define_estilos($model)
    {
        switch ($model->id_type_accounting_document){
            case "3": case "4":
                $estilos=" style='background:silver;color:black;border:1px solid black;'";
                break;
            case "5": case "6":
                $estilos=" style='background:white;color:red;border:1px solid black;'";
                break;
            case "7": case "8":
//                $estilos=" style='background:white;color:blue;border:1px solid black;'";
                $estilos=" style='background:white;color:red;border:1px solid black;'";
                break;
            default:
                $estilos = " style='background:white;color:black;border:1px solid black;'";
        }
        return $estilos;
    }
    /**
     * deja los bordes en cero y el fonde en blaco para los tr  de las tabla, por ejemplo en el tr de totales, para que solo resalten los td donde hay informacion
     * @return string
     */
    public static function define_estilos_null()
    {
        $estilos = " style='background:white;color:black;border:1px solid white;'";
        return $estilos;
    }
    /**
     * define el estilo de los td´s td donde se alojen totales en los reportes
     * @return string
     */
    public static function define_estilos_totals()
    {
        $estilos = " style='background:white;color:black;border:1px solid black;'";
        return $estilos;
    }
    /**
     * define a favor de quien esta el balance final en los SOA... la regla es que si el balance es negativo, esta a favor del operador, de lo contrario estara a favor de etelix
     * @param type $model
     * @param type $acumulado
     * @return string
     */
    public static function define_a_favor($model,$acumulado)
    {
        if($acumulado < 0)$afavor="Balance in favor of ".$model->group;
        else $afavor="Balance in favor of Etelix";
        return $afavor;
    }
    /**
     * 
     * @param type $model
     * @return string
     */  
    public static function define_fact_env($model)
    {
        if ($model->id_type_accounting_document==1){
            return Yii::app()->format->format_decimal($model->amount,3);
        }elseif($model->id_type_accounting_document==9 && $model->amount>=0){
            return Yii::app()->format->format_decimal($model->amount,3);
        }elseif($model->id_type_accounting_document==7){
            return "-".Yii::app()->format->format_decimal($model->amount,3);
        }else{
            return "";
        }
    }
    public static function define_currency_fe($model)/*deprecated*/
    {
        if ($model->id_type_accounting_document==1){
            return $model->currency;
        }elseif($model->id_type_accounting_document==7){
            return "-".$model->currency;
        }else{
            return "";
        }
    }
    /**
     * 
     * @param type $model
     * @return string
     */
    public static function define_fact_rec($model)
    {
        if ($model->id_type_accounting_document==2){
            return Yii::app()->format->format_decimal($model->amount,3);
        }elseif($model->id_type_accounting_document==9 && $model->amount<0){
            return Yii::app()->format->format_decimal(($model->amount)*-1,3);
        }elseif($model->id_type_accounting_document==8){
            return Yii::app()->format->format_decimal($model->amount,3);
        }else{
            return "";
        }
    }
    public static function define_currency_fr($model)/*deprecated*/
    {
        if ($model->id_type_accounting_document==2 || $model->id_type_accounting_document==9){
            return $model->currency;
        }elseif($model->id_type_accounting_document==8){
            return $model->currency."-";
        }else{
            return "";
        }
    }
    /**
     * 
     * @param type $model
     * @return string
     */    
    public static function define_pagos($model)
    {
        if ($model->id_type_accounting_document==3){
            return Yii::app()->format->format_decimal($model->amount,3);
        }else{
            return "";
        }
    }
    public static function define_currency_p($model)/*deprecated*/
    {
        if ($model->id_type_accounting_document==3){
            return $model->currency;
        }else{
            return "";
        }
    }
    /**
     * 
     * @param type $model
     * @return string
     */
    public static function define_cobros($model)
    {
        if ($model->id_type_accounting_document==4){
            return Yii::app()->format->format_decimal($model->amount,3);
        }else{
            return "";
        }
    }
    public static function define_currency_c($model)/*deprecated*/
    {
        if ($model->id_type_accounting_document==4){
            return $model->currency;
        }else{
            return "";
        }
    }
    /**
     * 
     * @param type $model
     * @param type $acumulado
     * @return type
     */
    public static function define_balance_amount($model,$acumulado)
    {
        switch ($model->id_type_accounting_document){
            case "9":
                return $model->amount;
                break;
            case "1":case "3":case "8":
                return $acumulado + $model->amount;
                break;
            case "2":case "4":case "7":
                return $acumulado - $model->amount;
                break;
        }
    }
    /**
     * determina el total de pagos, por ahora solo tiene esa funcion 
     * @param type $model
     * @param type $acumuladoPago
     * @return type
     */
    public static function define_total_pago($model,$acumuladoPago)
    {
        switch ($model->id_type_accounting_document){        
            case "3":
                return $acumuladoPago + $model->amount;
                break;
            default:
                return $acumuladoPago;
                break;
        }
    }
    /**
     * determina el total de cobros, por ahora solo tiene esa funcion
     * @param type $model
     * @param type $acumuladoCobro
     * @return type
     */
    public static function define_total_cobro($model,$acumuladoCobro)
    {
        switch ($model->id_type_accounting_document){        
            case "4":
                return $acumuladoCobro + $model->amount;
                break;
            default:
                return $acumuladoCobro;
                break;
        }
    }
    /**
     * Calcula total de facturas recibidas, para incluir en el total el saldo final, este debe ser negativo, y entonces el mismo seria restado para ello se multiplica por -1 antes de hacer la operacion, en si, el saldo se le descuenta al total. en el caso de las notas de credito, estas se le sumaran al total
     * @param type $model
     * @param type $acumuladoFacRec
     * @return type
     */
    public static function define_total_fac_rec($model,$acumuladoFacRec)
    {
        if ($model->id_type_accounting_document==2){
                return $acumuladoFacRec + $model->amount; }
            elseif($model->id_type_accounting_document==9 && $model->amount<0){
                return $acumuladoFacRec - ($model->amount*-1); }
            elseif($model->id_type_accounting_document==8) {
                return $acumuladoFacRec + $model->amount;}
            else{return $acumuladoFacRec;} 
    }
    /**
     * Calcula total de facturas enviadas, para incluir en el total el saldo final, este debe ser positivo, y entonces el mismo seria sumado al total. en el caso de las notas de credito, estas se le restarian al total
     * @param type $model
     * @param type $acumuladoFacEnv
     * @return type
     */
    public static function define_total_fac_env($model,$acumuladoFacEnv)
    {
        if ($model->id_type_accounting_document==1){
                return $acumuladoFacEnv + $model->amount; }
            elseif($model->id_type_accounting_document==9 && $model->amount>0){
                return $acumuladoFacEnv + $model->amount; }
            elseif($model->id_type_accounting_document==7) {
                return $acumuladoFacEnv - $model->amount;}
            else{return $acumuladoFacEnv;} 
    }
    /**
     * 
     * @param type $termino_pago
     * @return int
     */
    public static function define_dias_TP($termino_pago)
    {
        switch ($termino_pago) {
              case "P-Semanales": case "7/7": case "15/7": case "30/7":
                   $tp=7;
                  break;
              case "P-Mensuales": case "30/30":
                   $tp=30;
                  break;
              case "7/3":
                   $tp=3;
                  break;
              case "7/5": case "15/5":
                   $tp=5;
                  break;
              case "15/15":
                   $tp=15;
                  break;
        }return $tp;
    }
    /**
     * 
     * @param type $etiquetas
     * @param type $estilos
     * @return string
     */
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
