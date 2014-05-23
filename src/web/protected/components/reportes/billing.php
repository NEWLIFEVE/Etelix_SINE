 <?php

/**
 * @package reportes
 * @version 1.0
 */
class Billing extends Reportes 
{
    private $totalSoaDue;
    private $totalSoaNext;
    private $totalProvisionInvoiceSent;
    private $totalProvisioInvoiceReceived;
    private $totalProvisioTrafficSent;
    private $totalProvisioTrafficReceived;
    private $totalReceivedDispute;
    private $totalSentDispute;
    private $totalBalance;
    private $totalRevenue3;
    private $totalCost3;
    private $totalMargin3;
    private $totalRevenue2;
    private $totalCost2;
    private $totalMargin2;
    private $totalRevenue1;
    private $totalCost1;
    private $totalMargin1;
    
    private $styleNumberRow ="style='border:1px solid silver;text-align:center;background:#83898F;color:white;'";
    private $styleBasic ="style='border:1px solid silver;text-align:center;'";
    private $styleCarrierHead ="style='border:1px solid silver;background:silver;text-align:center;color:white;'";
    private $styleSoaHead ="style='border:1px solid silver;background:#3466B4;text-align:center;color:white;'";
    private $styleProvFactHead ="style='border:1px solid silver;background:#E99241;text-align:center;color:white;'";
    private $styleProvTrafHead ="style='border:1px solid silver;background:#248CB4;text-align:center;color:white;'";
    private $styleProvDispHead ="style='border:1px solid silver;background:#C37881;text-align:center;color:white;'";
    private $styleBalanceHead ="style='border:1px solid silver;background:#2E62B4;text-align:center;color:white;'";
    private $styleCostHead ="style='border:1px solid silver;background:#E99241;text-align:center;color:white;'";
    private $styleRevenueHead ="style='border:1px solid silver;background:#06ACFA;text-align:center;color:white;'";
    private $styleMarginHead ="style='border:1px solid silver;background:#049C47;text-align:center;color:white;'";
    private $styleNull ="style='border:1px solid white;'";
   
    /**
     * Encargada de armar el html del reporte
     * @param type $date
     * @param type $interCompany
     * @param type $noActivity
     * @param type $typePaymentTerm
     * @param type $paymentTerm
     * @return string
     */
    public function report($date,$interCompany,$noActivity,$typePaymentTerm,$paymentTerm)
    {
        /*********************   AYUDA A AUMENTAR EL TIEMPO PARA GENERAR EL REPORTE CUANDO SON MUCHOS REGISTROS   **********************/
        ini_set('max_execution_time', 500);

        if($date==null) $date=date('Y-m-d');
        $documents=$this->_getData($date,$interCompany,$noActivity,$typePaymentTerm,$paymentTerm);
        
        $body=NULL;
        if($documents!=NULL)
        {
            $body.="<br>
                    <table>
                        <tr>
                            <td colspan='10'>
                                <h2> ".Reportes::defineNameExtra($paymentTerm,$typePaymentTerm, NULL)."</h2>
                                al {$date}
                            </td>
                        </tr>
                   </table>
                   <table >
                    <tr>
                        <td {$this->styleNumberRow} >N°</td>
                        <td {$this->styleCarrierHead} > CARRIER </td>
                        <td {$this->styleBalanceHead} colspan='2'> SINE </td>
                        <td {$this->styleCarrierHead} > CARRIER </td>
                        <td {$this->styleBalanceHead} colspan='2'> BILLING </td>
                        <td {$this->styleBalanceHead} colspan='2'> DIFERENCE </td>
                        <td {$this->styleNumberRow} >N°</td>
                    </tr>";
            foreach($documents as $key => $document)
            {
                $pos=$key+1;

                $body.="<tr {$this->styleBasic} >";
                    $body.="<td {$this->styleNumberRow} >{$pos}</td>";
                    $body.="<td {$this->styleBasic} >".$document->name."</td>";
                    $body.="<td {$this->styleBasic} colspan='2'>".Yii::app()->format->format_decimal($document->balance)."</td>";
                    $body.="<td {$this->styleBasic} >".$document->name."</td>";
                    $body.="<td {$this->styleBasic} colspan='2'>".Yii::app()->format->format_decimal($document->balance)."</td>";
                    $body.="<td {$this->styleBasic} colspan='2'>".Yii::app()->format->format_decimal($document->balance)."</td>";

                    $body.="<td {$this->styleNumberRow} >{$pos}</td>";
                $body.="</tr>";
            }
            $body.="<tr>
                        <td {$this->styleNumberRow} >N°</td>
                        <td {$this->styleCarrierHead} > CARRIER </td>
                        <td {$this->styleBalanceHead} colspan='2'> SINE </td>
                        <td {$this->styleCarrierHead} > CARRIER </td>
                        <td {$this->styleBalanceHead} colspan='2'> BILLING </td>
                        <td {$this->styleBalanceHead} colspan='2'> DIFERENCE </td>
                        <td {$this->styleNumberRow} >N°</td>
                    </tr>
                </table>";        

            
        }  
        return $body;
    }
    
    /**
     * Metodo encargado de armar el resumen total cuando se consulta recredi para todos los termino pago.
     * @param type $date
     * @return string
     */
    public function totalsGeneral($date)
    {
        $body="<h2 style='color:#06ACFA!important;'>RESUMEN TOTAL GENERAL</h2> <br>";
        $body.="<table  style='width: 100%;'>
                <tr>
                    <td {$this->styleNull} ></td>
                    <td {$this->styleMarginHead} colspan='3'> CAPTURA  ".DateManagement::calculateDate('-3',$date)."</td>
                    <td {$this->styleMarginHead} colspan='3'> CAPTURA  ".DateManagement::calculateDate('-2',$date)."</td>
                    <td {$this->styleMarginHead} colspan='3'> CAPTURA  ".DateManagement::calculateDate('-1',$date)."</td>
                    <td {$this->styleNull} colspan='7'></td>
                    <td {$this->styleProvFactHead} colspan='2'> PROVISION FACT </td>
                    <td {$this->styleProvTrafHead} colspan='2'> PROVISION TRAFICO </td>
                    <td {$this->styleProvDispHead} colspan='2'> DISPUTAS </td>
                    <td {$this->styleNull} ></td>
                </tr>
                <tr>
                   <td {$this->styleNull} ></td>
                   <td {$this->styleRevenueHead} > REVENUE </td>
                   <td {$this->styleCostHead} > COST </td>
                   <td {$this->styleMarginHead} > MARGEN </td>
                   <td {$this->styleRevenueHead} > REVENUE </td>
                   <td {$this->styleCostHead} > COST </td>
                   <td {$this->styleMarginHead} > MARGEN </td>
                   <td {$this->styleRevenueHead} > REVENUE </td>
                   <td {$this->styleCostHead} > COST </td>
                   <td {$this->styleMarginHead} > MARGEN </td>
                   <td {$this->styleSoaHead} colspan='3'> BALANCE </td>
                       
                   <td {$this->styleSoaHead} colspan='2'> SOA(DUE)</td>
                   <td {$this->styleSoaHead} colspan='2'> SOA(NEXT)</td>
                   <td {$this->styleProvFactHead} > CLIENTES REVENUE </td>
                   <td {$this->styleProvFactHead} > PROVEEDORES COST </td>
                   <td {$this->styleProvTrafHead} > CLIENTES REVENUE </td>
                   <td {$this->styleProvTrafHead} > PROVEEDORES COST </td>
                   <td {$this->styleProvDispHead} > CLIENTES RECIBIDAS </td>
                   <td {$this->styleProvDispHead} > PROVEEDORES ENVIADAS </td>
                   <td {$this->styleNull} ></td>
               </tr>";
        $body.="<tr>
                    <td {$this->styleNull} ></td>
                    <td {$this->styleBasic} >".Yii::app()->format->format_decimal($this->totalRevenue3)."</td>
                    <td {$this->styleBasic} >".Yii::app()->format->format_decimal($this->totalCost3)."</td>
                    <td {$this->styleBasic} >".Yii::app()->format->format_decimal($this->totalMargin3)."</td>
                    <td {$this->styleBasic} >".Yii::app()->format->format_decimal($this->totalRevenue2)."</td>
                    <td {$this->styleBasic} >".Yii::app()->format->format_decimal($this->totalCost2)."</td>
                    <td {$this->styleBasic} >".Yii::app()->format->format_decimal($this->totalMargin2)."</td>
                    <td {$this->styleBasic} >".Yii::app()->format->format_decimal($this->totalRevenue1)."</td>
                    <td {$this->styleBasic} >".Yii::app()->format->format_decimal($this->totalCost1)."</td>
                    <td {$this->styleBasic} >".Yii::app()->format->format_decimal($this->totalMargin1)."</td>
                    <td {$this->styleBasic}  colspan='3'>".Yii::app()->format->format_decimal($this->totalBalance)."</td>
                        
                    <td {$this->styleBasic} colspan='2'>".Yii::app()->format->format_decimal($this->totalSoaDue)."</td>
                    <td {$this->styleBasic} colspan='2'>".Yii::app()->format->format_decimal($this->totalSoaNext)."</td>
                    <td {$this->styleBasic} >".Yii::app()->format->format_decimal($this->totalProvisionInvoiceSent)."</td>
                    <td {$this->styleBasic} >".Yii::app()->format->format_decimal($this->totalProvisioInvoiceReceived)."</td>
                    <td {$this->styleBasic} >".Yii::app()->format->format_decimal($this->totalProvisioTrafficSent)."</td>
                    <td {$this->styleBasic} >".Yii::app()->format->format_decimal($this->totalProvisioTrafficReceived)."</td>
                    <td {$this->styleBasic} >".Yii::app()->format->format_decimal($this->totalReceivedDispute)."</td>
                    <td {$this->styleBasic} >".Yii::app()->format->format_decimal($this->totalSentDispute)."</td>
                    <td {$this->styleNull} ></td>
                  </tr>
                </table>";
        return $body;
    }

    /**
     * Encargada de traer data para los listados y para el total total con el atributo $totals=TRUE
     * @param type $date
     * @param type $interCompany=TRUE
     * @param type $noActivity=TRUE
     * @param type $typePaymentTerm
     * @param type $paymentTerm
     * @param type $totals=NULL
     * @return type
     * @since 2.0
     * @access private
     */
    private function _getData($date,$interCompany=TRUE,$noActivity=TRUE,$typePaymentTerm,$paymentTerm)
    {
        if($interCompany)           $interCompany="";
        elseif($interCompany==FALSE) $interCompany="AND cg.id NOT IN(SELECT id FROM carrier_groups WHERE name IN('FULLREDPERU','R-ETELIX.COM PERU','CABINAS PERU'))";

        if($noActivity)           $noActivity="";
        elseif($noActivity==FALSE) $noActivity=" WHERE due_date IS NOT NULL";

        if($paymentTerm=="todos") {
            $filterPaymentTerm="1,2,3,4,5,6,7,8,9,10,12,13";
        }else{
            $filterPaymentTerm="{$paymentTerm}";
        }
        
        if($typePaymentTerm===NULL){
            $tableNext="";
            $wherePaymentTerm="";
        }
        if($typePaymentTerm===FALSE){
            $tableNext=", contrato con,  contrato_termino_pago ctp, termino_pago tp";
            $wherePaymentTerm="AND con.id_carrier=c.id
                               AND ctp.id_contrato=con.id
                               AND ctp.id_termino_pago=tp.id
                               AND ctp.end_date IS NULL
                               AND tp.id IN({$filterPaymentTerm})";
        }
        if($typePaymentTerm===TRUE){
            $tableNext=", contrato con,  contrato_termino_pago_supplier ctps, termino_pago tp";
            $wherePaymentTerm="AND con.id_carrier=c.id
                               AND ctps.id_contrato=con.id
                               AND ctps.id_termino_pago_supplier=tp.id
                               AND ctps.end_date IS NULL
                               AND tp.id IN({$filterPaymentTerm})";
        }

        $sqlExpirationCustomer="SELECT tp.expiration
                                FROM carrier c, 
                                     (SELECT id, sign_date, production_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_carrier, id_company, up, bank_fee
                                      FROM contrato
                                      WHERE sign_date<='{$date}') con, 
                                     (SELECT id, start_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_contrato, id_termino_pago
                                      FROM contrato_termino_pago
                                      WHERE start_date<='{$date}') ctp, 
                                     termino_pago tp
                                WHERE con.id_carrier=c.id AND ctp.id_contrato=con.id AND ctp.id_termino_pago=tp.id AND con.end_date>='{$date}' AND con.sign_date IS NOT NULL AND ctp.end_date>='{$date}' AND c.id IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id)
                                LIMIT 1";
        $sqlExpirationSupplier="SELECT tp.expiration
                                FROM carrier c, 
                                     (SELECT id, sign_date, production_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_carrier, id_company, up, bank_fee
                                      FROM contrato
                                      WHERE sign_date<='{$date}') con, 
                                     (SELECT id, start_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_contrato, id_termino_pago_supplier
                                      FROM contrato_termino_pago_supplier
                                      WHERE start_date<='{$date}') ctps, 
                                     termino_pago tp
                                WHERE con.id_carrier=c.id AND ctps.id_contrato=con.id AND ctps.id_termino_pago_supplier=tp.id AND con.end_date>='{$date}' AND con.sign_date IS NOT NULL AND ctps.end_date>='{$date}' AND c.id IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id)
                                LIMIT 1";
        $dueDate="(SELECT MAX(date)
                    FROM (SELECT CASE WHEN ({$sqlExpirationCustomer})=0 THEN issue_date
                                      WHEN ({$sqlExpirationCustomer})=3 THEN CAST(issue_date + interval '3 days' AS date)
                                      WHEN ({$sqlExpirationCustomer})=5 THEN CAST(issue_date + interval '5 days' AS date)
                                      WHEN ({$sqlExpirationCustomer})=7 THEN CAST(issue_date + interval '7 days' AS date)
                                      WHEN ({$sqlExpirationCustomer})=15 THEN CAST(issue_date + interval '15 days' AS date)
                                      WHEN ({$sqlExpirationCustomer})=30 THEN CAST(issue_date + interval '30 days' AS date)
                                      WHEN ({$sqlExpirationCustomer}) IS NULL THEN CAST(issue_date + interval '7 days' AS date) END AS date
                          FROM accounting_document
                          WHERE id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) AND id_type_accounting_document=1 
                          UNION
                          SELECT CASE WHEN ({$sqlExpirationSupplier})=0 THEN valid_received_date
                                      WHEN ({$sqlExpirationSupplier})=3 THEN CAST(valid_received_date + interval '3 days' AS date)
                                      WHEN ({$sqlExpirationSupplier})=5 THEN CAST(valid_received_date + interval '5 days' AS date)
                                      WHEN ({$sqlExpirationSupplier})=7 THEN CAST(valid_received_date + interval '7 days' AS date)
                                      WHEN ({$sqlExpirationSupplier})=15 THEN CAST(valid_received_date + interval '15 days' AS date)
                                      WHEN ({$sqlExpirationSupplier})=30 THEN CAST(valid_received_date + interval '30 days' AS date)
                                      WHEN ({$sqlExpirationSupplier}) IS NULL THEN CAST(valid_received_date + interval '7 days' AS date) END AS date
                          FROM accounting_document
                          WHERE id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) AND id_type_accounting_document=2 ) d
                            ";/* esto es lo que continua en el caso de due_date= WHERE d.date<='{$date}')*/    
                         
        $sql="SELECT * 
              FROM  (SELECT 
                     DISTINCT cg.id AS id, 
                     cg.name AS name, 
                     /*la variable select completa el select principal, en su estado natural trae todos los parametros y en el interno comienza con el id y nombre de grupo para los totales el select principal extrae la suma de cada valor y no extrae los datos basicos de grupo*/
               /*-----------------------------------------------------------------------------------------------------------*/  
                     /*El monto del soa*/ 
                     (SELECT (i.amount+e.amount+p.amount-n.amount-r.amount) AS amount
                      FROM (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document=9 AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id)) i,
                           (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(1) AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) AND 
                           CASE WHEN ({$sqlExpirationCustomer})=0 THEN issue_date
                                              WHEN ({$sqlExpirationCustomer})=3 THEN CAST(issue_date + interval '3 days' AS date)
                                              WHEN ({$sqlExpirationCustomer})=5 THEN CAST(issue_date + interval '5 days' AS date)
                                              WHEN ({$sqlExpirationCustomer})=7 THEN CAST(issue_date + interval '7 days' AS date)
                                              WHEN ({$sqlExpirationCustomer})=15 THEN CAST(issue_date + interval '15 days' AS date)
                                              WHEN ({$sqlExpirationCustomer})=30 THEN CAST(issue_date + interval '30 days' AS date)
                                              WHEN ({$sqlExpirationCustomer}) IS NULL THEN CAST(issue_date + interval '7 days' AS date) END<='{$date}') e,
                           (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(2) AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) AND 
                           CASE WHEN ({$sqlExpirationSupplier})=0 THEN valid_received_date
                                              WHEN ({$sqlExpirationSupplier})=3 THEN CAST(valid_received_date + interval '3 days' AS date)
                                              WHEN ({$sqlExpirationSupplier})=5 THEN CAST(valid_received_date + interval '5 days' AS date)
                                              WHEN ({$sqlExpirationSupplier})=7 THEN CAST(valid_received_date + interval '7 days' AS date)
                                              WHEN ({$sqlExpirationSupplier})=15 THEN CAST(valid_received_date + interval '15 days' AS date)
                                              WHEN ({$sqlExpirationSupplier})=30 THEN CAST(valid_received_date + interval '30 days' AS date)
                                              WHEN ({$sqlExpirationSupplier}) IS NULL THEN CAST(valid_received_date + interval '7 days' AS date) END<='{$date}') r,
                           (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(3,7,15) AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) AND issue_date<='{$date}') p,
                           (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(4,8,14) AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) AND issue_date<='{$date}') n) AS soa, 
              /*-----------------------------------------------------------------------------------------------------------*/     
                    /*el due date del soa*/
                   
                           {$dueDate} WHERE d.date<='{$date}') AS due_date,
              /*-----------------------------------------------------------------------------------------------------------*/                   
                    /*el soa next*/

                     (SELECT (i.amount+(p.amount-n.amount)) AS amount
                      FROM (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document=9 AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id)) i,
                           (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(1,3,7,15) AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) ) p,
                           (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(2,4,8,14) AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) ) n) AS soa_next, 
              /*-----------------------------------------------------------------------------------------------------------*/       
                    /*el due date del soa next*/

                           {$dueDate}) AS due_date_next,
              /*-----------------------------------------------------------------------------------------------------------*/                   
                    /*fin segmento para soas y due_dates*/
              /*-----------------------------------------------------------------------------------------------------------*/        
                    /*Traigo provisiones de facturas enviadas*/
                     (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount
                      FROM accounting_document
                      WHERE id_type_accounting_document=(SELECT id FROM type_accounting_document WHERE name='Provision Factura Enviada') AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) AND issue_date<='{$date}' AND id_accounting_document IS NULL) AS provision_invoice_sent,
              /*-----------------------------------------------------------------------------------------------------------*/  
                    /*Traigo provisiones de facturas recibidas*/
                     (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount
                      FROM accounting_document
                      WHERE id_type_accounting_document=(SELECT id FROM type_accounting_document WHERE name='Provision Factura Recibida') AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) AND issue_date<='{$date}' AND id_accounting_document IS NULL) AS provision_invoice_received,
              /*-----------------------------------------------------------------------------------------------------------*/        
                    /*Traigo provisiones de trafico enviada*/
                     (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount
                      FROM accounting_document
                      WHERE id_type_accounting_document=(SELECT id FROM type_accounting_document WHERE name='Provision Trafico Enviada') AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) AND issue_date<='{$date}' AND id_accounting_document IS NULL) AS provision_traffic_sent,
              /*-----------------------------------------------------------------------------------------------------------*/        
                    /*Traigo provisiones de trafico recibida*/
                     (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount
                      FROM accounting_document
                      WHERE id_type_accounting_document=(SELECT id FROM type_accounting_document WHERE name='Provision Trafico Recibida') AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) AND issue_date<='{$date}' AND id_accounting_document IS NULL) AS provision_traffic_received,
              /*-----------------------------------------------------------------------------------------------------------*/        
                    /*Traigo las disputas recibidas*/
                     (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount
                      FROM accounting_document
                      WHERE id_type_accounting_document=(SELECT id FROM type_accounting_document WHERE name='Disputa Recibida') AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) AND issue_date<='{$date}' AND id_accounting_document NOT IN (SELECT id_accounting_document FROM accounting_document WHERE id_type_accounting_document IN (7) AND id_accounting_document IS NOT NULL)) AS received_dispute,
              /*-----------------------------------------------------------------------------------------------------------*/        
                    /*Traigo las disputas recibidas*/
                     (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount
                      FROM accounting_document
                      WHERE id_type_accounting_document=(SELECT id FROM type_accounting_document WHERE name='Disputa Enviada') AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) AND issue_date<='{$date}' AND id_accounting_document NOT IN (SELECT id_accounting_document FROM accounting_document WHERE id_type_accounting_document IN (8) AND id_accounting_document IS NOT NULL)) AS sent_dispute,
              /*-----------------------------------------------------------------------------------------------------------*/        
                    /*Balance*/
                     (SELECT (i.amount + p.amount + pp.amount + dp.amount - n.amount - dn.amount - pn.amount) AS amount
                      FROM (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document=9 and id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id)) i,
                           (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(1,3,7,15) AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) AND issue_date<='{$date}' ) p,
                           (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(2,4,8,14) AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) AND issue_date<='{$date}' ) n,
                           (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(6) AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) AND issue_date<='{$date}' AND id_accounting_document NOT IN (SELECT id_accounting_document FROM accounting_document WHERE id_type_accounting_document IN (8) AND id_accounting_document IS NOT NULL)) dp,
                           (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(5) AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) AND issue_date<='{$date}' AND id_accounting_document NOT IN (SELECT id_accounting_document FROM accounting_document WHERE id_type_accounting_document IN (7) AND id_accounting_document IS NOT NULL)) dn,
                           (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(10,12) AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) AND issue_date<='{$date}' AND id_accounting_document IS NULL) pp,
                           (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(11,13) AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) AND issue_date<='{$date}' AND id_accounting_document IS NULL) pn) AS balance
                    
              FROM carrier_groups cg,
                   carrier c {$tableNext}
                   
              WHERE c.id_carrier_groups=cg.id 
                    {$wherePaymentTerm}
                    {$interCompany}  
              ORDER BY cg.name ASC)activity {$noActivity}";
        return AccountingDocument::model()->findAllBySql($sql);
    }
    
    /**
     * Trae la data de balance sori de los grupos
     * @access private
     * @param date $date
     * @return array
     */
    private function _getBalances($date)
    {
        $sql="SELECT c.id_carrier_groups AS id, SUM(b.revenue) as revenue, SUM(b.cost) AS cost, SUM(b.revenue-b.cost) AS margin
              FROM (SELECT id_carrier_customer AS id, CASE WHEN ABS(SUM(revenue))>ABS(SUM(cost+margin)) THEN SUM(cost+margin) ELSE SUM(revenue) END AS revenue, CAST(0 AS double precision) AS cost
                    FROM balance
                    WHERE date_balance='{$date}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
                    GROUP BY id_carrier_customer
                    UNION
                    SELECT id_carrier_supplier AS id, CAST(0 AS double precision) AS revenue, CASE WHEN ABS(SUM(cost))>ABS(SUM(revenue-margin)) THEN SUM(revenue-margin) ELSE SUM(cost) END AS cost
                    FROM balance
                    WHERE date_balance='{$date}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
                    GROUP BY id_carrier_supplier)b, carrier c
              WHERE c.id=b.id
              GROUP BY c.id_carrier_groups";
        return Balance::model()->findAllBySql($sql);
    }
    /**
     *
     */
    private function _getBalance($balances,$id,$atributte)
    {
        foreach ($balances as $key => $balance)
        {
            if($balance->id==$id)
            {
                return $balance->$atributte;
            }
        }
        return "0.00";
    }
    /**
     * Metodo encargado de determinar el tipo de reporte exacto y de ahi  pasar los parametros necesarios, hay varios casos:
     * 1- Ambos tipos de relacion comercial con todos los termino pago, en este caso se ejecuta dos foreach , uno consecutivo del otro buscando data de todos los termino pago como customer y supplier sucesivamente.
     * 2- Relacion comercial supplier o customer, donde selecciona todos los termino pago, se ejecuta un foreach buscando data en esa relacion y todos los terminos pago correspondientes.
     * 3- Un solo tipo de relacion comercial y un solo termino pago, ahi la busqueda es directa.
     * @param type $date
     * @param type $interCompany
     * @param type $noActivity
     * @param type $typePaymentTerm
     * @param type $paymentTerms
     * @return type
     */
    public function defineReport($date,$interCompany,$noActivity,$typePaymentTerm,$paymentTerms)
    {
        ini_set('max_execution_time', 1500);
        $var="";
        if($paymentTerms=="todos") {
            $paymentTerms= TerminoPago::getModel();
            
            if($typePaymentTerm===NULL){/*Este caso es si se selecciono traer ambos tipos de relacion comercial*/
                $var.="<h1 style='color:#06ACFA!important;'>RECREDI CUSTOMER</h1> <br>";
                foreach ($paymentTerms as $key => $paymentTerm) /*Busca todos los termino pago en la relacion customer*/
                {
                   if($paymentTerm->name!="Sin estatus") $var.= $this->report($date,$interCompany,$noActivity,FALSE,$paymentTerm->id);
                }
                $var.="<br> <h1 style='color:#06ACFA!important;'>RECREDI SUPPLIER</h1> <br>";
                foreach ($paymentTerms as $key => $paymentTerm) /*Concatena al customer y busca todos los termino pago en la relacion supplier*/
                {
                   if($paymentTerm->name!="Sin estatus") $var.= $this->report($date,$interCompany,$noActivity,TRUE,$paymentTerm->id);
                }
            }else{
                $var.="<h1>RECREDI</h1>";
                foreach ($paymentTerms as $key => $paymentTerm) /*Busca todos los termino pago en la relacion seleccionada, (solo una:customer o supplier)*/
                {
                   if($paymentTerm->name!="Sin estatus") $var.= $this->report($date,$interCompany,$noActivity,$typePaymentTerm,$paymentTerm->id);
                }
                $var.= $this->totalsGeneral($date);
            }
        }else{                                                  /*Busca un solo termino pago en la relacion seleccionada, (solo una:customer o supplier)*/
            $data= $this->report($date,$interCompany,$noActivity,$typePaymentTerm,$paymentTerms);
            if($data!=NULL)
                $var.="<h1>RECREDI</h1>". $data;
            else
                $var.="<h3>No hay data para este termino pago en la relacion comercial seleccionada</h3>";
            
        }    
        return $var;
    }
}
?>