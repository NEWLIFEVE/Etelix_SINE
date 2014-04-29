
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
    public static function reporte($fromDate,$toDate,$typeReport,$paymentTerm,$dividedInvoice,$sum)
    {
        /*********************                                  ESTILOS BASICOS                                **********************/
        $styleDescription="style='border:0px solid white;text-align:left;background:#fff;color:#06ACFA;'";
        $styleNumberRow=$styleTotalTotal="style='border:1px solid silver;text-align:center;background:#83898F;color:white;'";
        $styleProvisions="style='border:1px solid silver;background:#E99241;text-align:center;color:white;'";
        $styleSori="style='border:1px solid silver;background:#3466B4;text-align:center;color:white;'";
        $styleDiff="style='border:1px solid silver;background:#18B469;text-align:center;color:white;'";
        $styleSubTotal="style='border:1px solid #D7D8E4;text-align:center;background:silver;color:white;'";
        $styleDateNull="style='background:white;text-align:center;color:silver;'";
        $styleNoInvoice="style='background:#D1BFEC;text-align:center;color:white;border:1px solid #D7D8E4;'";
        $styleDifhigher="style='background:#FAE08D;text-align:center;color:silver;border:1px solid silver;'";
        /*********************                       INICIALIZACION DE VARIABLES NUMERICAS                     **********************/
        $acumFactura= $acumProvisions= $acumDiference= $acumInvoiceMin= $acumProvisionsMin= $acumDiferenceMin= $acumNoInvoiceDiffAmount= $acumNoInvoiceDiffMin=
        $acumMinHigher= $acumAmountHigher= $acumMinInvoiceHigher= $acumAmountInvoiceHigher= $acumDifMinHigher= $acumDifAmountHigher= $acumNoInvoiceDiffAmountPrev= $acumNoInvoiceDiffMinPrev=0;
        /*********************                       INICIALIZACION DE HEADER PRINCIPAL                        **********************/
        $header="<tr>
                    <td {$styleNumberRow} ></td>
                    <td colspan='3'".$styleProvisions."><b>CAPTURA</b></td>
                    <td colspan='4'".$styleSori."><b>FACTURACION SORI</b></td>
                    <td colspan='3'".$styleDiff."><b>DIFERENCIAS</b></td>
                    <td {$styleNumberRow} ></td>
                 </tr>
                 <tr>
                    <td {$styleNumberRow} >N°</td>
                    <td ".$styleProvisions.">OPERADOR</td>
                    <td ".$styleProvisions.">MINUTOS</td>
                    <td ".$styleProvisions.">MONTO</td>
                    <td ".$styleSori.">OPERADOR</td>
                    <td ".$styleSori.">MINUTOS</td>
                    <td ".$styleSori.">MONTO</td>
                    <td ".$styleSori.">Num FACTURA</td>
                    <td ".$styleDiff.">OPERADOR</td>
                    <td ".$styleDiff.">MINUTOS</td>
                    <td ".$styleDiff.">MONTO</td>
                    <td {$styleNumberRow} >N°</td>
                 </tr>";
        
        /*********************      EXTRACCION DE PROVISIONES, FACTURAS Y DIFERENCIAS DESDE BASE DE DATOS     **********************/
        $documents=self::getModel($fromDate, $toDate,$typeReport,$paymentTerm,$dividedInvoice, NULL, NULL);
        
        $seg=count($documents)*3;
            ini_set('max_execution_time', $seg);
        /*********************                      NOMBRE COMPLEMENTARIO PARA LOS PERIODOS                   **********************/ 
        
        if($dividedInvoice!=NULL) 
            $complementName="(". TerminoPago::getModelFind($paymentTerm)->name. ")";
        ELSE
            $complementName=Reportes::define_num_dias($fromDate,$toDate);
        
        /*********************                                   INICIO DE LA TABLA                           **********************/ 
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
                       <td {$styleDescription} colspan='3'> {$complementName} </td>
                       <td colspan='6'></td>
                    </tr>
                    <tr>
                       <td colspan='12'></td>
                    </tr>
                    <tr>
                       <td colspan='3'>PERIODO</td>
                       <td {$styleDescription} colspan='3'>".Utility::formatDateSINE($fromDate,"F j")." - ".Utility::formatDateSINE($toDate,"F j")."</td>
                       <td colspan='6'></td>
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
            /*********************                               ACUMULADOS PRINCIPALES                        **********************/     
                $acumProvisions+=$document->amount;
                $acumProvisionsMin+=$document->minutes;
                $acumFactura+=$document->fac_amount;
                $acumInvoiceMin+=$document->fac_minutes;
                $acumDiference+=$document->monto_diference;
                $acumDiferenceMin+=$document->min_diference;
            /*********************                      ACUMULADOS DE PROVISIONES SIN FACTURAS                 **********************/     
                if($document->fac_amount==NULL){
                    $acumNoInvoiceDiffAmount+=$document->amount;
                    $acumNoInvoiceDiffMin+=$document->minutes;
                }else{
            /*********************                  ACUMULADOS DE DIFERENCIAS MAYORES A (1 $)                  **********************/ 
                    if( $document->monto_diference>=1||$document->monto_diference<=-1 ){
                        $acumMinHigher+=$document->minutes;
                        $acumAmountHigher+=$document->amount;
                        $acumMinInvoiceHigher+=$document->fac_minutes;
                        $acumAmountInvoiceHigher+=$document->fac_amount;
                        $acumDifMinHigher+=$document->min_diference;
                        $acumDifAmountHigher+=$document->monto_diference;
                    }
                }
            /*********************                             LLENA LA DATA PRINCIPAL                         **********************/     
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
                       <td {$styleDateNull} colspan='12'>En este periodo no hay datos registrados</td>
                   </tr>";
        }
        /*********************                                 TOTALES PRINCIPALES                              **********************/ 
        $body.="<tr>
                   <td {$styleTotalTotal} colspan='12'>TOTALES</td>
               </tr>
               <tr>
                   <td {$styleProvisions} colspan='2'>MINUTOS</td>
                   <td {$styleProvisions}  colspan='2'> MONTO $ </td>
                   <td {$styleSori} colspan='2'> MINUTOS </td>
                   <td {$styleSori} colspan='2'> MONTO $ </td>
                   <td {$styleDiff} colspan='2'> MINUTOS </td>
                   <td {$styleDiff} colspan='2'> MONTO $ </td>
               </tr>
               <tr>
                   <td {$styleDifhigher} colspan='2'>".Yii::app()->format->format_decimal($acumMinHigher,3)."</td>
                   <td {$styleDifhigher} colspan='2'>".Yii::app()->format->format_decimal($acumAmountHigher,3)."</td>
                   <td {$styleDifhigher} colspan='2'>".Yii::app()->format->format_decimal($acumMinInvoiceHigher,3)."</td>
                   <td {$styleDifhigher} colspan='2'>".Yii::app()->format->format_decimal($acumAmountInvoiceHigher,3)."</td>
                   <td {$styleDifhigher} colspan='2'>".Yii::app()->format->format_decimal($acumDifMinHigher,3)."</td>
                   <td {$styleDifhigher} colspan='2'>".Yii::app()->format->format_decimal($acumDifAmountHigher,3)."</td>
               </tr> 
               <tr>
                   <td {$styleNoInvoice} colspan='2'>".Yii::app()->format->format_decimal($acumNoInvoiceDiffMin,3)."</td>
                   <td {$styleNoInvoice} colspan='2'>".Yii::app()->format->format_decimal($acumNoInvoiceDiffAmount,3)."</td>
                   <td {$styleNoInvoice} colspan='2'> 0,00 </td>
                   <td {$styleNoInvoice} colspan='2'> 0,00 </td>
                   <td {$styleNoInvoice} colspan='2'>".Yii::app()->format->format_decimal($acumNoInvoiceDiffMin,3)."</td>
                   <td {$styleNoInvoice} colspan='2'>".Yii::app()->format->format_decimal($acumNoInvoiceDiffAmount,3)."</td>
               </tr> 
               <tr>
                   <td {$styleSubTotal} colspan='2'>".Yii::app()->format->format_decimal($acumMinHigher + $acumNoInvoiceDiffMin,3)."</td>
                   <td {$styleSubTotal} colspan='2'>".Yii::app()->format->format_decimal($acumAmountHigher + $acumNoInvoiceDiffAmount,3)."</td>
                   <td {$styleSubTotal} colspan='2'>".Yii::app()->format->format_decimal($acumMinInvoiceHigher,3)."</td>
                   <td {$styleSubTotal} colspan='2'>".Yii::app()->format->format_decimal($acumAmountInvoiceHigher,3)."</td>
                   <td {$styleSubTotal} colspan='2'>".Yii::app()->format->format_decimal($acumDifMinHigher + $acumNoInvoiceDiffMin,3)."</td>
                   <td {$styleSubTotal} colspan='2'>".Yii::app()->format->format_decimal($acumDifAmountHigher + $acumNoInvoiceDiffAmount,3)."</td>
               </tr> 
               <tr>
                   <td {$styleTotalTotal} colspan='2'>".Yii::app()->format->format_decimal($acumProvisionsMin,3)."</td>
                   <td {$styleTotalTotal} colspan='2'>".Yii::app()->format->format_decimal($acumProvisions,3)."</td>
                   <td {$styleTotalTotal} colspan='2'>".Yii::app()->format->format_decimal($acumInvoiceMin,3)."</td>
                   <td {$styleTotalTotal} colspan='2'>".Yii::app()->format->format_decimal($acumFactura,3)."</td>
                   <td {$styleTotalTotal} colspan='2'>".Yii::app()->format->format_decimal($acumDiferenceMin,3)."</td>
                   <td {$styleTotalTotal} colspan='2'>".Yii::app()->format->format_decimal($acumDiference,3)."</td>
               </tr>";

        /*********************  VERIFICA SI DESDE INTERFAZ SE ESCOGIO MOSTRAR LOS REPORTES CON SUMMARY INCLUIDO   **********************/  
        if($sum==TRUE)
        {
        /*********************             ARMA UN ARRAY CON LOS PERIODOS PASADOS DESDE EL 2013-09-30             **********************/  
            $backPeriods=self::getBackPeriods($paymentTerm, $toDate, $typeReport);
            $segTwo=count($backPeriods)*50;
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
        /*************   LLAMA DE NUEVO AL MODELO PARA TRAER PROVISIONES, FACTURAS Y DIFERENCIAS PARA PERIODOS ANTERIORES   ************/  
                    $summary=self::getModel($periods["from"], $periods["to"],$typeReport,$paymentTerm,$dividedInvoice, NULL, TRUE);
                    $body.="<tr>
                               <td colspan='12' style='padding-bottom: 20px;'> </td>
                            </tr>
                            <tr>
                               <td colspan='12' style='padding-bottom: 20px;'> </td>
                            </tr>
                            <tr>
                               <td colspan='2'>TIPO DE FACTURACION</td>
                               <td {$styleDescription} colspan='10'> {$complementName} </td>
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
         /*********************            ACUMULADOS DE PROVISIONES SIN FACTURAS EN PERIODOS ANTERIORES         **********************/                     
                            if($detailSummary->doc_number==NULL){
                                $acumNoInvoiceDiffAmountPrev+=$detailSummary->amount;
                                $acumNoInvoiceDiffMinPrev+=$detailSummary->minutes;
                            }else{
         /*********************           ACUMULADOS DE DIFERENCIAS MAYORES A (1 $) EN PERIODOS ANTERIORES       **********************/                            
                                if( $detailSummary->monto_diference>=1||$detailSummary->monto_diference<=-1 ){
                                    $acumMinHigher+=$detailSummary->minutes;
                                    $acumAmountHigher+=$detailSummary->amount;
                                    $acumMinInvoiceHigher+=$detailSummary->fac_minutes;
                                    $acumAmountInvoiceHigher+=$detailSummary->fac_amount;
                                    $acumDifMinHigher+=$detailSummary->min_diference;
                                    $acumDifAmountHigher+=$detailSummary->monto_diference;
                                }
                            }
          /**************** LLENA LA DATA PRINCIPAL, PERO SOLO CON PROVISIONES SIN FACTURAS Y DIFF MAYORES A (1$)  ********************/         
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
                                   <td {$styleDateNull} colspan='12'>En este periodo no hay diferencias de mas de (1 $) o provisiones sin facturas</td>
                               </tr>";
                    }
           /********************                OBTIENE UN MODELO CON LOS TOTALES PARA SUMMARY                ************************/            
                    $totalSummary=self::getModel($periods["from"], $periods["to"],$typeReport,$paymentTerm,$dividedInvoice, TRUE, NULL);
           /********************               ARMA LAS CASILLAS TOTAL PARA CADA CASO DE SUMMARY              ************************/       
                $body.="<tr>
                            <td {$styleTotalTotal} colspan='12'>TOTALES</td>
                        </tr>
                        <tr>
                            <td {$styleProvisions} colspan='2'>MINUTOS</td>
                            <td {$styleProvisions} colspan='2'> MONTO $ </td>
                            <td {$styleSori} colspan='2'> MINUTOS </td>
                            <td {$styleSori} colspan='2'> MONTO $ </td>
                            <td {$styleDiff} colspan='2'> MINUTOS </td>
                            <td {$styleDiff} colspan='2'> MONTO $ </td>
                        </tr>
                        <tr>
                            <td {$styleDifhigher} colspan='2'>".Yii::app()->format->format_decimal($acumMinHigher,3)."</td>
                            <td {$styleDifhigher} colspan='2'>".Yii::app()->format->format_decimal($acumAmountHigher,3)."</td>
                            <td {$styleDifhigher} colspan='2'>".Yii::app()->format->format_decimal($acumMinInvoiceHigher,3)."</td>
                            <td {$styleDifhigher} colspan='2'>".Yii::app()->format->format_decimal($acumAmountInvoiceHigher,3)."</td>
                            <td {$styleDifhigher} colspan='2'>".Yii::app()->format->format_decimal($acumDifMinHigher,3)."</td>
                            <td {$styleDifhigher} colspan='2'>".Yii::app()->format->format_decimal($acumDifAmountHigher,3)."</td>
                        </tr> 
                        <tr>
                            <td {$styleNoInvoice} colspan='2'>".Yii::app()->format->format_decimal($acumNoInvoiceDiffMinPrev,3)."</td>
                            <td {$styleNoInvoice} colspan='2'>".Yii::app()->format->format_decimal($acumNoInvoiceDiffAmountPrev,3)."</td>
                            <td {$styleNoInvoice} colspan='2'></td>
                            <td {$styleNoInvoice} colspan='2'></td>
                            <td {$styleNoInvoice} colspan='2'>".Yii::app()->format->format_decimal($acumNoInvoiceDiffMinPrev,3)."</td>
                            <td {$styleNoInvoice} colspan='2'>".Yii::app()->format->format_decimal($acumNoInvoiceDiffAmountPrev,3)."</td>
                        </tr> 
                        <tr>
                            <td {$styleSubTotal} colspan='2'>".Yii::app()->format->format_decimal($acumMinHigher + $acumNoInvoiceDiffMinPrev,3)."</td>
                            <td {$styleSubTotal} colspan='2'>".Yii::app()->format->format_decimal($acumAmountHigher + $acumNoInvoiceDiffAmountPrev,3)."</td>
                            <td {$styleSubTotal} colspan='2'>".Yii::app()->format->format_decimal($acumMinInvoiceHigher,3)."</td>
                            <td {$styleSubTotal} colspan='2'>".Yii::app()->format->format_decimal($acumAmountInvoiceHigher,3)."</td>
                            <td {$styleSubTotal} colspan='2'>".Yii::app()->format->format_decimal($acumDifMinHigher + $acumNoInvoiceDiffMinPrev,3)."</td>
                            <td {$styleSubTotal} colspan='2'>".Yii::app()->format->format_decimal($acumDifAmountHigher + $acumNoInvoiceDiffAmountPrev,3)."</td>
                        </tr> 
                        <tr>
                            <td {$styleTotalTotal} colspan='2'>".Yii::app()->format->format_decimal($totalSummary->minutes,3)."</td>
                            <td {$styleTotalTotal} colspan='2'>".Yii::app()->format->format_decimal($totalSummary->amount,3)."</td>
                            <td {$styleTotalTotal} colspan='2'>".Yii::app()->format->format_decimal($totalSummary->fac_minutes,3)."</td>
                            <td {$styleTotalTotal} colspan='2'>".Yii::app()->format->format_decimal($totalSummary->fac_amount,3)."</td>
                            <td {$styleTotalTotal} colspan='2'>".Yii::app()->format->format_decimal($totalSummary->min_diference,3)."</td>
                            <td {$styleTotalTotal} colspan='2'>".Yii::app()->format->format_decimal($totalSummary->monto_diference,3)."</td>
                        </tr>";
                    
                    
                }
            }
        }
        /********************                             LEYENDA DE COLORES                                ************************/      
        $body.="<tr>
                   <td {$styleDescription} colspan='12' > Leyenda </td>
                </tr>
                <tr>
                   <td {$styleDifhigher} ></td>
                   <td  {$styleDescription} colspan='11' > Diferencias de mas de (1 $) </td>
                </tr>
                <tr>
                   <td {$styleNoInvoice} ></td>
                   <td {$styleDescription} colspan='11' > Provisiones Sin Factura </td>
                </tr>
                <tr>
                   <td {$styleSubTotal} ></td>
                   <td {$styleDescription} colspan='11' > Diff de mas de (1 $) + Prov Sin Factura </td>
                </tr>
                <tr>
                   <td {$styleTotalTotal} ></td>
                   <td {$styleDescription} colspan='11' > Total General </td>
                </tr>
         </table>";
        
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
            $sql="SELECT b.*, 
                         CASE WHEN (b.minutes - b.fac_minutes) IS NULL THEN (b.minutes) ELSE (b.minutes - b.fac_minutes) END AS min_diference,
                         CASE WHEN (b.amount - b.fac_amount) IS NULL THEN (b.amount) ELSE (b.amount - b.fac_amount) END AS monto_diference
                  FROM (SELECT c.name AS carrier, ad.minutes AS minutes, ad.amount AS amount,
                               (SELECT minutes
                                FROM accounting_document
                                WHERE id_type_accounting_document=(SELECT id FROM type_accounting_document WHERE  name='{$factura}') AND ad.id_accounting_document=id) AS fac_minutes,
                               (SELECT amount
                                FROM accounting_document
                                WHERE id_type_accounting_document=(SELECT id FROM type_accounting_document WHERE  name='{$factura}') AND ad.id_accounting_document=id) AS fac_amount,
                               (SELECT doc_number
                                FROM accounting_document
                                WHERE id_type_accounting_document=(SELECT id FROM type_accounting_document WHERE  name='{$factura}') AND ad.id_accounting_document=id) AS doc_number
                        FROM carrier c, accounting_document ad
                        WHERE c.id IN({$carriers}) AND ad.id_carrier=c.id AND ad.from_date{$from}'{$startDate}' AND ad.to_date{$to}'{$endDate}' AND ad.id_type_accounting_document=(SELECT id FROM type_accounting_document WHERE  name='{$provision}')
                        ORDER BY c.name ASC) b $summaryFilter";
            $return=AccountingDocument::model()->findAllBySql($sql); 
            
        }else{
                $sql="SELECT SUM(b.minutes) AS minutes,
                             SUM(b.amount) AS amount, 
                             SUM(b.fac_minutes) AS fac_minutes, 
                             SUM(b.fac_amount) AS fac_amount, 
                             SUM(b.minutes - b.fac_minutes) AS min_diference, 
                             SUM(b.amount - b.fac_amount) AS monto_diference,
                             '{$startDate}' AS from_date,
                             '{$endDate}' AS to_date
                  FROM (SELECT c.name AS carrier, ad.minutes AS minutes, ad.amount AS amount,
                               (SELECT minutes
                                FROM accounting_document
                                WHERE id_type_accounting_document=(SELECT id FROM type_accounting_document WHERE  name='{$factura}') AND ad.id_accounting_document=id) AS fac_minutes,
                               (SELECT amount
                                FROM accounting_document
                                WHERE id_type_accounting_document=(SELECT id FROM type_accounting_document WHERE  name='{$factura}') AND ad.id_accounting_document=id) AS fac_amount,
                               (SELECT doc_number
                                FROM accounting_document
                                WHERE id_type_accounting_document=(SELECT id FROM type_accounting_document WHERE  name='{$factura}') AND ad.id_accounting_document=id) AS doc_number
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
        if($document->fac_amount==null) 
            return '#D1BFEC';
        if($document->monto_diference>=1||$document->monto_diference<=-1) return '#FAE08D';
            return '#ffffff';
    }
}