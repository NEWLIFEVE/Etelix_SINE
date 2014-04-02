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
    public static function report($date,$intercompany,$noActivity,$typePaymentTerm,$paymentTerm)
    {
        $carrierGroups=CarrierGroups::getAllGroups();
            $seg=count($carrierGroups)*3;
            ini_set('max_execution_time', $seg);
        $styleNumberRow="style='border:1px solid silver;text-align:center;background:#83898F;color:white;'";
        $styleBasic="style='border:1px solid silver;text-align:left;color:#6F7074;'";
        $styleBasicCenter="style='border:1px solid silver;text-align: center;color:#6F7074;'";
        $styleBasicCenterLess="style='border:1px solid silver;text-align: center;color:red;'";
        $styleBasicCenterHigherDue="style='border:1px solid silver;text-align: center;color:#3466B4;'";
        $styleBasicCenterHigherNext="style='border:1px solid silver;text-align: center;color:#049C47;'";
        $styleBasicNumDue="style='border:1px solid silver;text-align:right;color:#6F7074;background:#DEECF7;'";
        $styleBasicDateDue="style='border:1px solid silver;text-align: center;background:#DEECF7;color:#6F7074;'";
        $styleBasicNumDueTwo="style='border:1px solid silver;text-align:right;color:#6F7074;background:#F0F6FA;'";
        $styleBasicDateDueTwo="style='border:1px solid silver;text-align: center;background:#F0F6FA;color:#6F7074;'";
        $styleBasicNumNext="style='border:1px solid silver;text-align:right;color:#6F7074;background:#DEF7DF;'";
        $styleBasicDateNext="style='border:1px solid silver;text-align: center;background:#DEF7DF;color:#6F7074;'";
        $styleBasicNumNextTwo="style='border:1px solid silver;text-align:right;color:#6F7074;background:#EDF8EE;'";
        $styleBasicDateNextTwo="style='border:1px solid silver;text-align: center;background:#EDF8EE;color:#6F7074;'";
        $styleActived="style='background:#F0950C;color:white;border:1px solid silver;text-align:center;'";
        $styleNull="style='border:0px solid white;text-align: left:'";
        $styleCarrier="style='border:1px solid silver;background:silver;text-align:center;color:white;'";
        $styleDatePC="style='border:1px solid silver;background:#06ACFA;text-align:center;color:white;'";
        $styleSoaDue="style='border:1px solid silver;background:#3466B4;text-align:center;color:white;white-space: nowrap;'";
        $styleSoaNext="style='border:1px solid silver;background:#049C47;text-align:center;color:white;white-space: nowrap;'";
        $styleDueDateD="style='border:1px solid silver;background:#3466B4;text-align:center;color:white;white-space: nowrap;'";
        $styleDueDateN="style='border:1px solid silver;background:#049C47;text-align:center;color:white;white-space: nowrap;'";
        $styleRowActiv="style='color:red;border:1px solid silver;text-align:center;font-size: x-large;padding-bottom: 0.5%;'";
        
        $firstWeekOne=DateManagement::firstOrLastDayWeek(DateManagement::calculateWeek("+1", $date), "first");
        $lastWeekOne=DateManagement::firstOrLastDayWeek(DateManagement::calculateWeek("+1", $date), "last");
        $firstWeekTwo=DateManagement::firstOrLastDayWeek(DateManagement::calculateWeek("+2", $date), "first");
        $lastWeekTwo=DateManagement::firstOrLastDayWeek(DateManagement::calculateWeek("+2", $date), "last");
        $firstWeekThree=DateManagement::firstOrLastDayWeek(DateManagement::calculateWeek("+3", $date), "first");
        $lastWeekThree=DateManagement::firstOrLastDayWeek(DateManagement::calculateWeek("+3", $date), "last");
        $firstWeekFour=DateManagement::firstOrLastDayWeek(DateManagement::calculateWeek("+4", $date), "first");
        $lastWeekFour=DateManagement::firstOrLastDayWeek(DateManagement::calculateWeek("+4", $date), "last");                               
        $last_payment_collect=$soaPrevTotal=$soaThisWeekTotal=$soaWeekOneTotal=$soaWeekTwoTotal=$soaWeekThreeTotal=$soaWeekFourTotal=0;
        $soaPrevLess=$soaThisWeekLess=$soaWeekOneLess=$soaWeekTwoLess=$soaWeekThreeLess=$soaWeekFourLess=0;
        $soaPrevHigher=$soaThisWeekHigher=$soaWeekOneHigher=$soaWeekTwoHigher=$soaWeekThreeHigher=$soaWeekFourHigher=0;
        $dueDaysDue=$dueDaysNext="";
        
        $documents=  self::getData($date,$intercompany,$noActivity,$typePaymentTerm,$paymentTerm);

        $body="<table>
                <tr>
                    <td colspan='4'>
                        <h1>SUMMARY  ".Reportes::defineNameExtra($paymentTerm,$typePaymentTerm)."</h1>
                    </td>
                    <td colspan='9'>  AL {$date} </td>
                <tr>
                    <td colspan='11'></td>
                </tr>
               </table>
               <table style='width: 100%;'>
                <tr>
                    <td colspan='5'></td>
                    <td {$styleSoaDue} colspan='2'> PREVIOUS </td>
                    <td {$styleSoaDue} colspan='2'> THIS WEEK </td>
                    <td></td>
                    <td {$styleSoaNext} colspan='2'> WEEK ".Utility::formatDateSINE($firstWeekOne,"d")."-".Utility::formatDateSINE($lastWeekOne,"d")."".Utility::formatDateSINE($lastWeekOne,"M")." </td>
                    <td {$styleSoaNext} colspan='2'> WEEK ".Utility::formatDateSINE($firstWeekTwo,"d")."-".Utility::formatDateSINE($lastWeekTwo,"d")."".Utility::formatDateSINE($lastWeekTwo,"M")." </td>
                    <td {$styleSoaNext} colspan='2'> WEEK ".Utility::formatDateSINE($firstWeekThree,"d")."-".Utility::formatDateSINE($lastWeekThree,"d")."".Utility::formatDateSINE($lastWeekThree,"M")." </td>
                    <td {$styleSoaNext} colspan='2'> WEEK ".Utility::formatDateSINE($firstWeekFour,"d")."-".Utility::formatDateSINE($lastWeekFour,"d")."".Utility::formatDateSINE($lastWeekFour,"M")." </td>
                    <td colspan='2'></td>
                </tr>
                <tr>
                    <td {$styleNumberRow} >N°</td>
                    <td {$styleCarrier} > CARRIER </td>
                    <td {$styleActived} > INACTIVE </td>
                    <td {$styleDatePC} > LAST(Pay/Coll) </td>
                    <td {$styleDatePC} > DATE(Pay/Coll) </td>
                    <td {$styleSoaDue} > SOA(DUE) </td>
                    <td {$styleDueDateD} > DUE DATE(D) </td>
                    <td {$styleSoaDue} > SOA(DUE) </td>
                    <td {$styleDueDateD} > DUE DATE(D) </td>
                    <td {$styleDueDateD} > DUE DAYS </td> 
                    <td {$styleSoaNext} > SOA(NEXT) </td>
                    <td {$styleDueDateN} > DUE DATE(N) </td>
                    <td {$styleSoaNext} > SOA(NEXT) </td>
                    <td {$styleDueDateN} > DUE DATE(N) </td>
                    <td {$styleSoaNext} > SOA(NEXT) </td>
                    <td {$styleDueDateN} > DUE DATE(N) </td>
                    <td {$styleSoaNext} > SOA(NEXT) </td>
                    <td {$styleDueDateN} > DUE DATE(N) </td>  
                    <td {$styleDueDateN} > DUE DAYS </td>
                    <td {$styleNumberRow} >N°</td>
                </tr>";
        foreach ($documents as $key => $document)
        { 
            $dueDaysNext=abs(DateManagement::howManyDaysBetween($document->due_date_next,$date));
            if($document->due_date_next==NULL||$document->due_date_next==$document->due_date)
                $dueDaysNext="0";
            $dueDaysDue=DateManagement::howManyDaysBetween($document->due_date, $date);
            if($dueDaysDue>365 || $dueDaysDue==NULL)
                $dueDaysDue="0";
            
            $styleCollPaym="style='border:1px solid silver;text-align: right;color:".Reportes::definePaymCollect($document,"style")."'";
            $styleOldDate="style='border:1px solid silver;text-align: center;color:#6F7074;background:".Reportes::defineStyleOld($document->last_date_pago_cobro, $date)."'";
            $pos=$key+1;
            $last_payment_collect+=$document->last_pago_cobro;
            
            $soaPrevLess=Reportes::defineAcums(Reportes::defineLessOrHigher($document->soa, FALSE),$document->due_date,$date, NULL, NULL,"prev",$soaPrevLess);
            $soaThisWeekLess=Reportes::defineAcumsThisWeek(Reportes::defineAcums(Reportes::defineLessOrHigher($document->soa, FALSE),$document->due_date,$date, NULL, NULL,NULL,$soaThisWeekLess),Reportes::defineLessOrHigher($document->soa_next, FALSE),$document->due_date_next, $date, $soaThisWeekLess);
            $soaWeekOneLess=Reportes::defineAcums(Reportes::defineLessOrHigher($document->soa_next, FALSE),$document->due_date_next,$date, $firstWeekOne, $lastWeekOne,NULL,$soaWeekOneLess);
            $soaWeekTwoLess=Reportes::defineAcums(Reportes::defineLessOrHigher($document->soa_next, FALSE),$document->due_date_next,$date, $firstWeekTwo, $lastWeekTwo,NULL,$soaWeekTwoLess);
            $soaWeekThreeLess=Reportes::defineAcums(Reportes::defineLessOrHigher($document->soa_next, FALSE),$document->due_date_next,$date, $firstWeekThree, $lastWeekThree,NULL,$soaWeekThreeLess);
            $soaWeekFourLess=Reportes::defineAcums(Reportes::defineLessOrHigher($document->soa_next, FALSE),$document->due_date_next,$date, $firstWeekFour, $lastWeekFour,NULL,$soaWeekFourLess);
            
            $soaPrevHigher=Reportes::defineAcums(Reportes::defineLessOrHigher($document->soa, TRUE),$document->due_date,$date, NULL, NULL,"prev",$soaPrevHigher);
            $soaThisWeekHigher=Reportes::defineAcumsThisWeek(Reportes::defineAcums(Reportes::defineLessOrHigher($document->soa, TRUE),$document->due_date,$date, NULL, NULL,NULL,$soaThisWeekHigher),Reportes::defineLessOrHigher($document->soa_next, TRUE),$document->due_date_next, $date, $soaThisWeekHigher);
            $soaWeekOneHigher=Reportes::defineAcums(Reportes::defineLessOrHigher($document->soa_next, TRUE),$document->due_date_next,$date, $firstWeekOne, $lastWeekOne,NULL,$soaWeekOneHigher);
            $soaWeekTwoHigher=Reportes::defineAcums(Reportes::defineLessOrHigher($document->soa_next, TRUE),$document->due_date_next,$date, $firstWeekTwo, $lastWeekTwo,NULL,$soaWeekTwoHigher);
            $soaWeekThreeHigher=Reportes::defineAcums(Reportes::defineLessOrHigher($document->soa_next, TRUE),$document->due_date_next,$date, $firstWeekThree, $lastWeekThree,NULL,$soaWeekThreeHigher);
            $soaWeekFourHigher=Reportes::defineAcums(Reportes::defineLessOrHigher($document->soa_next, TRUE),$document->due_date_next,$date, $firstWeekFour, $lastWeekFour,NULL,$soaWeekFourHigher);
            
            $soaPrevTotal=Reportes::defineAcums($document->soa,$document->due_date,$date, NULL, NULL,"prev",$soaPrevTotal);
            $soaThisWeekTotal=Reportes::defineAcumsThisWeek(Reportes::defineAcums($document->soa,$document->due_date,$date, NULL, NULL,NULL,$soaThisWeekTotal),$document->soa_next,$document->due_date_next, $date, $soaThisWeekTotal);
            $soaWeekOneTotal=Reportes::defineAcums($document->soa_next,$document->due_date_next,$date, $firstWeekOne, $lastWeekOne,NULL,$soaWeekOneTotal);
            $soaWeekTwoTotal=Reportes::defineAcums($document->soa_next,$document->due_date_next,$date, $firstWeekTwo, $lastWeekTwo,NULL,$soaWeekTwoTotal);
            $soaWeekThreeTotal=Reportes::defineAcums($document->soa_next,$document->due_date_next,$date, $firstWeekThree, $lastWeekThree,NULL,$soaWeekThreeTotal);
            $soaWeekFourTotal=Reportes::defineAcums($document->soa_next,$document->due_date_next,$date, $firstWeekFour, $lastWeekFour,NULL,$soaWeekFourTotal);

            $body.="<tr>
                      <td {$styleNumberRow} > {$pos} </td>
                      <td {$styleBasic} > ".$document->name." </td>
                      <td {$styleRowActiv} > ".Reportes::defineActive($document->active)." </td>
                      <td {$styleCollPaym} > ".Yii::app()->format->format_decimal(Reportes::definePaymCollect($document,"value"))." </td>
                      <td {$styleOldDate} > ".Utility::formatDateSINE($document->last_date_pago_cobro,"Y-m-d")." </td> 
                      <td {$styleBasicNumDue} > ".Yii::app()->format->format_decimal(Reportes::defineValueTD($document->soa,$document->due_date,$date, NULL, NULL,"prev"))." </td>
                      <td {$styleBasicDateDue} > ".Utility::formatDateSINE(Reportes::defineValueTD($document->due_date,$document->due_date,$date, NULL, NULL,"prev"),"Y-m-d")." </td>
                      <td {$styleBasicNumDueTwo} > ".Yii::app()->format->format_decimal(Reportes::defineValueThisNext(Reportes::defineValueTD($document->soa,$document->due_date,$date, NULL, NULL,NULL),$document->soa_next,$document->due_date_next, $date))." </td>
                      <td {$styleBasicDateDueTwo} > ".Utility::formatDateSINE(Reportes::defineValueThisNext(Reportes::defineValueTD($document->due_date,$document->due_date,$date, NULL, NULL,NULL),$document->due_date_next,$document->due_date_next, $date),"Y-m-d")." </td>
                      <td {$styleBasicDateDue} > {$dueDaysDue} </td>
                      <td {$styleBasicNumNext} > ".Yii::app()->format->format_decimal(Reportes::defineValueTD($document->soa_next,$document->due_date_next,$date, $firstWeekOne, $lastWeekOne,NULL))." </td>
                      <td {$styleBasicDateNext} > ".Utility::formatDateSINE(Reportes::defineValueTD($document->due_date_next,$document->due_date_next,$date, $firstWeekOne, $lastWeekOne,NULL),"Y-m-d")." </td>
                      <td {$styleBasicNumNextTwo} > ".Yii::app()->format->format_decimal(Reportes::defineValueTD($document->soa_next,$document->due_date_next,$date, $firstWeekTwo, $lastWeekTwo,NULL))." </td>
                      <td {$styleBasicDateNextTwo} > ".Utility::formatDateSINE(Reportes::defineValueTD($document->due_date_next,$document->due_date_next,$date, $firstWeekTwo, $lastWeekTwo,NULL),"Y-m-d")." </td>
                      <td {$styleBasicNumNext} > ".Yii::app()->format->format_decimal(Reportes::defineValueTD($document->soa_next,$document->due_date_next,$date, $firstWeekThree, $lastWeekThree,NULL))." </td>
                      <td {$styleBasicDateNext} > ".Utility::formatDateSINE(Reportes::defineValueTD($document->due_date_next,$document->due_date_next,$date, $firstWeekThree, $lastWeekThree,NULL),"Y-m-d")." </td>
                      <td {$styleBasicNumNextTwo} > ".Yii::app()->format->format_decimal(Reportes::defineValueTD($document->soa_next,$document->due_date_next,$date, $firstWeekFour, $lastWeekFour,NULL))." </td>
                      <td {$styleBasicDateNextTwo} > ".Utility::formatDateSINE(Reportes::defineValueTD($document->due_date_next,$document->due_date_next,$date, $firstWeekFour, $lastWeekFour,NULL),"Y-m-d")." </td>
                      <td {$styleBasicDateNext} > ".$dueDaysNext." </td>
                      <td {$styleNumberRow} >{$pos}</td>
                    </tr>"; 
        }
         $body.="<tr>
                    <td {$styleNull} colspan='3'></td>
                    <td {$styleDatePC} colspan='2'> PAYMENT/COLLECTION </td>
                    <td {$styleSoaDue} colspan='2'> SOA(DUE)PREVIOUS </td>
                    <td {$styleSoaDue} colspan='2'> SOA(DUE)THIS WEEK </td>
                    <td {$styleNull} ></td>
                    <td {$styleSoaNext} colspan='2'> WEEK ".Utility::formatDateSINE($firstWeekOne,"d")."-".Utility::formatDateSINE($lastWeekOne,"d")."".Utility::formatDateSINE($lastWeekOne,"M")." </td>
                    <td {$styleSoaNext} colspan='2'> WEEK ".Utility::formatDateSINE($firstWeekTwo,"d")."-".Utility::formatDateSINE($lastWeekTwo,"d")."".Utility::formatDateSINE($lastWeekTwo,"M")." </td>
                    <td {$styleSoaNext} colspan='2'> WEEK ".Utility::formatDateSINE($firstWeekThree,"d")."-".Utility::formatDateSINE($lastWeekThree,"d")."".Utility::formatDateSINE($lastWeekThree,"M")." </td>
                    <td {$styleSoaNext} colspan='2'> WEEK ".Utility::formatDateSINE($firstWeekFour,"d")."-".Utility::formatDateSINE($lastWeekFour,"d")."".Utility::formatDateSINE($lastWeekFour,"M")." </td>
                    <td {$styleNull} ></td>
                 </tr>";
         $body.="<tr>
                    <td {$styleNull} colspan='3'></td>
                    <td {$styleBasicCenter} colspan='2'>".Yii::app()->format->format_decimal($last_payment_collect)."</td>
                    <td {$styleBasicCenterHigherDue} colspan='2'>".Yii::app()->format->format_decimal($soaPrevHigher)."</td>
                    <td {$styleBasicCenterHigherDue} colspan='2'>".Yii::app()->format->format_decimal($soaThisWeekHigher)."</td>
                    <td {$styleNull} ></td>
                    <td {$styleBasicCenterHigherNext} colspan='2'>".Yii::app()->format->format_decimal($soaWeekOneHigher)."</td>
                    <td {$styleBasicCenterHigherNext} colspan='2'>".Yii::app()->format->format_decimal($soaWeekTwoHigher)."</td>
                    <td {$styleBasicCenterHigherNext} colspan='2'>".Yii::app()->format->format_decimal($soaWeekThreeHigher)."</td>
                    <td {$styleBasicCenterHigherNext} colspan='2'>".Yii::app()->format->format_decimal($soaWeekFourHigher)."</td>
                  </tr>";
         $body.="<tr>
                    <td {$styleNull} colspan='3'></td>
                    <td {$styleNull} colspan='2'></td>
                    <td {$styleBasicCenterLess} colspan='2'>".Yii::app()->format->format_decimal($soaPrevLess)."</td>
                    <td {$styleBasicCenterLess} colspan='2'>".Yii::app()->format->format_decimal($soaThisWeekLess)."</td>
                    <td {$styleNull} ></td>
                    <td {$styleBasicCenterLess} colspan='2'>".Yii::app()->format->format_decimal($soaWeekOneLess)."</td>
                    <td {$styleBasicCenterLess} colspan='2'>".Yii::app()->format->format_decimal($soaWeekTwoLess)."</td>
                    <td {$styleBasicCenterLess} colspan='2'>".Yii::app()->format->format_decimal($soaWeekThreeLess)."</td>
                    <td {$styleBasicCenterLess} colspan='2'>".Yii::app()->format->format_decimal($soaWeekFourLess)."</td>   
                 </tr>";   
         $body.="<tr>
                    <td {$styleNull} colspan='3'></td>
                    <td {$styleNull} colspan='2'></td>
                    <td {$styleSoaDue} colspan='2'>".Yii::app()->format->format_decimal($soaPrevTotal)."</td>
                    <td {$styleSoaDue} colspan='2'>".Yii::app()->format->format_decimal($soaThisWeekTotal)."</td>
                    <td {$styleNull} ></td>
                    <td {$styleSoaNext} colspan='2'>".Yii::app()->format->format_decimal($soaWeekOneTotal)."</td>
                    <td {$styleSoaNext} colspan='2'>".Yii::app()->format->format_decimal($soaWeekTwoTotal)."</td>
                    <td {$styleSoaNext} colspan='2'>".Yii::app()->format->format_decimal($soaWeekThreeTotal)."</td>
                    <td {$styleSoaNext} colspan='2'>".Yii::app()->format->format_decimal($soaWeekFourTotal)."</td>
                  </tr>
                 </table>";   
          return $body;
    }

    /**
     * Encargada de traer la data
     * @param date $date,$intercompany=TRUE,$no_activity=TRUE,$paymentTerm
     * @return array
     * @since 1.0
     * @access public
     */
    public static function getData($date,$intercompany=TRUE,$no_activity=TRUE,$typePaymentTerm,$paymentTerm)
    {
        if($intercompany)           $intercompany="";
        elseif($intercompany==FALSE) $intercompany="AND cg.id NOT IN(SELECT id FROM carrier_groups WHERE name IN('FULLREDPERU','R-ETELIX.COM PERU','CABINAS PERU'))";

        if($no_activity)           $no_activity="";
        elseif($no_activity==FALSE) $no_activity=" WHERE due_date IS NOT NULL";

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
                 (SELECT DISTINCT cg.id AS id, 
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
                    {$wherePaymentTerm}
                    {$intercompany}  
              ORDER BY cg.name ASC)activity {$no_activity} ";
        return AccountingDocument::model()->findAllBySql($sql);
    }
}
?>
