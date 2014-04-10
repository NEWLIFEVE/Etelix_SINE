
<?php
/**
 * @package reportes
 */
class InvoiceReport extends Reportes
{
    /**
     * 
     * @param type $fromDate
     * @param type $toDate
     * @param type $typeReport
     * @param type $paymentTerm
     * @param type $dividedInvoice
     * @param type $sum
     * @return string
     */
    public static function reporte($fromDate,$toDate,$typeReport,$paymentTerm=null,$dividedInvoice,$sum)
    {
        //Estilos
        $styleDescription="style='border:0px solid silver;text-align:left;background:#fff;color:#06ACFA;'";
        $styleNumberRow="style='border:1px solid silver;text-align:center;background:#83898F;color:white;'";
        $style_provisiones="style='border:1px solid silver;background:#E99241;text-align:center;color:white;'";
        $style_sori="style='border:1px solid silver;background:#3466B4;text-align:center;color:white;'";
        $style_diference="style='border:1px solid silver;background:#18B469;text-align:center;color:white;'";
        $style_totals="style='border:1px solid silver;background:silver;text-align:right;'";
        $style_date_null="style='background:white;text-align:center;color:silver'";
        $header="<tr>
                    <td {$styleNumberRow} ></td>
                    <td colspan='3'".$style_provisiones."><b>CAPTURA</b></td>
                    <td colspan='4'".$style_sori."><b>FACTURACION SORI</b></td>
                    <td colspan='3'".$style_diference."><b>DIFERENCIAS</b></td>
                    <td {$styleNumberRow} ></td>
                 </tr>
                 <tr>
                    <td {$styleNumberRow} >N°</td>
                    <td ".$style_provisiones.">OPERADOR</td>
                    <td ".$style_provisiones.">MINUTOS</td>
                    <td ".$style_provisiones.">MONTO $</td>
                    <td ".$style_sori.">OPERADOR</td>
                    <td ".$style_sori.">MINUTOS</td>
                    <td ".$style_sori.">MONTO</td>
                    <td ".$style_sori.">Num FACTURA</td>
                    <td ".$style_diference.">OPERADOR</td>
                    <td ".$style_diference.">MINUTOS</td>
                    <td ".$style_diference.">MONTO</td>
                    <td {$styleNumberRow} >N°</td>
                 </tr>";
        $acumulado_factura=$acumulado_provisiones=$acumulado_diference=$acumulado_factura_min=$acumulado_provisiones_min=$acumulado_diference_min=0;
        //Traigo las Provisiones de base de datos
        $documents=self::getModel($fromDate, $toDate,$typeReport,$paymentTerm,$dividedInvoice, NULL, NULL);
        $seg=count($documents)*3;
            ini_set('max_execution_time', $seg);
        $body="<table style='width: 100%;'>
                    <tr rowspan='2'>
                       <td colspan='12'></td>
                    </tr>
                    <tr>
                       <td colspan='12'><h1>{$typeReport}</h1>".str_replace("-","",$fromDate)." - ".str_replace("-","",$toDate)." al ".str_replace("-","",date('Y-m-d'))."</td>    
                    </tr>
                    <tr>
                       <td colspan='12'></td>
                    </tr>
                    <tr>
                       <td colspan='3'>TIPO DE FACTURACION</td>
                       <td {$styleDescription}colspan='3'>".Reportes::define_num_dias($fromDate,$toDate)."</td>
                       <td colspan='7'></td>
                    </tr>
                    <tr>
                       <td colspan='12'></td>
                    </tr>
                    <tr>
                       <td colspan='3'>PERIODO</td>
                       <td {$styleDescription}colspan='3'>".Utility::formatDateSINE($fromDate,"F j")." - ".Utility::formatDateSINE($toDate,"F j")."</td>
                       <td colspan='7'></td>
                    </tr>
                    <tr>
                       <td colspan='12'></td>
                    </tr>
                    {$header}";
        if($documents!=null)
        {
            foreach($documents as $key => $document)
            {
                $pos=$key+1;
                $style=self::style($document);

                $acumulado_factura+=$document->fac_amount;
                $acumulado_provisiones+=$document->amount;
                $acumulado_diference+=$document->monto_diference;
                $acumulado_factura_min+=$document->fac_minutes;
                $acumulado_provisiones_min+=$document->minutes;
                $acumulado_diference_min+=$document->min_diference;
                $body.="<tr>
                           <td {$styleNumberRow} >{$pos}</td>
                           <td style='border:1px solid silver;text-align:left;background:{$style}'>".$document->carrier."</td>
                           <td style='border:1px solid silver;text-align:right;background:{$style}'>".Yii::app()->format->format_decimal($document->minutes,3)."</td>
                           <td style='border:1px solid silver;text-align:right;background:{$style}'>".Yii::app()->format->format_decimal($document->amount,3)."</td>
                           <td style='border:1px solid silver;text-align:left;background:{$style}'>".$document->carrier."</td>
                           <td style='border:1px solid silver;text-align:right;background:{$style}'>".Yii::app()->format->format_decimal($document->fac_minutes,3)."</td>
                           <td style='border:1px solid silver;text-align:right;background:{$style}'>".Yii::app()->format->format_decimal($document->fac_amount,3)."</td>
                           <td style='border:1px solid silver;text-align:left;background:{$style}'>".$document->doc_number."</td>
                           <td style='border:1px solid silver;text-align:left;background:{$style}'>".$document->carrier."</td>
                           <td style='border:1px solid silver;text-align:right;background:{$style}'>".Yii::app()->format->format_decimal($document->min_diference,3)."</td>
                           <td style='border:1px solid silver;text-align:right;background:{$style}'>".Yii::app()->format->format_decimal($document->monto_diference,3)."</td>
                           <td {$styleNumberRow} >{$pos}</td>
                       </tr>";
            }
        }else{
            $body.="<tr>
                       <td {$style_date_null} colspan='12'>En este periodo no hay datos registrados</td>
                   </tr>";
        }

            $body.="<tr>
                       <td $style_provisiones colspan='2'><b>MINUTOS</b></td>
                       <td $style_totals colspan='2'>".Yii::app()->format->format_decimal($acumulado_provisiones_min,3)."</td>
                       <td $style_sori ><b>MINUTOS</b></td>
                       <td $style_totals colspan='3'>".Yii::app()->format->format_decimal($acumulado_factura_min,3)."</td>
                       <td $style_diference ><b>MINUTOS</b></td>
                       <td $style_totals colspan='3'>".Yii::app()->format->format_decimal($acumulado_diference_min,3)."</td>
                    </tr>
                    <tr>
                       <td $style_provisiones colspan='2'><b>MONTO $</b></td>
                       <td $style_totals colspan='2'>".Yii::app()->format->format_decimal($acumulado_provisiones,3)."</td>
                       <td $style_sori ><b>MONTO $</b></td>
                       <td $style_totals colspan='3'>".Yii::app()->format->format_decimal($acumulado_factura,3)."</td>
                       <td $style_diference ><b>MONTO $</b></td>
                       <td $style_totals colspan='3'>".Yii::app()->format->format_decimal($acumulado_diference,3)."</td>
                    </tr>";
         
        if($sum==TRUE)
        {
            $backPeriods=self::getBackPeriods($paymentTerm, $toDate, $typeReport);
            $segTwo=count($backPeriods)*30;
                ini_set('max_execution_time', $segTwo);
            if(count($backPeriods)>1)
                $body.="<tr>
                           <td colspan='12'><h2>SUMMARY {$typeReport}</h2></td>
                       </tr>";
            foreach ($backPeriods as $key => $periods) 
            {
                $pos=$key+1;
                if($pos>1)
                {
                    $summary=self::getModel($periods["from"], $periods["to"],$typeReport,$paymentTerm,$dividedInvoice, NULL, TRUE);
                    $body.="<tr>
                               <td colspan='12' style='padding-bottom: 20px;'> </td>
                            </tr>
                            <tr>
                               <td colspan='2'>PERIODO </td>
                               <td {$styleDescription} colspan='10'> ".Utility::formatDateSINE($periods["from"],"F j")." - ".Utility::formatDateSINE($periods["to"],"F j")."</td>
                            </tr>
                            {$header}";
                    if($summary!=null)
                    {
                        foreach($summary as $key => $detailSummary)
                        {
                            $pos=$key+1;
                            $style=self::style($detailSummary);
                            $body.="<tr>
                                       <td {$styleNumberRow} >{$pos}</td>
                                       <td style='border:1px solid silver;text-align:left;background:{$style}'>".$detailSummary->carrier."</td>
                                       <td style='border:1px solid silver;text-align:right;background:{$style}'>".Yii::app()->format->format_decimal($detailSummary->minutes,3)."</td>
                                       <td style='border:1px solid silver;text-align:right;background:{$style}'>".Yii::app()->format->format_decimal($detailSummary->amount,3)."</td>
                                       <td style='border:1px solid silver;text-align:left;background:{$style}'>".$detailSummary->carrier."</td>
                                       <td style='border:1px solid silver;text-align:right;background:{$style}'>".Yii::app()->format->format_decimal($detailSummary->fac_minutes,3)."</td>
                                       <td style='border:1px solid silver;text-align:right;background:{$style}'>".Yii::app()->format->format_decimal($detailSummary->fac_amount,3)."</td>
                                       <td style='border:1px solid silver;text-align:left;background:{$style}'>".$detailSummary->doc_number."</td>
                                       <td style='border:1px solid silver;text-align:left;background:{$style}'>".$detailSummary->carrier."</td>
                                       <td style='border:1px solid silver;text-align:right;background:{$style}'>".Yii::app()->format->format_decimal($detailSummary->min_diference,3)."</td>
                                       <td style='border:1px solid silver;text-align:right;background:{$style}'>".Yii::app()->format->format_decimal($detailSummary->monto_diference,3)."</td>
                                       <td {$styleNumberRow} >{$pos}</td>
                                   </tr>";
                        }
                    }else{
                        $body.="<tr>
                                   <td {$style_date_null} colspan='12'>En este periodo no hay diferencias de mas de (1 $) o provisiones sin facturas</td>
                               </tr>";
                    }
                    $totalSummary=self::getModel($periods["from"], $periods["to"],$typeReport,$paymentTerm,$dividedInvoice, TRUE, NULL);
                    $body.="<tr>
                               <td $style_provisiones colspan='2'><b>MINUTOS</b></td>
                               <td $style_totals colspan='2'>".Yii::app()->format->format_decimal($totalSummary->minutes,3)."</td>
                               <td $style_sori ><b>MINUTOS</b></td>
                               <td $style_totals colspan='3'>".Yii::app()->format->format_decimal($totalSummary->fac_minutes,3)."</td>
                               <td $style_diference ><b>MINUTOS</b></td>
                               <td $style_totals colspan='3'>".Yii::app()->format->format_decimal($totalSummary->min_diference,3)."</td>
                            </tr>
                            <tr>
                               <td $style_provisiones colspan='2'><b>MONTO $</b></td>
                               <td $style_totals colspan='2'>".Yii::app()->format->format_decimal($totalSummary->amount,3)."</td>
                               <td $style_sori ><b>MONTO $</b></td>
                               <td $style_totals colspan='3'>".Yii::app()->format->format_decimal($totalSummary->fac_amount,3)."</td>
                               <td $style_diference ><b>MONTO $</b></td>
                               <td $style_totals colspan='3'>".Yii::app()->format->format_decimal($totalSummary->monto_diference,3)."</td>
                           </tr>";
                }
            }
            $body.="</table>";
        }
        
        
        return $body;
    }

    /**
     * ejecuta la consulta para traer el trafico de minutos y monto por cada carrier que pase por el foreach
     * @param type $startDate
     * @param type $endDate
     * @param type $typeReport
     * @param int $paymentTerm
     * @param type $dividedInvoice
     * @param type $colls
     * @param type $summary
     * @return type
     */
    private static function getModel($startDate, $endDate, $typeReport, $paymentTerm=null, $dividedInvoice, $colls, $summary) 
    {
        $from=">=";
        $to="<=";
        $divided="";
        $summaryFilter="";
        if($summary!=NULL)  $summaryFilter="WHERE ABS(b.fac_amount-b.amount)>=1 OR doc_number IS NULL";
        
        if($typeReport=="REFAC")
        { 
            if($paymentTerm==null) 
                $paymentTerm=7;
            $provision="Provision Factura Enviada";
            $factura="Factura Enviada";
            $carriers="SELECT c.id
                       FROM carrier c, contrato con, contrato_termino_pago ctp, termino_pago tp
                       WHERE con.id_carrier=c.id AND con.end_date IS NULL AND ctp.id_contrato=con.id AND ctp.id_termino_pago=tp.id AND ctp.end_date IS NULL AND tp.period={$paymentTerm}";
        }
        else
        {
            if($dividedInvoice != "Si"){
                if(TerminoPago::getModelFind($paymentTerm)->period==7){
                    $divided=" AND ctps.month_break NOT IN(1)";
                    $from="=";
                    $to="=";
                }
            }
            $provision="Provision Factura Recibida";
            $factura="Factura Recibida";
            $carriers="SELECT c.id
                       FROM carrier c, contrato con, contrato_termino_pago_supplier ctps, termino_pago tp
                       WHERE con.id_carrier=c.id 
                         AND con.end_date IS NULL 
                         AND ctps.id_contrato=con.id 
                         AND ctps.id_termino_pago_supplier=tp.id 
                         AND ctps.end_date IS NULL 
                         AND tp.id={$paymentTerm}
                         {$divided}";
        }

        if($colls==NULL)
        {
            $sql="SELECT b.*, (b.fac_minutes-b.minutes) AS min_diference, (b.fac_amount-b.amount) AS monto_diference
                  FROM (SELECT c.name AS carrier, ad.minutes AS minutes, ad.amount AS amount,
                               (SELECT minutes
                                FROM accounting_document
                                WHERE id_type_accounting_document=(SELECT id FROM type_accounting_document WHERE  name='{$factura}') AND ad.from_date=from_date AND ad.to_date=to_date AND c.id=id_carrier) AS fac_minutes,
                               (SELECT amount
                                FROM accounting_document
                                WHERE id_type_accounting_document=(SELECT id FROM type_accounting_document WHERE  name='{$factura}') AND ad.from_date=from_date AND ad.to_date=to_date AND c.id=id_carrier) AS fac_amount,
                               (SELECT doc_number
                                FROM accounting_document
                                WHERE id_type_accounting_document=(SELECT id FROM type_accounting_document WHERE  name='{$factura}') AND ad.from_date=from_date AND ad.to_date=to_date AND c.id=id_carrier) AS doc_number
                        FROM carrier c, accounting_document ad
                        WHERE c.id IN({$carriers}) AND ad.id_carrier=c.id AND ad.from_date{$from}'{$startDate}' AND ad.to_date{$to}'{$endDate}' AND ad.id_type_accounting_document=(SELECT id FROM type_accounting_document WHERE  name='{$provision}')
                        ORDER BY c.name ASC) b $summaryFilter ";
            $return=AccountingDocument::model()->findAllBySql($sql); 
            
        }else{
                $sql="SELECT SUM(b.minutes) AS minutes,
                             SUM(b.amount) AS amount, 
                             SUM(b.fac_minutes) AS fac_minutes, 
                             SUM(b.fac_amount) AS fac_amount, 
                             SUM(b.fac_minutes-b.minutes) AS min_diference, 
                             SUM(b.fac_amount-b.amount) AS monto_diference,
                             '{$startDate}' AS from_date,
                             '{$endDate}' AS to_date
                  FROM (SELECT c.name AS carrier, ad.minutes AS minutes, ad.amount AS amount,
                               (SELECT minutes
                                FROM accounting_document
                                WHERE id_type_accounting_document=(SELECT id FROM type_accounting_document WHERE  name='{$factura}') AND ad.from_date=from_date AND ad.to_date=to_date AND c.id=id_carrier) AS fac_minutes,
                               (SELECT amount
                                FROM accounting_document
                                WHERE id_type_accounting_document=(SELECT id FROM type_accounting_document WHERE  name='{$factura}') AND ad.from_date=from_date AND ad.to_date=to_date AND c.id=id_carrier) AS fac_amount,
                               (SELECT doc_number
                                FROM accounting_document
                                WHERE id_type_accounting_document=(SELECT id FROM type_accounting_document WHERE  name='{$factura}') AND ad.from_date=from_date AND ad.to_date=to_date AND c.id=id_carrier) AS doc_number
                        FROM carrier c, accounting_document ad
                        WHERE c.id IN({$carriers}) AND ad.id_carrier=c.id AND ad.from_date{$from}'{$startDate}' AND ad.to_date{$to}'{$endDate}' AND ad.id_type_accounting_document=(SELECT id FROM type_accounting_document WHERE  name='{$provision}')) b";
                 
            
            $return=AccountingDocument::model()->findBySql($sql);    
        }
        
        return $return;     
    }
    public static function getBackPeriods($periodPaymentTerm, $date, $typeReport)
    {
        $period=NULL;
        if($typeReport=="REFAC") //obtengo el atributo periodo
            $period=$periodPaymentTerm;
        else
            $period=TerminoPago::getModelFind($periodPaymentTerm)->period;

       $key=0;//obtengo el array de los periodos pasados
       $array=array(); 
       $result=array(); 
       for($date; $date >= "2013-10-06"; $date = date('Y-m-d', strtotime('-1', strtotime(Reportes::defineFromDate($period,$date)))))
       {
            $array= array("from" => Reportes::defineFromDate($period,$date), "to" => $date);
            $result[$key]=$array; 
            $key++; 
       }
       return $result;
    }


            /**
     *
     */
    private static function style($document)
    {
        if($document->fac_amount==null) return '#FCC089';
        if($document->monto_diference>=1||$document->monto_diference<=-1) return '#FAE08D';
        return '#ffffff';
    }
}