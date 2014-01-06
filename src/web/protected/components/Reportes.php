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
     * @param type $no_disp
     * @param type $no_prov
     * @param type $grupoName
     * @return type
     */
    public function SOA($grupo,$fecha,$no_disp,$no_prov,$grupoName)
    {
        $var=SOA::reporte($grupo,$fecha,$no_disp,$no_prov,$grupoName);
        return $var;
    }
    /**
     * busca el reporte en componente "balance" hace la consulta y extrae los atributos necesarios para luego formar el html y enviarlo por correo y/o exportarlo a excel
     * @param type $grupo
     * @param type $fecha
     * @param type $no_disp
     * @param type $no_prov
     * @param type $grupoName
     * @return type
     */
    public function balance_report($grupo,$fecha,$no_disp,$no_prov,$grupoName)
    {
        $var=balance_report::reporte($grupo,$fecha,$no_disp,$no_prov,$grupoName);
        return $var;
    }
    /**
     * busca el reporte refac en componente "refac" trae html de tabla ya lista para ser aprovechado por la funcion mail y excel, 
     * este reporte tiene la particularidad mas fuer de que las consltas se hacen en base a facturas enviadas y captura de carriers costummers
     * @param type $fecha_from
     * @param type $fecha_to
     * @return type
     */
    public function refac($fecha_from,$fecha_to,$tipo_report)
    {
        $var=refac_refi_prov::reporte($fecha_from,$fecha_to,$tipo_report);
        return $var;
    }
    /**
     * busca el reporte refi_prov en componente "refi_prov" trae html de tabla ya lista para ser aprovechado por la funcion mail y excel, 
     * este reporte es casi igual que refac, con la particularidad de que en este caso busca facturas recibidas y en captura se filtra por medio de carrier suppliers
     * @param type $fecha_from
     * @param type $fecha_to
     * @return type
     */
    public function refi_prov($fecha_from,$fecha_to,$tipo_report)
    {
        $var=refac_refi_prov::reporte($fecha_from,$fecha_to,$tipo_report);
        return $var;
    }
    /**
     * esta funcion es usada para por ahora el SOA, y determina el sql complementario para llamar los datos de los grupos normalmente 
     * o en su caso especial, en cabinas peru, va a traer una serie de grupos pertenecientes a este...aun hay que meterle otras cosas a SOA para complementarlo
     * @param type $grupo
     * @return string
     */
    public static function define_grupo($grupo)
    {    
           if($grupo=="CABINAS PERU")  
               return "id_carrier_groups=301 OR id_carrier_groups=443";
           else   
               return "id_carrier_groups=".CarrierGroups::getID($grupo)."";
    }
    /**
     * define si la consulta traera las disputas o no
     * si es diferente de null, el sql es standar, es decir, traera las disputas, sino, entonces el sql no traera las disputas, 
     * puesto que le esta indicando la condicion de "NOT IN (5,6)"
     * @param type $no_disp
     * @return string
     */
    public static function define_disp($no_disp)
    {
        if($no_disp=="No")
           $disp_sql="and a.id_type_accounting_document NOT IN (5,6)";
        else  
           $disp_sql="";
        return $disp_sql;
    }
    /**
     * define si traera las disputas en los SOA
     * @param type $no_prov
     * @return string
     */
    public static function define_prov($no_prov)
    {
        if($no_prov=="No")
           $prov_sql="";//aqui debe ir el sql para filtrar las provisiones
        else  
           $prov_sql="";
        return $prov_sql;
    }
    /**
     * fucnion encargada de determinar el due_date apartir de termino pago y issue_date para ser usado en SOA, 
     * no obstante tambien es usado en refac y refi_prov, se usa en parte para determinar el from_date de estos 
     * ultimos reportes, en el caso standar de due_date para soa, esta funcion suma un dia, pero para el caso de
     *  refac y refi_prov, resta los dias dependiendo el num de dias que arroje el tp, ejecutado antes d ellegar a esta funcion
     * @param type $tp
     * @param type $fecha
     * @return type
     */
    public static function define_due_date($tp, $fecha, $signo)
    {
        $tpdia=$signo.$tp.' day';
        $due_date=date('Y-m-d', strtotime($tpdia, strtotime ($fecha))) ;
        return $due_date;
    }
    /**
     * define la descipcion en SOA
     * @param type $model
     * @return string
     */
    public static function define_description($model)
    {   
        $bf= substr($model->doc_number, 0, 2) ;  
        switch ($model->id_type_accounting_document){
            case "3":
                if($bf=="bf")
                    $description="BF - Etelix to ".$model->group;
                    else
                    $description="WT - Etelix to ".$model->group;
                break;
            case "4":
                if($bf=="bf")
                    $description="BF - ".$model->group." to Etelix";
                    else
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
     * la regla es que para pagos y cobros, no hay due date, por lo que se coloca el mismo issue_date, 
     * y por defecto para los demas es el dua_date determinado por ...::define_dua_date
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
     * define el estilo de los tr dependiendo del tipo de documento contable, por los momentos solo define el estilo de pagos-cobros, 
     * disputas-notas de credito, donde el primer grupo es background:silver y el segundo grupo es fuente color: red...
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
     * define el estilo de los td�s td donde se alojen totales en los reportes
     * @return string
     */
    public static function define_estilos_totals()
    {
        $estilos = " style='background:white;color:black;border:1px solid black;'";
        return $estilos;
    }
    /**
     * define a favor de quien esta el balance final en los SOA... la regla es que si el balance es negativo, esta a favor del operador,
     *  de lo contrario estara a favor de etelix
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
     * en este caso solo multiplica por -1 el acumulado de ser negativo para asi mostralo en el reporte, sino es negativo, normal, no hace nada
     * @param type $acumulado
     * @return type
     */
    public static function define_a_favor_monto($acumulado)
    {
        if($acumulado < 0)$acumulado=$acumulado*-1;
        return $acumulado;
    }
    /**
     * define las facturas enviadas en reporte SOA
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
    /**
     * define la moneda para facturas enviadas en SOA
     * @param type $model
     * @return string
     */
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
     * define facturas recibidas en SOA
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
    /**
     * define la moneda para facturas recibidas en SOA
     * @param type $model
     * @return string
     */
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
     * define pagos en SOA
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
    /**
     * define moneda en pagos para SOA
     * @param type $model
     * @return string
     */
    public static function define_currency_p($model)/*deprecated*/
    {
        if ($model->id_type_accounting_document==3){
            return $model->currency;
        }else{
            return "";
        }
    }
    /**
     * define cobros en SOA
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
    /**
     * define la moneda para cobros en SOA
     * @param type $model
     * @return string
     */
    public static function define_currency_c($model)/*deprecated*/
    {
        if ($model->id_type_accounting_document==4){
            return $model->currency;
        }else{
            return "";
        }
    }
    /**
     * define monto del balance en SOA, en si es la ultima columna del reporte, pero esta para ser desarrollada, necesita de las demas
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
     * define la fecha de inicio del reporte para refac y refi_prov
     * @param type $termino_pago
     * @param type $fecha_to
     * @return type
     */
    public static function define_fecha_from($termino_pago, $fecha_to)
    {
        $tp_name= TerminoPago::getName($termino_pago);
        $tp= self::define_tp($tp_name)["periodo"];
        return Reportes::define_due_date($tp, $fecha_to,"-");
    }
    /**
     * determina el numero de dias entre fechas, para asi definir si el periodo es diario, semanal,quincenal y mensual con el uso de define_periodo, por ahora solo para REFAC
     * @param type $fecha_first
     * @param type $fecha_last
     * @return type
     */
     public static function define_num_dias($fecha_first,$fecha_last)
     {
         $from_date =strtotime($fecha_first);
         $to_date =strtotime($fecha_last);
              $from = date("d",$from_date );
              $to = date("d",$to_date );
          $result_dias= $from - $to;
          $resultadoPeriodo=Reportes::define_periodo($result_dias);
        return $resultadoPeriodo;
     }
     /**
      * complementa a define_num_dias, primero pasa por esa para determinar el numero de dias, y en base a eso esta funcion determina el tipo de periodo
      * @param type $var
      * @return string
      */
     public static function define_periodo($var)
     {
         if($var<0) $var=$var*-1;
         
         if($var=="7"||$var=="23")  return "SEMANAL";
         
         if($var=="15"||$var=="14") return "QUINCENAL";
         
         if($var=="30"||$var=="1"||$var=="0")return "MENSUAL"; 
         
         if($var=="3"||$var=="27") return "3 DIAS";
         
         if($var=="5"||$var=="25") return "5 DIAS";
     }
     /**
      * define acumulado de totales de sori en refac y refi prov
      * @param type $model
      * @param type $acumulado_sori
      * @return type
      */
    public static function define_total_sori($model,$acumulado_sori)
    {
        return $acumulado_sori + $model->amount;
    }
    /**
     * define acumulado de totales de captura en refac y refi prov
     * @param type $model
     * @param type $acumulado_captura
     * @return type
     */
    public static function define_total_captura($model,$acumulado_captura)
    {
                return $acumulado_captura + $model->revenue;
    }
    /**
     * define acumulado de totales de diferencias en refac y refi prov
     * @param type $model
     * @param type $acumulado_diference
     * @return type
     */
    public static function define_total_diference($diferencia,$acumulado_diference)
    {
        return $acumulado_diference + $diferencia;
    }
    
    public static function define_tp($key)
    {
        $termino_pago=array("P-Semanales"=>array("periodo"=>7,"vencimiento"=>0),
                               "P-Mensuales"=>array("periodo"=>30,"vencimiento"=>0),
                               "7/3"=>array("periodo"=>7,"vencimiento"=>3),
                               "7/5"=>array("periodo"=>7,"vencimiento"=>5),
                               "7/7"=>array("periodo"=>7,"vencimiento"=>7),
                               "15/7"=>array("periodo"=>15,"vencimiento"=>7),
                               "15/15"=>array("periodo"=>15,"vencimiento"=>15),
                               "30/7"=>array("periodo"=>30,"vencimiento"=>7),
                               "30/30"=>array("periodo"=>30,"vencimiento"=>30),
                               "Sin estatus"=>array("periodo"=>7,"vencimiento"=>7)//hay que consultar como seria el periodo y los dias para pagar en el paso de terminos de pago sin status
                               );
        return $termino_pago[$key];
    }

    /** 
     * Retorna la cantidad de dias de un mes
     * @access protected
     * @static
     * @param date $fecha la fecha que se dira la cantidad de dias que tiene el mes
     * @return int 
     */
    public static function howManyDays($fecha=null)
    {
        if(strpos($fecha,'-'))
        {
            $arrayFecha=explode('-',$fecha);
        }
        if(is_callable('cal_days_in_month'))
        {
            return cal_days_in_month(CAL_GREGORIAN, $arrayFecha[1], $arrayFecha[0]);
        }
        else
        {
            return date('d',mktime(0,0,0,$arrayFecha[1]+1,0,$arrayFecha[0]));
        }
    }
}
?>
