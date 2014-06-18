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
    public static function report($date,$intercompany,$noActivity,$typePaymentTerm,$PaymentTerm)
    {
        $carrierGroups=CarrierGroups::getAllGroups();
            $seg=count($carrierGroups)*3;
            ini_set('max_execution_time', $seg);
            
        $styleNumberRow="style='border:1px solid black;text-align:center;background:#83898F;color:white;'";
        $styleBasic="style='border:1px solid black;text-align: left;color=#6F7074;'";
        $styleBasicNum="style='border:1px solid black;text-align: right;color=#6F7074;'";
        $styleActived="style='background:#F0950C;color:white;border:1px solid black;text-align:center;'";
        $styleBasicDate="style='border:1px solid black;text-align: center;'";
        $styleNull="style='border:0px solid white;text-align: left:'";
        $styleCarrier="style='border:1px solid black;background:silver;text-align:center;color:white;'";
        $styleDatePC="style='border:1px solid black;background:#06ACFA;text-align:center;color:white;'";

        $styleSoaDue="style='border:1px solid black;background:#3466B4;text-align:center;color:white;'";
        $styleSoaNext="style='border:1px solid black;background:#049C47;text-align:center;color:white;'";
        $styleDueDateD="style='border:1px solid black;background:#3466B4;text-align:center;color:white;'";
        $styleDueDateN="style='border:1px solid black;background:#049C47;text-align:center;color:white;'";
        $styleRowActiv="style='color:red;border:1px solid black;text-align:center;font-size: x-large;padding-bottom: 0.5%;'";
        $last_pago_cobro=$soa=$soa_next=0;$dueDaysNext="";

        if($PaymentTerm=="todos") {
         $typeRecredi="GENERAL";
        }else{
            $typeRecredi=TerminoPago::getModelFind($PaymentTerm)->name;
        }
        $documents=  self::getData($date,$intercompany,$noActivity,$typePaymentTerm,$PaymentTerm);

        $body="<table>
                <tr>
                    <td colspan='2'>
                        <h1>SUMMARY - {$typeRecredi}</h1>
                    </td>
                    <td colspan='11'>  AL {$date} </td>
                <tr>
                    <td colspan='11'></td>
                </tr>
               </table>
               <table style='width: 100%;'>
                <tr>
                    <td {$styleNumberRow} >N°</td>
                    <td {$styleCarrier} > CARRIER </td>
                    <td {$styleActived} > INACTIVE </td>
                    <td {$styleDatePC} > ULTIMO(Pag/Cobr) </td>
                    <td {$styleDatePC} > FECHA(Pag/Cobr) </td>
                    <td {$styleSoaDue} > SOA(DUE) </td>
                    <td {$styleDueDateD} > DUE DATE(D) </td>

                    <td {$styleDueDateD} > DUE DAYS </td>

                    <td {$styleSoaNext} > SOA(NEXT) </td>
                    <td {$styleDueDateN} > DUE DATE(N) </td>
                    <td {$styleDueDateN} > DUE DAYS </td>
                    <td {$styleNumberRow} >N°</td>
                </tr>";
        foreach ($documents as $key => $document)
        { 
            if($document->due_date_next==NULL||$document->due_date_next==$document->due_date)
                $dueDaysNext="0";
            else
                $dueDaysNext=abs(DateManagement::howManyDaysBetween($document->due_date_next,$date));
            
            $styleCollPaym="style='border:1px solid black;text-align: right;color:".self::definePaymCollect($document,"style")."'";
            $styleOldDate="style='border:1px solid black;text-align: center;background:".self::defineStyleOld($document->last_date_pago_cobro, $date)."'";
            $pos=$key+1;
            $last_pago_cobro+=$document->last_pago_cobro;
            $soa+=$document->soa;
            $soa_next+=$document->soa_next;
            $body.=" <tr>
                      <td {$styleNumberRow} >{$pos}</td>
                      <td {$styleBasic} > ".$document->name." </td>
                      <td {$styleRowActiv} > ".self::defineActive($document->active)." </td>
                      <td {$styleCollPaym} > ".Yii::app()->format->format_decimal(self::definePaymCollect($document,"value"))." </td>
                      <td {$styleOldDate} > ".Utility::formatDateSINE($document->last_date_pago_cobro,"Y-m-d")." </td>
                      <td {$styleBasicNum} > ".Yii::app()->format->format_decimal($document->soa)." </td>
                      <td {$styleBasicDate} > ".Utility::formatDateSINE($document->due_date,"Y-m-d")." </td>
                      <td {$styleBasicDate} > ".DateManagement::howManyDaysBetween($document->due_date, $date)." </td>
                      <td {$styleBasicNum} > ".Yii::app()->format->format_decimal($document->soa_next)." </td>
                      <td {$styleBasicDate} > ".Utility::formatDateSINE($document->due_date_next,"Y-m-d")." </td>
                      <td {$styleBasicDate} > ".$dueDaysNext." </td>
                      <td {$styleNumberRow} >{$pos}</td>
                  </tr>";  
        }
         $body.=" <tr>
                      <td {$styleNull} colspan='3'></td>
                      <td {$styleDatePC} >".Yii::app()->format->format_decimal($last_pago_cobro)."</td>
                      <td {$styleNull} ></td>
                      <td {$styleSoaDue} >".Yii::app()->format->format_decimal($soa)."</td>
                      <td {$styleNull} ></td>
                      <td {$styleNull} ></td>
                      <td {$styleSoaNext} >".Yii::app()->format->format_decimal($soa_next)."</td>
                      <td {$styleNull} ></td>
                      <td {$styleNull} ></td>
                      <td {$styleNull} ></td>
                  </tr>
                  </table>";
          return $body;
    }
    
    public static function defineStyleOld($dateModel,$date)
    {
        if(DateManagement::getNumberWeek($dateModel)==DateManagement::getNumberWeek($date)-1 && Utility::formatDateSINE($dateModel,"Y")==Utility::formatDateSINE($date,"Y"))        
            return "#FCD746";
        else
            return "#fff";
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
                 return "#6F7074";
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
    public static function getData($date,$intercompany=TRUE,$no_activity=TRUE,$typePaymentTerm,$PaymentTerm)
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
                                   /*active carrier*/       
                                    (SELECT id_managers 
                                    FROM carrier_managers 
                                    WHERE id_carrier IN(Select id from carrier where id_carrier_groups=cg.id)
                                      AND end_date IS NULL
                                      limit 1) AS active,
                                      
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
                   
                   /*el due date del soa*/
                   
                           {$due_date} WHERE d.date<='{$date}') AS due_date,
                               
                   /*el soa next*/

                     (SELECT (i.amount+(p.amount-n.amount)) AS amount
                      FROM (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document=9 AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id)) i,
                           (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(1,3,7,15) AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) ) p,
                           (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(2,4,8,14) AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) ) n) AS soa_next, 
                   
                   /*el due date del soa next*/

                           {$due_date}) AS due_date_next
                                
                           /*fin segmento para soas y due_dates*/
              FROM carrier_groups cg,
                   carrier c {$tableNext}
                   
              WHERE c.id_carrier_groups=cg.id 
                    AND group_leader=1
                    {$wherePaymentTerm}
                    {$intercompany}  
              ORDER BY cg.name ASC)activity {$no_activity}";
        return AccountingDocument::model()->findAllBySql($sql);
    }
}
?>
