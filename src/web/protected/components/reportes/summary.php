 <?php

/**
 * @package reportes
 * @version 1.0
 */
class summary extends Reportes 
{
    /**
     * Encargada de armar el html del reporte
     * @return string
     * @access public
     */
    public static function report($date,$intercompany,$noActivity,$PaymentTerm)
    {
        $carrierGroups=CarrierGroups::getAllGroups();
            $seg=count($carrierGroups)*3;
            ini_set('max_execution_time', $seg);
            
        $styleNumberRow="style='border:1px solid black;text-align:center;background:#83898F;color:white;'";
        $styleBasic="style='border:1px solid black;text-align: left;'";
        $styleBasicNum="style='border:1px solid black;text-align: right;'";
        $styleActived="style='background:#F0950C;color:white;border:1px solid black;text-align:center;'";
        $styleBasicDate="style='border:1px solid black;text-align: center;'";
        $styleNull="style='border:0px solid white;text-align: left:'";
        $styleCarrier="style='border:1px solid black;background:silver;text-align:center;color:white;'";
        $styleDatePC="style='border:1px solid black;background:#06ACFA;text-align:center;color:white;'";
        $styleSoa="style='border:1px solid black;background:#3466B4;text-align:center;color:white;'";
        $styleDueDateD="style='border:1px solid black;background:#F89289;text-align:center;color:white;'";
        $styleDueDateN="style='border:1px solid black;background:#049C47;text-align:center;color:white;'";
        $styleRowActiv="style='color:red;border:1px solid black;text-align:center;font-size: x-large;padding-bottom: 0.5%;'";
        $last_pago_cobro=$soa=$soa_next=0;
         
        if($PaymentTerm=="todos") {
         $typeRecredi="GENERAL";
        }else{
            $typeRecredi=TerminoPago::getModelFind($PaymentTerm)->name;
        }
        $documents=  self::getData($date,$intercompany,$noActivity,$PaymentTerm);

        $body="<table>
                <tr>
                    <td colspan='2'>
                        <h1>SUMMARY - {$typeRecredi}</h1>
                    </td>
                    <td colspan='9'>  AL {$date} </td>
                <tr>
                    <td colspan='9'></td>
                </tr>
               </table>
               <table style='width: 100%;'>
                <tr>
                    <td {$styleNumberRow} >N°</td>
                    <td {$styleCarrier} > CARRIER </td>
                    
                    <td {$styleDatePC} > ULTIMO(Pag/Cobr) </td>
                    <td {$styleDatePC} > FECHA(Pag/Cobr) </td>
                    <td {$styleSoa} > SOA(DUE) </td>
                    <td {$styleDueDateD} > DUE DATE(D) </td>
                    <td {$styleSoa} > SOA(NEXT) </td>
                    <td {$styleDueDateN} > DUE DATE(N) </td>
                    <td {$styleNumberRow} >N°</td>
                </tr>";
//                    <td {$styleActived} > INACTIV </td>
        foreach ($documents as $key => $document)
        { 
            $styleCollPaym="style='border:1px solid black;text-align: right;color:".self::definePaymCollect($document,"style")."'";
            $pos=$key+1;
            $last_pago_cobro+=$document->last_pago_cobro;
            $soa+=$document->soa;
            $soa_next+=$document->soa_next;
            $body.=" <tr>
                      <td {$styleNumberRow} >{$pos}</td>
                      <td {$styleBasic} > ".$document->name." </td>
                      
                      <td {$styleCollPaym} > ".Yii::app()->format->format_decimal(self::definePaymCollect($document,"value"))." </td>
                      <td {$styleBasicDate} > ".$document->last_date_pago_cobro." </td>
                      <td {$styleBasicNum} > ".Yii::app()->format->format_decimal($document->soa)." </td>
                      <td {$styleBasicDate} > ".$document->due_date." </td>
                      <td {$styleBasicNum} > ".Yii::app()->format->format_decimal($document->soa_next)." </td>
                      <td {$styleBasicDate} > ".$document->due_date_next." </td>
                      <td {$styleNumberRow} >{$pos}</td>
                  </tr>";  
//                      <td {$styleRowActiv} > ".self::defineActive(16)." </td>
        }
         $body.=" <tr>
                      <td {$styleNull} colspan='2'></td>
                      <td {$styleDatePC} >".Yii::app()->format->format_decimal($last_pago_cobro)."</td>
                      <td {$styleNull} ></td>
                      <td {$styleSoa} >".Yii::app()->format->format_decimal($soa)."</td>
                      <td {$styleNull} ></td>
                      <td {$styleSoa} >".Yii::app()->format->format_decimal($soa_next)."</td>
                      <td {$styleNull} ></td>
                      <td {$styleNull} ></td>
                  </tr>
                  </table>";
          return $body;
    }
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
                 return "black";
        }
    }
    public static function defineActive($var)
    {
        if($var!="16")
            return "";
        else
            return "x";
    }
    /**
     * Encargada de traer la data
     * @param date $date,$intercompany=TRUE,$no_activity=TRUE,$PaymentTerm
     * @return array
     * @since 1.0
     * @access public
     */
    public static function getData($date,$intercompany=TRUE,$no_activity=TRUE,$PaymentTerm)
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
                                   /*monto del ultimo pago o cobro*/
	                            (select amount 
                                        from accounting_document
                                        where id_type_accounting_document IN(select id from type_accounting_document where name in ('Pago','Cobro'))
                                          and id_carrier IN (SELECT id FROM carrier WHERE id_carrier_groups=cg.id)
                                          and issue_date<='{$date}'
                                          order by issue_date desc LIMIT 1) AS last_pago_cobro,
                                          
                                   /*tipo (pago o cobro)*/
	                            (select t.name 
                                        from accounting_document a, type_accounting_document t
                                        where id_type_accounting_document IN(select id from type_accounting_document where name in ('Pago','Cobro'))
                                          and id_carrier IN (SELECT id FROM carrier WHERE id_carrier_groups=cg.id)
                                          and issue_date<='{$date}'
                                          and id_type_accounting_document=t.id
                                              
                                          order by issue_date desc LIMIT 1) AS type_c_p,
                                          
			          /*monto del ultimo pago o cobro*/     
			           (select max(issue_date) as date
                                        from accounting_document
                                        where id_type_accounting_document IN(select id from type_accounting_document where name in ('Pago','Cobro'))
                                          and id_carrier IN (SELECT id FROM carrier WHERE id_carrier_groups=cg.id)
                                          and issue_date<='{$date}') AS last_date_pago_cobro ,
                                          
                     /*El Nombre del grupo*/ 
                     cg.name AS name,
                     /*segmento para soas y due_dates*/
                     /*El monto del soa*/ 
                     (SELECT (i.amount+(p.amount-n.amount)) AS amount
                      FROM (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document=9 AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id)) i,
                           (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(1,3,8,15) AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) AND issue_date<='{$date}') p,
                           (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(2,4,7,14) AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) AND issue_date<='{$date}') n) AS soa, 
                   /*el due date del soa*/
                   
                            {$due_date} AS due_date,
                                
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
                            /*el due date del soa next*/
                            
                            {$due_date_next} AS due_date_next
                                
                           /*fin segmento para soas y due_dates*/
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
}
?>
