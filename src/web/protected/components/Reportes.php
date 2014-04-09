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
     * @param type $provision
     * @param type $grupoName
     * @return type
     */
    public function SOA($grupo,$fecha,$no_disp,$provision)
    {
        $var=SOA::reporte($grupo,$fecha,$no_disp,$provision);
        return $var;
    }
    /**
     * 
     * @param type $date
     * @param type $intercompany
     * @param type $no_activity
     * @param type $PaymentTerm
     * @return type
     */
    public function summary($date,$intercompany,$no_activity,$typePaymentTerm,$PaymentTerm)
    {
        $var=summary::report($date,$intercompany,$no_activity,$typePaymentTerm,$PaymentTerm);
        return $var;
    }
    /**
     * busca el reporte en componente "balance" hace la consulta y extrae los atributos necesarios para luego formar el html y enviarlo por correo y/o exportarlo a excel
     * @param type $grupo
     * @param type $fecha
     * @param type $no_disp
     * @param type $grupoName
     * @return type
     */
    public function balance_report($grupo,$fecha,$no_disp)
    {
        $var=balance_report::reporte($grupo,$fecha,$no_disp);
        return $var;
    }

    public function reteco($carActived,$typePaymentTerm,$paymentTerm)
    {
        $var=reteco::report($carActived,$typePaymentTerm,$paymentTerm);
        return $var;
    }
    

    /**
     * busca el reporte refac en componente "refac" trae html de tabla ya lista para ser aprovechado por la funcion mail y excel, 
     * este reporte tiene la particularidad mas fuer de que las consltas se hacen en base a facturas enviadas y captura de carriers costummers
     * @param type $fromDate
     * @param type $toDate
     * @param type $typeReport
     * @param type $periodPaymentTerm
     * @param type $sum
     * @return type
     */
    public function refac($fromDate,$toDate,$typeReport,$periodPaymentTerm,$sum)
    {
        $var=InvoiceReport::reporte($fromDate,$toDate,$typeReport,$periodPaymentTerm,NULL,$sum);
        return $var;
    }

    /**
     * busca el reporte refi_prov en componente "refi_prov" trae html de tabla ya lista para ser aprovechado por la funcion mail y excel, 
     * este reporte es casi igual que refac, con la particularidad de que en este caso busca facturas recibidas y en captura se filtra por medio de carrier suppliers
     * @param type $fromDate
     * @param type $toDate
     * @param type $typeReport
     * @param type $paymentTerm
     * @param type $dividedInvoice
     * @param type $sum
     * @return type
     */
    public function refi_prov($fromDate,$toDate,$typeReport,$paymentTerm,$dividedInvoice, $sum)
    {
        $var=InvoiceReport::reporte($fromDate,$toDate,$typeReport,$paymentTerm,$dividedInvoice,$sum);
        return $var;
    }

    /**
     * @param type $fecha_from
     * @param type $fecha_to
     * @param type $tipo_report
     * @return type   
     */
    public function recredi($date,$intercompany,$no_activity,$typePaymentTerm,$PaymentTerm)
    {
        $var=new Recredi;
        return $var->report($date,$intercompany,$no_activity,$typePaymentTerm,$PaymentTerm);
    }

    public function recopa($fecha,$filter_oper,$expired,$order)
    {
        $var=Recopa::reporte($fecha,$filter_oper,$expired,$order);
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
            return "id_carrier_groups IN(SELECT id FROM carrier_groups WHERE name IN('FULLREDPERU','R-ETELIX.COM PERU','CABINAS PERU'))";
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
    public static function define_disp($no_disp,$type_report,$group,$date)
    {   
        if($group!=null)$group=Reportes::define_grupo($group);
        switch ($type_report)
           {
            case "soa":
                  if($no_disp=="No"){
                     return " ";
                  }else{
                     return "UNION
                             SELECT a.id, issue_date, valid_received_date, doc_number, from_date, to_date, minutes, g.name AS group, CAST(NULL AS date) AS due_date, 
                                    amount, id_type_accounting_document,s.name AS currency, c.name AS carrier
                             FROM accounting_document a, type_accounting_document tad, currency s, carrier c, carrier_groups g
                             WHERE a.id_carrier IN(Select id from carrier where $group)
                                     AND a.id_type_accounting_document=tad.id AND a.id_carrier=c.id AND a.id_currency=s.id AND c.id_carrier_groups = g.id AND confirm != -1
                                     AND a.id_type_accounting_document IN (5,6) AND a.id_accounting_document NOT IN (SELECT id_accounting_document FROM accounting_document WHERE id_type_accounting_document IN (7,8) AND id_accounting_document IS NOT NULL)";
                  }
                break;
            case "balance": 
                 if($no_disp=="No") {
                    return " ";
                 }else{
                    return "UNION
                            SELECT a.id, minutes, a.issue_date,a.valid_received_date,a.id_type_accounting_document,g.name as group,c.name as carrier, tp.name as tp, t.name as type, a.from_date, a.to_date, a.doc_number, a.amount,s.name AS currency 
                            FROM accounting_document a, type_accounting_document t, carrier c, currency s, contrato x, contrato_termino_pago xtp, termino_pago tp, carrier_groups g
                            WHERE a.id_carrier IN(Select id from carrier where $group) AND a.id_type_accounting_document=t.id AND a.id_carrier=c.id AND a.id_currency=s.id AND a.id_carrier=x.id_carrier AND x.id=xtp.id_contrato AND xtp.id_termino_pago=tp.id and xtp.end_date IS NULL AND c.id_carrier_groups=g.id AND a.issue_date<='{$date}'
                                  AND a.id_type_accounting_document IN (5,6) AND a.id_accounting_document NOT IN (SELECT id_accounting_document FROM accounting_document WHERE id_type_accounting_document IN (7,8) AND id_accounting_document IS NOT NULL)";
                 }
                break;
            default:
                return "";
           }
    }

    /**
     * 
     * @param type $no_prov
     * @param type $group
     * @return type
     */
    public static function define_prov($no_prov,$group)
    {
        if($group!=null)$group=Reportes::define_grupo($group);
        if($no_prov=="No"){
            return"";
        } else{
            return "UNION
                    SELECT a.id, issue_date, valid_received_date, doc_number, from_date, to_date, minutes, g.name AS group,
                         CAST(NULL AS date) AS due_date, amount, id_type_accounting_document,s.name AS currency, c.name AS carrier
                    FROM accounting_document a, type_accounting_document tad, currency s, carrier c, carrier_groups g
                    WHERE a.id_carrier IN(Select id from carrier where {$group})
                        AND tad.name  IN('Provision Factura Enviada','Provision Factura Recibida') 
                        AND a.id_type_accounting_document=tad.id
                        AND a.id_carrier=c.id
                        AND a.id_currency=s.id
                        AND c.id_carrier_groups = g.id
                        AND a.id_accounting_document IS NULL";
        }
    }

    /**
     * 
     * @param type $day: la cantidad de dias para sumar o restar
     * @param type $fecha
     * @param type $signo
     * @return type
     */
    public static function sumRestDate($day, $date, $symbol)
    {
        $oper=$symbol.$day.' day';
        $result=date('Y-m-d', strtotime($oper, strtotime ($date))) ;
        return $result;
    }
    /**
     * define la descipcion en SOA
     * @param type $model
     * @return string
     */
    public static function define_description($model)
    {   
        switch ($model->id_type_accounting_document){
            case "3":
                    $description="WT - Etelix to ".$model->group;
                break;
            case "15":
                    $description="BF - Etelix to ".$model->group;
                break;
            case "4":
                    $description="WT - ".$model->group." to Etelix";
                break;
            case "14":
                    $description="BF - ".$model->group." to Etelix";
                break;
            case "9":
                $description="Balance - ".Utility::formatDateSINE($model->issue_date,"M-Y");
                break;
            case "2":
                $description =$model->carrier." # ". $model->doc_number." (".Utility::formatDateSINE($model->from_date,"M-").Utility::formatDateSINE($model->from_date,"d-").Utility::formatDateSINE($model->to_date,self::defineFormatPeriod($model)).")";
                break;
            case "1":
                $description =$model->carrier." - ". $model->doc_number." (".Utility::formatDateSINE($model->from_date,"M-").Utility::formatDateSINE($model->from_date,"d-").Utility::formatDateSINE($model->to_date,self::defineFormatPeriod($model)).")";
                break;
            case "7":case "8":   //hay que mejoprarlo, tengo la idea, pero mejor discutirlo antes
                $description = "NC - ". $model->doc_number." (".Utility::formatDateSINE($model->from_date,"M-").Utility::formatDateSINE($model->from_date,"d-").Utility::formatDateSINE($model->to_date," d").")";
                break;
            case "10":case "11":
                $description = $model->carrier." - PT  ". $model->doc_number." (".Utility::formatDateSINE($model->from_date,"M-").Utility::formatDateSINE($model->from_date,"d-").Utility::formatDateSINE($model->to_date," d").")";
                break;
            case "12":case "13":
                $description = $model->carrier." - PF  ".$model->doc_number." (".Utility::formatDateSINE($model->from_date,"M-").Utility::formatDateSINE($model->from_date,"d-").Utility::formatDateSINE($model->to_date," d").")";
                break;
            case "5":case "6":
                $description = "DISPUTE (".Utility::formatDateSINE($model->from_date,"M-").Utility::formatDateSINE($model->from_date,"d-").Utility::formatDateSINE($model->to_date," d").")";
                break;
            default:
                $description = $model->doc_number." (".Utility::formatDateSINE($model->from_date,"M-").Utility::formatDateSINE($model->from_date,"d-").Utility::formatDateSINE($model->to_date," d").")";
        }
        return $description;
    }
    public static function defineFormatPeriod($model)
    {
        if(Utility::formatDateSINE($model->from_date,"M-")==Utility::formatDateSINE($model->to_date,"M-"))
            return " d";
        else
            return "M-d";
    }

    /**
     * la regla es que para pagos y cobros, no hay due date, por lo que se coloca el mismo issue_date, 
     * y por defecto para los demas es el dua_date determinado por ...::define_dua_date
     * @param type $model
     * @return type
     */
    public static function define_to_date($model,$balanceDueDate)
    {
//        switch ($model->id_type_accounting_document){
//            case "3": case "4":case "9":case "10":case"11":case"12":case"13":case"14":case"15":
//                $to_date="";
//                break;
//            default:
//                $to_date = Utility::formatDateSINE($model->due_date,"d-M-y");
//        }
        //provisional...//
       
        if($balanceDueDate==NULL){
        switch ($model->id_type_accounting_document){
            case "3": case "4":case "9":case "10":case"11":case"12":case"13":case"14":case"15":
                $to_date="";
                break;
            default:
                $to_date = Utility::formatDateSINE($model->due_date,"d-M-y");
        }
        return $to_date;
        }else{
            switch ($model->id_type_accounting_document){
                case "3": case "4":case "9":case "10":case"11":case"12":case"13":case"14":case"15":
                    $to_date="";
                    break;
                default:
                    $to_date = Utility::formatDateSINE($balanceDueDate,"d-M-y");
            }
            return $to_date;
        }
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
                $estilos=" style='background:silver;color:black;border:1px solid silver;'";
                break;
            case "14":case "15":
                $estilos=" style='background:#E5EAF5;color:black;border:1px solid silver;'";
                break;
            case "5": case "6":
                $estilos=" style='background:white;color:red;border:1px solid silver;'";
                break;
            case "7": case "8":
                $estilos=" style='background:white;color:red;border:1px solid silver;'";
                break;
            
            case "10":case "12": 
                $estilos=" style='background:#5CC468;color:black;border:1px solid silver;'";
                break;
            case "11": case "13":
                $estilos=" style='background:#FCC089;color:black;border:1px solid silver;'";
                break;
            default:
                $estilos = " style='background:white;color:black;border:1px solid silver;'";
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
        $estilos = " style='background:white;color:black;border:1px solid silver;'";
        return $estilos;
    }

    /**
     * 
     * @param type $val
     * @param type $background
     * @return type
     */
    public static function estilos_basic($val,$background)
    {
        if($val>=1||$val<=-1){
            $style_basic="style='border:1px solid black;text-align:left;$background'";
           }else{
            $style_basic="style='border:1px solid black;text-align:left;'";
           }
        return $style_basic;
    }

    /**
     * 
     * @param type $val
     * @param type $background
     * @return string
     */
    public static function estilos_num($val,$background)
    {
        if($val>=1||$val<=-1){
            $style_basic_number="style='border:1px solid black;text-align:right;$background'";
           }else{
            $style_basic_number="style='border:1px solid black;text-align:right;'";
           }
        return $style_basic_number;
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
        if($model->id_type_accounting_document==1||$model->id_type_accounting_document==10||$model->id_type_accounting_document==12)
        {
            return Yii::app()->format->format_decimal($model->amount,3);
        }
        elseif($model->id_type_accounting_document==9 && $model->amount>=0)
        {
            return Yii::app()->format->format_decimal($model->amount,3);
        }
        elseif($model->id_type_accounting_document==5||$model->id_type_accounting_document==7)
        {
//            if($model->amount<0)
               return Yii::app()->format->format_decimal($model->amount,3);
//            else
//               return "-".Yii::app()->format->format_decimal($model->amount,3);
        }
        else
        {
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
        if($model->id_type_accounting_document==1)
        {
            return $model->currency;
        }
        elseif($model->id_type_accounting_document==7)
        {
            return "-".$model->currency;
        }
        else
        {
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
        if($model->id_type_accounting_document==2||$model->id_type_accounting_document==11||$model->id_type_accounting_document==13)
        {
            return Yii::app()->format->format_decimal($model->amount,3);
        }
        elseif($model->id_type_accounting_document==9 && $model->amount<0)
        {
            return Yii::app()->format->format_decimal(($model->amount)*-1,3);
        }
        elseif($model->id_type_accounting_document==6||$model->id_type_accounting_document==8)
        {
            return Yii::app()->format->format_decimal($model->amount,3);
        }
        else
        {
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
        if($model->id_type_accounting_document==2 || $model->id_type_accounting_document==9)
        {
            return $model->currency;
        }
        elseif($model->id_type_accounting_document==8)
        {
            return $model->currency."-";
        }
        else
        {
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
        if($model->id_type_accounting_document==3||$model->id_type_accounting_document==15)
        {
            return Yii::app()->format->format_decimal($model->amount,3);
        }
        else
        {
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
        if($model->id_type_accounting_document==3)
        {
            return $model->currency;
        }
        else
        {
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
        if($model->id_type_accounting_document==4||$model->id_type_accounting_document==14)
        {
            return Yii::app()->format->format_decimal($model->amount,3);
        }
        else
        {
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
        if($model->id_type_accounting_document==4)
        {
            return $model->currency;
        }
        else
        {
            return "";
        }
    }

    /**
     * define monto del balance en SOA, en si es la ultima columna del reporte, pero esta para ser desarrollada, necesita de las demas
     * 1 - Factura Enviada: + SOA
     * 2 - Factura Recibida: - SOA
     * 3 - Pago: + SOA
     * 4 - Cobro: - SOA
     * 5 - Disputa Recibida: - SOA
     * 6 - Disputa Enviada: + SOA
     * 7 - Nota de Credito Enviada: - SOA
     * 8 - Nota de Credito Recibida: + SOA
     * 9 - Saldo Inicial.
     * 10 - Provision de Trafico Enviada: + SOA
     * 11 - Provision de Trafico Recibida: - SOA
     * 12 - Provision de Factura Enviada: + SOA
     * 13 - Provision de Factura Recibida: - SOA
     * 14 - Bank Fee Cobro: - SOA
     * 15 - Bank Fee Pago: + SOA
     * @param type $model
     * @param type $acumulado
     * @return type
     */
    public static function define_balance_amount($model,$acumulado)
    {
        switch ($model->id_type_accounting_document)
        {
            case "9":
                return $acumulado + $model->amount;
                break;
            case "1":case "3":case "6":case "7":case"10":case "12":case "15":

                return $acumulado + $model->amount;
                break;
            case "2":case "4":case "5":case "8":case "11":case "13":case "14":
                return $acumulado - $model->amount;
                break;
            default:
                return $acumulado;
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
        switch($model->id_type_accounting_document)
        {        
            case "3":case "15":
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
            case "4":case "14":
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
        if($model->id_type_accounting_document==2||$model->id_type_accounting_document==11||$model->id_type_accounting_document==13)
        {
            return $acumuladoFacRec + $model->amount;
        }
        elseif($model->id_type_accounting_document==9 && $model->amount<0)
        {
            return $acumuladoFacRec + ($model->amount*-1);
        }
        elseif($model->id_type_accounting_document==8)
        {
            return $acumuladoFacRec + $model->amount;
        }
        else
        {
            return $acumuladoFacRec;
        } 
    }

    /**
     * Calcula total de facturas enviadas, para incluir en el total el saldo final, este debe ser positivo, y entonces el mismo seria sumado al total. en el caso de las notas de credito, estas se le restarian al total
     * @param type $model
     * @param type $acumuladoFacEnv
     * @return type
     */
    public static function define_total_fac_env($model,$acumuladoFacEnv)
    {
        if($model->id_type_accounting_document==1||$model->id_type_accounting_document==10||$model->id_type_accounting_document==12)
        {
            return $acumuladoFacEnv + $model->amount;
        }
        elseif($model->id_type_accounting_document==9 && $model->amount>0)
        {
            return $acumuladoFacEnv + $model->amount;
        }
        elseif($model->id_type_accounting_document==7)
        {
            return $acumuladoFacEnv - $model->amount;
        }
        else
        {
            return $acumuladoFacEnv;
        } 
    }
    /**
     * define la fecha de inicio del reporte para refac y refi_prov
     * @param type $termino_pago
     * @param type $toDate
     * @return type
     */
    public static function defineFromDate($tp, $toDate)
    {
        switch($tp)
        {
            case 7:
                return date('Y-m-d', strtotime('-6day', strtotime($toDate)));
                break;
            case 15:
                if(date("d", strtotime($toDate)) == 15)
                {
                    return DateManagement::getDayOne($toDate);
                }
                elseif($toDate == DateManagement::separatesDate($toDate)['year'] . '-' . DateManagement::separatesDate($toDate)['month'] . '-' . DateManagement::howManyDays($toDate))
                {
                    return DateManagement::separatesDate($toDate)['year'] . '-' . DateManagement::separatesDate($toDate)['month'] . '-16';
                }
                else
                {
                    return DateManagement::calculateDate('-15',$toDate);
                }
                break;
            case 30:
                return DateManagement::getDayOne($toDate);
                break;
            default:
                break;
        }
    }

    /**
     * determina el numero de dias entre fechas, para asi definir si el periodo es diario, semanal,quincenal y mensual con el uso de define_periodo, por ahora solo para REFAC
     * @param type $fecha_first
     * @param type $fecha_last
     * @return type
     */
    public static function define_num_dias($fecha_first,$fecha_last)
    {
        $from_date=strtotime($fecha_first);
        $to_date=strtotime($fecha_last);
        $from=date("d",$from_date);
        $to=date("d",$to_date);
        $result_dias=$from-$to;
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
         
        if($var=="4"||$var=="5"||$var=="6"||$var=="7"||$var=="23"||$var=="24"||$var=="25"||$var=="21")  return "SEMANAL";
         
        if($var=="16"||$var=="14"||$var=="15") return "QUINCENAL";
         
        if($var=="30"||$var=="1"||$var=="0"||$var=="31"||$var=="28"||$var=="29")return "MENSUAL"; 
    }

    /**
     * define acumulado de totales de sori en refac y refi prov
     * @param type $model
     * @param type $acumulado_facturas
     * @return type
     */
    public static function define_total_facturas($model,$acumulado_facturas)
    {
        return $acumulado_facturas + $model->fac_amount;
    }

    /**
     * define acumulado de totales de provisiones en refac y refi prov
     * @param type $model
     * @param type $acumulado_provisiones
     * @return type
     */
    public static function define_total_provisiones($model,$acumulado_provisiones)
    {
        return $acumulado_provisiones + $model->amount;
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

    /**
     * determina el numero de dias dependiendo del termino de pago. aplica para soa, refac y refi_prov
     * @param type $key
     * @return array
     */
    public static function define_tp($key)
    {
        $termino_pago=array(
            "P-Semanales"=>array("periodo"=>7,"vencimiento"=>0),
            "P-Quincenales"=>array("periodo"=>15,"vencimiento"=>0),
            "P-Mensuales"=>array("periodo"=>30,"vencimiento"=>0),
            "7/3"=>array("periodo"=>7,"vencimiento"=>3),
            "7/5"=>array("periodo"=>7,"vencimiento"=>5),
            "7/7"=>array("periodo"=>7,"vencimiento"=>7),
            "15/7"=>array("periodo"=>15,"vencimiento"=>7),
            "15/15"=>array("periodo"=>15,"vencimiento"=>15),
            "15/5"=>array("periodo"=>15,"vencimiento"=>5),
            "30/7"=>array("periodo"=>30,"vencimiento"=>7),
            "30/15"=>array("periodo"=>30,"vencimiento"=>15),
            "30/30"=>array("periodo"=>30,"vencimiento"=>30),
            "Sin estatus"=>array("periodo"=>7,"vencimiento"=>7)//hay que consultar como seria el periodo y los dias para pagar en el paso de terminos de pago sin status
            );

        return $termino_pago[$key];
    }
    
    /**
     * este metodo es usado para obtener la diferencia en minutos y montos en los reportes refac y reprov, para que no las muestre vacias cuando sean exactamente iguales
     * @param type $varA
     * @param type $varB
     * @return string
     */
    public static function diferenceInvoiceReport($varA,$varB)
    {
        if($varA!=$varB)
            return $varA-$varB;
        else
            return "0.00";
    }
    public static function defineMayor($model)
    { 
        if($model->valid_received_date!=null){
                if($model->issue_date >= $model->valid_received_date){
                   return $model->issue_date;
                }else{
                   return $model->valid_received_date;
                }
        }else{
            return $model->issue_date;
        }
    }
          /**
         * 
         * @param type $id
         * @return type
         */
        public static function getDueDate($id_group)
        {
            $sql="SELECT MAX(issue_date)as issue_date,MAX(valid_received_date)as valid_received_date FROM accounting_document  WHERE id_carrier IN(Select id from carrier where id_carrier_groups = {$id_group}) AND id_type_accounting_document IN(1,2)";
            $date= AccountingDocument::model()->findBySql($sql);
            return self::DueDate($date, $id_group);
        }
        public static function DueDate($date,$id_group)
        {
            
            if($date->issue_date !=null || $date->valid_received_date!=null){
               if($date->issue_date >= $date->valid_received_date){
                   return Reportes::sumRestDate( Reportes::define_tp(Contrato::getContratoTP($id_group,"1"))["vencimiento"],$date->issue_date,"+");
               }else{
                    if($date->valid_received_date!=null){
                       return Reportes::sumRestDate( Reportes::define_tp(Contrato::getContratoTP($id_group,"2"))["vencimiento"],$date->valid_received_date,"+");
                    }else{
                        return null;
                    }
               }
            }
        }
        /**
         * metodos generales para RETECO, RECREDI Y SUMMARY
         */
        
         /**   se usa para summary y reteco
         * DEFINE SI EL CARRIER ESTA ACTIVO O NO
         * @param type $var
         * @return string
         */
        public static function defineActive($var)
        {
            if($var!="16")
                return "";
            else
                return "x";
        }
        /**
         * DEVUELVE EL NOMBRE COMPLEMENTARIO PARA LOS REPORTES DEPENDIENDO EL TERMINO PAGO
         * @param type $PaymentTerm
         * @return string
         */
        public static function defineNameExtra($PaymentTerm,$relation)
        {
            if($PaymentTerm=="todos"){ 
                return "GENERAL";
            }else{
                if($relation===FALSE)
                   return "CUSTOMER ".TerminoPago::getModelFind($PaymentTerm)->name;

                if($relation===TRUE)
                   return "SUPPLIER ".TerminoPago::getModelFind($PaymentTerm)->name;
            }
        }
        /**
         *  metodos para SUMMARY
         */
        /**
         * METODO ENCARGADO DE DEFINIR LOS ACUMULADOS
         * @param type $value
         * @param type $dueDate
         * @param type $date
         * @param type $firstWeek
         * @param type $lastWeek
         * @param type $prev
         * @param type $acum
         * @return type
         */
        public static function defineAcums($value,$dueDate,$date, $firstWeek, $lastWeek, $prev, $acum)
        {
            if($firstWeek==NULL && $lastWeek==NULL){
                if($prev=="prev"){
                    if(DateManagement::firstOrLastDayWeek($dueDate,"first") < DateManagement::firstOrLastDayWeek($date,"first")){
                        return $acum+$value;
                    }else{
                        return $acum;
                    }
                }else{
                    if(DateManagement::firstOrLastDayWeek($dueDate,"first") == DateManagement::firstOrLastDayWeek($date,"first")){
                        return $acum+$value;
                    }else{
                        return $acum;
                    }
                }
            }else{
                if($dueDate>=$firstWeek && $dueDate<=$lastWeek){
                    return $acum+$value;
                }else{
                    return $acum;
                }
            }
        }
        /**
         * EN EL CASO DE THIS WEEK ENCUENTRA DATOS DE DUE Y NEXT Y SI ESTA EN EL RANFO DE FECHA LO UBICA EN THIS WEEK. ESTO ES DEBIDO A QUE ALGUNAS FACTURAS VENCEN EN THIS WEEK, PERO OTRAS YA ESTAN VENCIDAS
         * @param type $valueResult
         * @param type $valueNext
         * @param type $dueDateNext
         * @param type $date
         * @return string
         */    
        public static function defineValueThisNext($valueResult,$valueNext,$dueDateNext, $date)
        {
            if($valueResult==""||$valueResult==NULL){
                if(DateManagement::firstOrLastDayWeek($date, "first") == DateManagement::firstOrLastDayWeek($dueDateNext, "first")){
                    return $valueNext;
                }else{
                    return "";
                }
            }else{
                return $valueResult;
            }

        }
        /**
         * DEFINE LOS ACUMULADOS PARA THIS WEEK CON DUE Y NEXT
         * @param type $valueResult
         * @param type $valueNext
         * @param type $dueDateNext
         * @param type $date
         * @param type $acum
         * @return type
         */
        public static function defineAcumsThisWeek($valueResult,$valueNext,$dueDateNext, $date, $acum)
        {
            if($valueResult==$acum){
                if(DateManagement::firstOrLastDayWeek($date, "first") == DateManagement::firstOrLastDayWeek($dueDateNext, "first")){
                    return $valueNext+$acum;
                }else{
                    return $acum;
                }
            }else{
                return $valueResult;
            }

        }

        /**
         * METODO ENCARGADO DE DESVIAR LOS MONTOS DEPENDIENDO (SI ES NEGATIVO O POSITIVO) PARA EL CALCULO DE TOTALES EN SUMMARY
         * @param type $value
         * @param type $var
         * @return int
         */
        public static function defineLessOrHigher($value, $var)
        {
            if($var==FALSE){
                if($value<0)
                    return $value;
                else
                    return 0;
            }else{
                if($value>=0)
                    return $value;
                else
                    return 0;
            }
        }
 
        /**
         * METODO ENCARGADO DE POSICIONAR LOS MONTOS Y LOS DUE_DATE DEPENDIENDO DE LA SEMANA
         * @param type $value
         * @param type $dueDate
         * @param type $date
         * @param type $firstWeek
         * @param type $lastWeek
         * @param type $prev
         * @return string
         */
        public static function defineValueTD($value,$dueDate,$date, $firstWeek, $lastWeek, $prev)
        {
            if($firstWeek==NULL && $lastWeek==NULL){
                if($prev=="prev"){
                    if(DateManagement::firstOrLastDayWeek($dueDate,"first") < DateManagement::firstOrLastDayWeek($date,"first")){
                        return $value;
                    }else{
                        return "";
                    }
                }else{
                    if(DateManagement::firstOrLastDayWeek($dueDate,"first") == DateManagement::firstOrLastDayWeek($date,"first")){
                        return $value;
                    }else{
                        return "";
                    }
                }
            }else{
                if($dueDate>=$firstWeek && $dueDate<=$lastWeek){
                    return $value;
                }else{
                    return "";
                }
            }
        }
        /**
         * DEFINE ESTILOS PARA PAGOS Y COBROS DE UNA SEMANA DE ANTIGUEDAD
         * @param type $dateModel
         * @param type $date
         * @return string
         */
        public static function defineStyleOld($dateModel,$date)
        {
            if(DateManagement::firstOrLastDayWeek($dateModel,"first") == DateManagement::firstOrLastDayWeek(DateManagement::calculateWeek("-1", $date),"first"))
                return "#FCD746";
            else
                return "#fff";
        }
        /**
         * DEFINE COLOR Y SIGNO PARA DISTINGUIR PAGOS Y COBROS
         * @param type $model
         * @param type $attr
         * @return string
         */
        public static function definePaymCollect($model,$attr)
        {
            if($attr=="value"){
                if($model->type_c_p=="Pago")
                    return "-".$model->last_pago_cobro;
                else 
                    return $model->last_pago_cobro;
            }else{
                if($model->type_c_p=="Pago")
                     return "red";
                 else 
                     return "#6F7074";
            }
        }
        /**
         * fin metodos SUMMARY
         */
        /**
         * RETECO
         */
        /**
         * SE ENCARGA DE DEFINIR ESTILOS PARA RETECO
         * @param type $var
         * @return string
         */
        public static function defineStyleNeed($var)
        {
            if($var==NULL)
                return "style='background:#E99241;color:white;border:1px solid silver;text-align:left;'";
            else 
                return "style='background:white;color:black;border:1px solid silver;text-align:left;'";
        }
        /**
         * fin RETECO
         */
}
?>
