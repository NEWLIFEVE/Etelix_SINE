 <?php

/**
 * @package reportes
 * @version 2.0
 */
class Recredi extends Reportes 
{
    /**
     * Encargada de armar el html del reporte
     * @return string
     * @access public
     */
    public function report($date,$intercompany,$no_activity,$PaymentTerm)
    {
        $carrierGroups=CarrierGroups::getAllGroups();
            $seg=count($carrierGroups)*3;
            ini_set('max_execution_time', $seg);
            
        if($date==null) $date=date('Y-m-d');
        $documents=$this->_getData($date,$intercompany,$no_activity,$PaymentTerm);
        $balances_3=$this->_getBalances(DateManagement::calculateDate('-3',$date));
        $balances_2=$this->_getBalances(DateManagement::calculateDate('-2',$date));
        $balances_1=$this->_getBalances(DateManagement::calculateDate('-1',$date));
        if($PaymentTerm=="todos") {
         $typeRecredi="GENERAL";
        }else{
            $typeRecredi=TerminoPago::getModelFind($PaymentTerm)->name;
        }
        
        $soaDue=$soaNext=$provisionInvoiceSent=$provisionInvoiceReceived=$provisionTrafficSent=$provisionTrafficReceived=$receivedDispute=$sentDispute=$balance=$revenue_3=$cost_3=$margin_3=$revenue_2=$cost_2=$margin_2=$revenue_1=$cost_1=$margin_1=0;
        $style_number_row="style='border:0px solid black;text-align:center;background:#83898F;color:white;'";
        $style_basic="style='border:1px solid black;text-align:center;'";
        $style_carrier_head="style='border:0px solid black;background:silver;text-align:center;color:white;'";
        $style_soa_head="style='border:0px solid black;background:#3466B4;text-align:center;color:white;'";
        $style_prov_fact_head="style='border:1px solid black;background:#E99241;text-align:center;color:white;'";
        $style_prov_traf_head="style='border:1px solid black;background:#248CB4;text-align:center;color:white;'";
        $style_prov_disp_head="style='border:1px solid black;background:#C37881;text-align:center;color:white;'";
        $style_balance_head="style='border:0px solid black;background:#2E62B4;text-align:center;color:white;'";
        $style_cost_head="style='border:1px solid black;background:#E99241;text-align:center;color:white;'";
        $style_revenue_head="style='border:1px solid black;background:#06ACFA;text-align:center;color:white;'";
        $style_margin_head="style='border:1px solid black;background:#049C47;text-align:center;color:white;'";

        $body="<table>
                <tr>
                    <td colspan='2'>
                        <h1>RECREDI - {$typeRecredi}</h1>
                    </td>
                    <td colspan='10'>  AL {$date} </td>
                <tr>
                    <td colspan='10'></td>
                </tr>
               </table>
               <table {$style_basic} >
                <tr>
                    <td {$style_number_row} ></td>
                    <td {$style_carrier_head} ></td>
                    <td {$style_soa_head} ></td>
                    <td {$style_soa_head} ></td>
                    <td {$style_soa_head} ></td>
                    <td {$style_soa_head} ></td>
                    <td {$style_prov_fact_head} colspan='2'> PROVISION FACT </td>
                    <td {$style_prov_traf_head} colspan='2'> PROVISION TRAFICO </td>
                    <td {$style_prov_disp_head} colspan='2'> DISPUTAS </td>
                    <td {$style_balance_head} ></td>
                    <td {$style_margin_head} colspan='3'> CAPTURA  ".DateManagement::calculateDate('-3',$date)."</td>
                    <td {$style_margin_head} colspan='3'> CAPTURA  ".DateManagement::calculateDate('-2',$date)."</td>
                    <td {$style_margin_head} colspan='3'> CAPTURA  ".DateManagement::calculateDate('-1',$date)."</td>
                    <td {$style_number_row} ></td>
                </tr>
                <tr>
                    <td {$style_number_row} >N°</td>
                    <td {$style_carrier_head} > CARRIER </td>
                    <td {$style_soa_head} > SOA(DUE)</td>
                    <td {$style_soa_head} > DUE DATE </td>
                    <td {$style_soa_head} > SOA(NEXT)</td>
                    <td {$style_soa_head} > DUE DATE </td>
                    <td {$style_prov_fact_head} > CLIENTES REVENUE </td>
                    <td {$style_prov_fact_head} > PROVEEDORES COST </td>
                    <td {$style_prov_traf_head} > CLIENTES REVENUE </td>
                    <td {$style_prov_traf_head} > PROVEEDORES COST </td>
                    <td {$style_prov_disp_head} > CLIENTES RECIBIDAS </td>
                    <td {$style_prov_disp_head} > PROVEEDORES ENVIADAS </td>
                    <td {$style_balance_head} > BALANCE </td>
                        
                    <td {$style_revenue_head} > REVENUE </td>
                    <td {$style_cost_head} > COST </td>
                    <td {$style_margin_head} > MARGEN </td>
                    <td {$style_revenue_head} > REVENUE </td>
                    <td {$style_cost_head} > COST </td>
                    <td {$style_margin_head} > MARGEN </td>
                    <td {$style_revenue_head} > REVENUE </td>
                    <td {$style_cost_head} > COST </td>
                    <td {$style_margin_head} > MARGEN </td>
                    <td {$style_number_row} >N°</td>
                </tr>";
        foreach($documents as $key => $document)
        {
            $pos=$key+1;
            
            $body.="<tr {$style_basic} >";
            $body.="<td {$style_number_row} >{$pos}</td>";
            $body.="<td {$style_basic} >".$document->name."</td>";
            $body.="<td {$style_basic} >".Yii::app()->format->format_decimal($document->soa)."</td>";
            $soaDue+=$document->soa;
            $body.="<td {$style_basic} >".Utility::ifNull($document->due_date, "Nota 1") ."</td>";
            $body.="<td {$style_basic} >".Yii::app()->format->format_decimal($document->soa_next)."</td>";
            $soaNext+=$document->soa_next;
            $body.="<td {$style_basic} >".Utility::ifNull($document->due_date_next, "Nota") ."</td>";
            $body.="<td {$style_basic} >".Yii::app()->format->format_decimal($document->provision_invoice_sent)."</td>";
            $provisionInvoiceSent+=$document->provision_invoice_sent;
            $body.="<td {$style_basic} >".Yii::app()->format->format_decimal($document->provision_invoice_received)."</td>";
            $provisionInvoiceReceived+=$document->provision_invoice_received;
            $body.="<td {$style_basic} >".Yii::app()->format->format_decimal($document->provision_traffic_sent)."</td>";
            $provisionTrafficSent+=$document->provision_traffic_sent;
            $body.="<td {$style_basic} >".Yii::app()->format->format_decimal($document->provision_traffic_received)."</td>";
            $provisionTrafficReceived+=$document->provision_traffic_received;
            $body.="<td {$style_basic} >".Yii::app()->format->format_decimal($document->received_dispute)."</td>";
            $receivedDispute+=$document->received_dispute;
            $body.="<td {$style_basic} >".Yii::app()->format->format_decimal($document->sent_dispute)."</td>";
            $sentDispute+=$document->sent_dispute;
            $body.="<td {$style_basic} >".Yii::app()->format->format_decimal($document->balance)."</td>";
            $balance+=$document->balance;
            
            $body.="<td {$style_basic} >".Yii::app()->format->format_decimal(self::_getBalance($balances_3,$document->id,"revenue"))."</td>";
            $revenue_3+=self::_getBalance($balances_3,$document->id,"revenue");
            $body.="<td {$style_basic} >".Yii::app()->format->format_decimal(self::_getBalance($balances_3,$document->id,"cost"))."</td>";
            $cost_3+=self::_getBalance($balances_3,$document->id,"cost");
            $body.="<td {$style_basic} >".Yii::app()->format->format_decimal(self::_getBalance($balances_3,$document->id,"margin"))."</td>";
            $margin_3+=self::_getBalance($balances_3,$document->id,"margin");
            $body.="<td {$style_basic} >".Yii::app()->format->format_decimal(self::_getBalance($balances_2,$document->id,"revenue"))."</td>";
            $revenue_2+=self::_getBalance($balances_2,$document->id,"revenue");
            $body.="<td {$style_basic} >".Yii::app()->format->format_decimal(self::_getBalance($balances_2,$document->id,"cost"))."</td>";
            $cost_2+=self::_getBalance($balances_2,$document->id,"cost");
            $body.="<td {$style_basic} >".Yii::app()->format->format_decimal(self::_getBalance($balances_2,$document->id,"margin"))."</td>";
            $margin_2+=self::_getBalance($balances_2,$document->id,"margin");
            $body.="<td {$style_basic} >".Yii::app()->format->format_decimal(self::_getBalance($balances_1,$document->id,"revenue"))."</td>";
            $revenue_1+=self::_getBalance($balances_1,$document->id,"revenue");
            $body.="<td {$style_basic} >".Yii::app()->format->format_decimal(self::_getBalance($balances_1,$document->id,"cost"))."</td>";
            $cost_1+=self::_getBalance($balances_1,$document->id,"cost");
            $body.="<td {$style_basic} >".Yii::app()->format->format_decimal(self::_getBalance($balances_1,$document->id,"margin"))."</td>";
            $margin_1+=self::_getBalance($balances_1,$document->id,"margin");
            
            $body.="<td {$style_number_row} >{$pos}</td>";
            $body.="</tr>";
        }
        $body.="<tr>
                    <td {$style_carrier_head} colspan='2' > CARRIER </td>
                    <td {$style_soa_head} colspan='2'> SOA(DUE)</td>
                    <td {$style_soa_head} colspan='2'> SOA(NEXT)</td>
                    <td {$style_prov_fact_head} > CLIENTES REVENUE </td>
                    <td {$style_prov_fact_head} > PROVEEDORES COST </td>
                    <td {$style_prov_traf_head} > CLIENTES REVENUE </td>
                    <td {$style_prov_traf_head} > PROVEEDORES COST </td>
                    <td {$style_prov_disp_head} > CLIENTES RECIBIDAS </td>
                    <td {$style_prov_disp_head} > PROVEEDORES ENVIADAS </td>
                    <td {$style_balance_head} > BALANCE </td>
                    <td {$style_revenue_head} > REVENUE </td>
                    <td {$style_cost_head} > COST </td>
                    <td {$style_margin_head} > MARGEN </td>
                    <td {$style_revenue_head} > REVENUE </td>
                    <td {$style_cost_head} > COST </td>
                    <td {$style_margin_head} > MARGEN </td>
                    <td {$style_revenue_head} > REVENUE </td>
                    <td {$style_cost_head} > COST </td>
                    <td {$style_margin_head} colspan='2'> MARGEN </td>
                </tr>
                <tr>
                    <td {$style_basic} colspan='2' >Total</td>
                    <td {$style_basic} colspan='2'>".Yii::app()->format->format_decimal($soaDue)."</td>
                    <td {$style_basic} colspan='2'>".Yii::app()->format->format_decimal($soaNext)."</td>
                    <td {$style_basic} >".Yii::app()->format->format_decimal($provisionInvoiceSent)."</td>
                    <td {$style_basic} >".Yii::app()->format->format_decimal($provisionInvoiceReceived)."</td>
                    <td {$style_basic} >".Yii::app()->format->format_decimal($provisionTrafficSent)."</td>
                    <td {$style_basic} >".Yii::app()->format->format_decimal($provisionTrafficReceived)."</td>
                    <td {$style_basic} >".Yii::app()->format->format_decimal($receivedDispute)."</td>
                    <td {$style_basic} >".Yii::app()->format->format_decimal($sentDispute)."</td>
                    <td {$style_basic} >".Yii::app()->format->format_decimal($balance)."</td>
                        
                    <td {$style_basic} >".Yii::app()->format->format_decimal($revenue_3)."</td>
                    <td {$style_basic} >".Yii::app()->format->format_decimal($cost_3)."</td>
                    <td {$style_basic} >".Yii::app()->format->format_decimal($margin_3)."</td>
                    <td {$style_basic} >".Yii::app()->format->format_decimal($revenue_2)."</td>
                    <td {$style_basic} >".Yii::app()->format->format_decimal($cost_2)."</td>
                    <td {$style_basic} >".Yii::app()->format->format_decimal($margin_2)."</td>
                    <td {$style_basic} >".Yii::app()->format->format_decimal($revenue_1)."</td>
                    <td {$style_basic} >".Yii::app()->format->format_decimal($cost_1)."</td>
                    <td {$style_basic} colspan='2'>".Yii::app()->format->format_decimal($margin_1)."</td>
                </tr>";        
        
        $body.="<table><tr style='border:0px><td style='border:0px colspan='23'>Nota: No presenta movimiento despues de la fecha</td></tr></table>";

        if($no_activity==TRUE)$body.="<table><tr style='border:0px><td style='border:0px colspan='23'>Nota 1: No presenta movimiento a la fecha</td></tr><table>";
          else  $body."";
        return $body;
    }
    
    /**
     * Encargada de traer la data
     * @param date $date,$intercompany=TRUE,$no_activity=TRUE,$PaymentTerm
     * @return array
     * @since 2.0
     * @access private
     */
    private function _getData($date,$intercompany=TRUE,$no_activity=TRUE,$PaymentTerm)
    {
     if($intercompany)           $intercompany="";
     elseif($intercompany==FALSE) $intercompany="AND cg.id NOT IN(SELECT id FROM carrier_groups WHERE name IN('FULLREDPERU','R-ETELIX.COM PERU','CABINAS PERU'))";
    
     if($no_activity)           $no_activity="";
     elseif($no_activity==FALSE) $no_activity=" WHERE due_date IS NOT NULL";
     
     if($PaymentTerm=="todos") {
         $filterPaymentTerm="1,2,3,4,5,6,7,8,9,10,12,13";
     }else{
         $filterPaymentTerm="{$PaymentTerm}";
     }

    //El id del grupo
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
        $due_date="(SELECT MAX(date)
                    FROM (SELECT CASE WHEN ({$sqlExpirationCustomer})=0 THEN MAX(issue_date)
                                      WHEN ({$sqlExpirationCustomer})=3 THEN CAST(MAX(issue_date) + interval '3 days' AS date)
                                      WHEN ({$sqlExpirationCustomer})=5 THEN CAST(MAX(issue_date) + interval '5 days' AS date)
                                      WHEN ({$sqlExpirationCustomer})=7 THEN CAST(MAX(issue_date) + interval '7 days' AS date)
                                      WHEN ({$sqlExpirationCustomer})=15 THEN CAST(MAX(issue_date) + interval '15 days' AS date)
                                      WHEN ({$sqlExpirationCustomer})=30 THEN CAST(MAX(issue_date) + interval '30 days' AS date)
                                      WHEN ({$sqlExpirationCustomer}) IS NULL THEN CAST(MAX(issue_date) + interval '7 days' AS date) END AS date
                          FROM accounting_document
                          WHERE id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) AND id_type_accounting_document=1 AND issue_date<='{$date}'
                          UNION
                          SELECT CASE WHEN ({$sqlExpirationSupplier})=0 THEN MAX(valid_received_date)
                                      WHEN ({$sqlExpirationSupplier})=3 THEN CAST(MAX(valid_received_date) + interval '3 days' AS date)
                                      WHEN ({$sqlExpirationSupplier})=5 THEN CAST(MAX(valid_received_date) + interval '5 days' AS date)
                                      WHEN ({$sqlExpirationSupplier})=7 THEN CAST(MAX(valid_received_date) + interval '7 days' AS date)
                                      WHEN ({$sqlExpirationSupplier})=15 THEN CAST(MAX(valid_received_date) + interval '15 days' AS date)
                                      WHEN ({$sqlExpirationSupplier})=30 THEN CAST(MAX(valid_received_date) + interval '30 days' AS date)
                                      WHEN ({$sqlExpirationSupplier}) IS NULL THEN CAST(MAX(valid_received_date) + interval '7 days' AS date) END AS date
                          FROM accounting_document
                          WHERE id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) AND id_type_accounting_document=2 AND valid_received_date<='{$date}') d)  ";

         $due_date_next=" (SELECT MAX(date)
                            FROM (SELECT CASE WHEN ({$sqlExpirationCustomer})=0 THEN MAX(issue_date)
                                              WHEN ({$sqlExpirationCustomer})=3 THEN CAST(MAX(issue_date) + interval '3 days' AS date)
                                              WHEN ({$sqlExpirationCustomer})=5 THEN CAST(MAX(issue_date) + interval '5 days' AS date)
                                              WHEN ({$sqlExpirationCustomer})=7 THEN CAST(MAX(issue_date) + interval '7 days' AS date)
                                              WHEN ({$sqlExpirationCustomer})=15 THEN CAST(MAX(issue_date) + interval '15 days' AS date)
                                              WHEN ({$sqlExpirationCustomer})=30 THEN CAST(MAX(issue_date) + interval '30 days' AS date)
                                              WHEN ({$sqlExpirationCustomer}) IS NULL THEN CAST(MAX(issue_date) + interval '7 days' AS date) END AS date
                                  FROM accounting_document
                                  WHERE id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) AND id_type_accounting_document=1 AND issue_date>'{$date}'
                                  UNION
                                  SELECT CASE WHEN ({$sqlExpirationSupplier})=0 THEN MAX(valid_received_date)
                                              WHEN ({$sqlExpirationSupplier})=3 THEN CAST(MAX(valid_received_date) + interval '3 days' AS date)
                                              WHEN ({$sqlExpirationSupplier})=5 THEN CAST(MAX(valid_received_date) + interval '5 days' AS date)
                                              WHEN ({$sqlExpirationSupplier})=7 THEN CAST(MAX(valid_received_date) + interval '7 days' AS date)
                                              WHEN ({$sqlExpirationSupplier})=15 THEN CAST(MAX(valid_received_date) + interval '15 days' AS date)
                                              WHEN ({$sqlExpirationSupplier})=30 THEN CAST(MAX(valid_received_date) + interval '30 days' AS date)
                                              WHEN ({$sqlExpirationSupplier}) IS NULL THEN CAST(MAX(valid_received_date) + interval '7 days' AS date) END AS date
                                  FROM accounting_document
                                  WHERE id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) AND id_type_accounting_document=2 AND valid_received_date>'{$date}') d) ";  
                                  
        $sql="/*filtro el due_date null*/ 
              SELECT * FROM 
                 (SELECT cg.id AS id,
                     /*El Nombre del grupo*/ 
                     cg.name AS name,
                     /*segmento para soas y due_dates*/
                     /*El monto del soa*/ 
                     (SELECT (i.amount+(p.amount-n.amount)) AS amount
                      FROM (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document=9 AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id)) i,
                           (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(1,3,8,15) AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) AND issue_date<='{$date}') p,
                           (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(2,4,7,14) AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) AND issue_date<='{$date}') n) AS soa, 
                    /*soa next*/
                    (SELECT (
                            (SELECT (i.amount+(p.amount-n.amount)) AS amount
                            FROM (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document=9 AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id)) i,
                                 (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(1,3,8,15) AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id)  AND issue_date<='{$date}') p,
                                 (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(2,4,7,14) AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id)  AND issue_date<='{$date}') n)
                            +(p.amount-n.amount)) AS amount
                    FROM 
                    (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(1,3,8,15) AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) AND {$due_date_next}>'{$date}' AND issue_date>'{$date}') p,
                    (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(2,4,7,14) AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) AND {$due_date_next}>'{$date}' AND issue_date>'{$date}') n) AS soa_next,

                            /*el due date del soa*/
                            {$due_date} AS due_date,

                            /*el due date del soa next*/
                            {$due_date_next} AS due_date_next,
                    /*fin segmento para soas y due_dates*/
                    
                    /*Traigo provisiones de facturas enviadas*/
                     (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount
                      FROM accounting_document
                      WHERE id_type_accounting_document=(SELECT id FROM type_accounting_document WHERE name='Provision Factura Enviada') AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) AND issue_date<='{$date}' AND confirm != -1) AS provision_invoice_sent,
                    /*Traigo provisiones de facturas recibidas*/
                     (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount
                      FROM accounting_document
                      WHERE id_type_accounting_document=(SELECT id FROM type_accounting_document WHERE name='Provision Factura Recibida') AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) AND issue_date<='{$date}' AND confirm != -1) AS provision_invoice_received,
                    /*Traigo provisiones de trafico enviada*/
                     (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount
                      FROM accounting_document
                      WHERE id_type_accounting_document=(SELECT id FROM type_accounting_document WHERE name='Provision Trafico Enviada') AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) AND issue_date<='{$date}' AND confirm != -1) AS provision_traffic_sent,
                    /*Traigo provisiones de trafico recibida*/
                     (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount
                      FROM accounting_document
                      WHERE id_type_accounting_document=(SELECT id FROM type_accounting_document WHERE name='Provision Trafico Recibida') AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) AND issue_date<='{$date}' AND confirm != -1) AS provision_traffic_received,
                    /*Traigo las disputas recibidas*/
                     (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount
                      FROM accounting_document
                      WHERE id_type_accounting_document=(SELECT id FROM type_accounting_document WHERE name='Disputa Recibida') AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) AND issue_date<='{$date}') AS received_dispute,
                    /*Traigo las disputas recibidas*/
                     (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount
                      FROM accounting_document
                      WHERE id_type_accounting_document=(SELECT id FROM type_accounting_document WHERE name='Disputa Enviada') AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) AND issue_date<='{$date}') AS sent_dispute,
                    /*Balance*/
                     (SELECT (i.amount+(p.amount-n.amount)) AS amount
                      FROM (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document=9 and id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id)) i,
                           (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(1,3,6,8,10,12,15) AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) AND issue_date<='{$date}' AND confirm != -1) p,
                           (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(2,4,5,7,11,13,14) AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) AND issue_date<='{$date}' AND confirm != -1) n) AS balance
              FROM carrier_groups cg,
                   carrier c, 
                   contrato con, 
                   contrato_termino_pago ctp, 
                   termino_pago tp
                   
              WHERE c.id_carrier_groups=cg.id 
                    AND group_leader=1
                    AND con.id_carrier=c.id
                    AND ctp.id_contrato=con.id
                    AND ctp.id_termino_pago=tp.id
                    AND ctp.end_date IS NULL
                    AND tp.id IN({$filterPaymentTerm})
                    {$intercompany}  
              ORDER BY cg.name ASC)activity {$no_activity} ";
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
    private static function _getBalance($balances,$id,$atributte)
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
}
?>
