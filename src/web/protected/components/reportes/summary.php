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
        /*********************   AYUDA A AUMENTAR EL TIEMPO PARA GENERAR EL REPORTE CUANDO SON MUCHOS REGISTROS   **********************/
        $carrierGroups=CarrierGroups::getAllGroups();
            $seg=count($carrierGroups)*3;
            ini_set('max_execution_time', $seg);
        /***************************                      SECCION DE ESTILOS GENERALES                       ***************************/
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
        $styleNull="style='border:1px solid white!important;text-align: left:'";
        $styleCarrier="style='border:1px solid silver;background:silver;text-align:center;color:white;'";
        $styleDatePCPrev="style='border:1px solid silver;background:#C37881;text-align:center;color:white;'";
        $styleDatePCLast="style='border:1px solid silver;background:#248CB4;text-align:center;color:white;'";
        $styleDatePC="style='border:1px solid silver;background:#06ACFA;text-align:center;color:white;'";
        $styleSoaDue="style='border:1px solid silver;background:#3466B4;text-align:center;color:white;white-space: nowrap;'";
        $styleSoaNext="style='border:1px solid silver;background:#049C47;text-align:center;color:white;white-space: nowrap;'";
        $styleDueDateD="style='border:1px solid silver;background:#3466B4;text-align:center;color:white;white-space: nowrap;'";
        $styleDueDateN="style='border:1px solid silver;background:#049C47;text-align:center;color:white;white-space: nowrap;'";
        $styleRowActiv="style='color:red;border:1px solid silver;text-align:center;font-size: x-large;padding-bottom: 0.5%;'";
        
        /***************************          SECCION ENCARGADA DE DEFINIR LOS HEAD PARA SOAS NEXT           ***************************/
        $firstWeekOne=DateManagement::firstOrLastDayWeek(DateManagement::calculateWeek("+1", $date), "first");
        $lastWeekOne=DateManagement::firstOrLastDayWeek(DateManagement::calculateWeek("+1", $date), "last");
        $firstWeekTwo=DateManagement::firstOrLastDayWeek(DateManagement::calculateWeek("+2", $date), "first");
        $lastWeekTwo=DateManagement::firstOrLastDayWeek(DateManagement::calculateWeek("+2", $date), "last");
        $firstWeekThree=DateManagement::firstOrLastDayWeek(DateManagement::calculateWeek("+3", $date), "first");
        $lastWeekThree=DateManagement::firstOrLastDayWeek(DateManagement::calculateWeek("+3", $date), "last");
        $firstWeekFour=DateManagement::firstOrLastDayWeek(DateManagement::calculateWeek("+4", $date), "first");
        $lastWeekFour=DateManagement::firstOrLastDayWeek(DateManagement::calculateWeek("+4", $date), "last");  
        /***************************      SECCION ENCARGADA DE INICIALIZAR VARIABLES NUMERICAS Y DATE        ***************************/
        $thisPaymentCollectLess=$PrevPaymentCollectLess=$lastWeekPaymentCollectLess=$thisPaymentCollectHigher=$PrevPaymentCollectHigher=
        $lastWeekPaymentCollectHigher=$thisPaymentCollect=$PrevPaymentCollect=$lastWeekPaymentCollect=$soaPrevTotal=$soaThisWeekTotal=
        $soaWeekOneTotal=$soaWeekTwoTotal=$soaWeekThreeTotal=$soaWeekFourTotal=$soaPrevLess=$soaThisWeekLess=$soaWeekOneLess=$soaWeekTwoLess=
        $soaWeekThreeLess=$soaWeekFourLess=$soaPrevHigher=$soaThisWeekHigher=$soaWeekOneHigher=$soaWeekTwoHigher=$soaWeekThreeHigher=
        $soaWeekFourHigher=$balanceTotal=$soaThisWeek=$soaProvisionedLess=$soaProvisionedHigher=$soaProvisionedTotal=0;
        $dueDaysDue=$dueDaysNext="";
        
        /***************************       SE ENCARGA DE LLAMAR EL MODELO GENERAL PARA ARMAR LA TABLA        ***************************/
        $documents=  self::getData($date,$intercompany,$noActivity,$typePaymentTerm,$paymentTerm);
        /***************************                  DEFINE EL HEAD PRINCIPAL PARA LA TABLA                 ***************************/
        $body="<table>
                <tr>
                    <td colspan='4'>
                        <h1>SUMMARY  ".Reportes::defineNameExtra($paymentTerm,$typePaymentTerm,NULL)."</h1>
                    </td>
                    <td colspan='9'>  AL {$date} </td>
                <tr>
                    <td colspan='11'></td>
                </tr>
               </table>
               <table style='width: 100%;'>
                <tr>
                    <td colspan='3'></td>
                    <td {$styleDatePCLast} colspan='6'> RECEIPTS AND PAYMENTS </td>
                    <td > &nbsp; &nbsp; </td>
                    <td {$styleSoaNext} colspan='13'> SOAs </td>
                    <td colspan='3'> </td>
                </tr>
                <tr>
                    <td colspan='3'></td>
                    <td {$styleDatePCPrev} colspan='2'> PREVIOUS </td>
                    <td {$styleDatePCLast} colspan='2'> LAST WEEK</td>
                    <td {$styleDatePC} colspan='2'> THIS WEEK </td>
                    <td > </td>
                    <td {$styleSoaDue} colspan='2'> PREVIOUS </td>
                    <td {$styleSoaDue} colspan='2'> THIS WEEK </td>
                    
                    <td {$styleSoaDue} ></td>
                    <td {$styleSoaNext} colspan='2'> WEEK ".Utility::formatDateSINE($firstWeekOne,"d")."-".Utility::formatDateSINE($lastWeekOne,"d")."".Utility::formatDateSINE($lastWeekOne,"M")." </td>
                    <td {$styleSoaNext} colspan='2'> WEEK ".Utility::formatDateSINE($firstWeekTwo,"d")."-".Utility::formatDateSINE($lastWeekTwo,"d")."".Utility::formatDateSINE($lastWeekTwo,"M")." </td>
                    <td {$styleSoaNext} colspan='2'> WEEK ".Utility::formatDateSINE($firstWeekThree,"d")."-".Utility::formatDateSINE($lastWeekThree,"d")."".Utility::formatDateSINE($lastWeekThree,"M")." </td>
                    <td {$styleSoaNext} colspan='2'> WEEK ".Utility::formatDateSINE($firstWeekFour,"d")."-".Utility::formatDateSINE($lastWeekFour,"d")."".Utility::formatDateSINE($lastWeekFour,"M")." </td>
                    <td colspan='3'></td>
                </tr>
                <tr>
                    <td {$styleNumberRow} >N°</td>
                    <td {$styleCarrier} > CARRIER </td>
                    <td {$styleActived} > INACTIVE </td>
                    <td {$styleDatePCPrev} > AMOUNT(Pay/Coll) </td>
                    <td {$styleDatePCPrev} > DATE(Pay/Coll) </td>
                    <td {$styleDatePCLast} > AMOUNT(Pay/Coll) </td>
                    <td {$styleDatePCLast} > DATE(Pay/Coll) </td>
                    <td {$styleDatePC} > AMOUNT(Pay/Coll) </td>
                    <td {$styleDatePC} > DATE(Pay/Coll) </td>
                    <td  >  </td>
                    <td {$styleSoaDue} > SOA(DUE) </td>
                    <td {$styleDueDateD} > DUE DATE(D) </td>
                    <td {$styleSoaDue} > SOA(DUE) </td>
                    <td {$styleDueDateD} > DUE DATE(D) </td>
                     
                    <td {$styleSoaDue} > DUE DAYS </td>
                    <td {$styleSoaNext} > SOA(NEXT) </td>
                    <td {$styleDueDateN} > DUE DATE(N) </td>
                    <td {$styleSoaNext} > SOA(NEXT) </td>
                    <td {$styleDueDateN} > DUE DATE(N) </td>
                    <td {$styleSoaNext} > SOA(NEXT) </td>
                    <td {$styleDueDateN} > DUE DATE(N) </td>
                    <td {$styleSoaNext} > SOA(NEXT) </td>
                    <td {$styleDueDateN} > DUE DATE(N) </td>  
                    <td {$styleDueDateN} > DUE DAYS </td>
                    <td {$styleDueDateN} > SOA PROVISIONED </td>
                    <td {$styleNumberRow} >N°</td>
                </tr>";
        foreach ($documents as $key => $document)
        { 
            /*****************  DETERMINA SI SE DEBEN MOSTRAR DUE DATE DEPENDIENDO DEL TIEMPO QUE TENGA SIN ACTIVIDAD *******************/
            $dueDaysNext=abs(DateManagement::howManyDaysBetween($document->due_date_next,$date));
            if($document->due_date_next==NULL||$document->due_date_next==$document->due_date)
                $dueDaysNext="0";
            $dueDaysDue=DateManagement::howManyDaysBetween($document->due_date, $date);
            if($dueDaysDue>365 || $dueDaysDue==NULL)
                $dueDaysDue="0";
            /***************************               DEFINE ESTILOS PARA LAS COLUMNAS DE PAGOS              ***************************/
            $styleCollPaymPrev="style='border:1px solid silver;text-align: right;color:".Reportes::definePaymCollect($document->previous_pago_cobro, $document->type_c_p_previous, "style").";'";
            $styleCollPaymLast="style='border:1px solid silver;text-align: right;color:".Reportes::definePaymCollect($document->last_week_pago_cobro, $document->type_c_p_last_week, "style").";background:#DEECF7;'";
            $styleCollPaym="style='border:1px solid silver;text-align: right;color:".Reportes::definePaymCollect($document->last_pago_cobro, $document->type_c_p, "style").";'";

            $pos=$key+1;
            /***************************                    DEFINE ACUMULADOS PARA LOS PAGOS                  ***************************/
            $PrevPaymentCollectLess+=Reportes::defineLessOrHigher(Reportes::definePaymCollect($document->previous_pago_cobro, $document->type_c_p_previous, "value"), FALSE);
            $lastWeekPaymentCollectLess+=Reportes::defineLessOrHigher(Reportes::definePaymCollect($document->last_week_pago_cobro, $document->type_c_p_last_week, "value"), FALSE);
            $thisPaymentCollectLess+=Reportes::defineLessOrHigher(Reportes::definePaymCollect($document->last_pago_cobro, $document->type_c_p, "value"), FALSE);
            
            $PrevPaymentCollectHigher+=Reportes::defineLessOrHigher(Reportes::definePaymCollect($document->previous_pago_cobro, $document->type_c_p_previous, "value"), TRUE);
            $lastWeekPaymentCollectHigher+=Reportes::defineLessOrHigher(Reportes::definePaymCollect($document->last_week_pago_cobro, $document->type_c_p_last_week, "value"), TRUE);
            $thisPaymentCollectHigher+=Reportes::defineLessOrHigher(Reportes::definePaymCollect($document->last_pago_cobro, $document->type_c_p, "value"), TRUE);

//            $PrevPaymentCollect+=$document->previous_pago_cobro;
//            $lastWeekPaymentCollect+=$document->last_week_pago_cobro;
//            $thisPaymentCollect+=$document->last_pago_cobro;
            /***************************                  DEFINE ACUMULADOS PARA SOAS NEGATIVOS               ***************************/
            $soaPrevLess=Reportes::defineAcums(Reportes::defineLessOrHigher($document->soa, FALSE),$document->due_date,$date, NULL, NULL,"prev",$soaPrevLess);
            $soaThisWeekLess=Reportes::defineAcumsThisWeek(Reportes::defineAcums(Reportes::defineLessOrHigher($document->soa, FALSE),$document->due_date,$date, NULL, NULL,NULL,$soaThisWeekLess),Reportes::defineLessOrHigher($document->soa_next, FALSE),$document->due_date_next, $date, $soaThisWeekLess);
            $soaWeekOneLess=Reportes::defineAcums(Reportes::defineLessOrHigher($document->soa_next, FALSE),$document->due_date_next,$date, $firstWeekOne, $lastWeekOne,NULL,$soaWeekOneLess);
            $soaWeekTwoLess=Reportes::defineAcums(Reportes::defineLessOrHigher($document->soa_next, FALSE),$document->due_date_next,$date, $firstWeekTwo, $lastWeekTwo,NULL,$soaWeekTwoLess);
            $soaWeekThreeLess=Reportes::defineAcums(Reportes::defineLessOrHigher($document->soa_next, FALSE),$document->due_date_next,$date, $firstWeekThree, $lastWeekThree,NULL,$soaWeekThreeLess);
            $soaWeekFourLess=Reportes::defineAcums(Reportes::defineLessOrHigher($document->soa_next, FALSE),$document->due_date_next,$date, $firstWeekFour, $lastWeekFour,NULL,$soaWeekFourLess);
            $soaProvisionedLess+=Reportes::defineLessOrHigher($document->soa_provisioned, FALSE);
            /***************************                  DEFINE ACUMULADOS PARA SOAS POSITIVOS               ***************************/
            $soaPrevHigher=Reportes::defineAcums(Reportes::defineLessOrHigher($document->soa, TRUE),$document->due_date,$date, NULL, NULL,"prev",$soaPrevHigher);
            $soaThisWeekHigher=Reportes::defineAcumsThisWeek(Reportes::defineAcums(Reportes::defineLessOrHigher($document->soa, TRUE),$document->due_date,$date, NULL, NULL,NULL,$soaThisWeekHigher),Reportes::defineLessOrHigher($document->soa_next, TRUE),$document->due_date_next, $date, $soaThisWeekHigher);
            $soaWeekOneHigher=Reportes::defineAcums(Reportes::defineLessOrHigher($document->soa_next, TRUE),$document->due_date_next,$date, $firstWeekOne, $lastWeekOne,NULL,$soaWeekOneHigher);
            $soaWeekTwoHigher=Reportes::defineAcums(Reportes::defineLessOrHigher($document->soa_next, TRUE),$document->due_date_next,$date, $firstWeekTwo, $lastWeekTwo,NULL,$soaWeekTwoHigher);
            $soaWeekThreeHigher=Reportes::defineAcums(Reportes::defineLessOrHigher($document->soa_next, TRUE),$document->due_date_next,$date, $firstWeekThree, $lastWeekThree,NULL,$soaWeekThreeHigher);
            $soaWeekFourHigher=Reportes::defineAcums(Reportes::defineLessOrHigher($document->soa_next, TRUE),$document->due_date_next,$date, $firstWeekFour, $lastWeekFour,NULL,$soaWeekFourHigher);
            $soaProvisionedHigher+=Reportes::defineLessOrHigher($document->soa_provisioned, TRUE);
            /***************************                  DEFINE ACUMULADOS PARA SOAS TOTALES                 ***************************/
            $soaPrevTotal=Reportes::defineAcums($document->soa,$document->due_date,$date, NULL, NULL,"prev",$soaPrevTotal);
            $soaThisWeekTotal=Reportes::defineAcumsThisWeek(Reportes::defineAcums($document->soa,$document->due_date,$date, NULL, NULL,NULL,$soaThisWeekTotal),$document->soa_next,$document->due_date_next, $date, $soaThisWeekTotal);
            $soaWeekOneTotal=Reportes::defineAcums($document->soa_next,$document->due_date_next,$date, $firstWeekOne, $lastWeekOne,NULL,$soaWeekOneTotal);
            $soaWeekTwoTotal=Reportes::defineAcums($document->soa_next,$document->due_date_next,$date, $firstWeekTwo, $lastWeekTwo,NULL,$soaWeekTwoTotal);
            $soaWeekThreeTotal=Reportes::defineAcums($document->soa_next,$document->due_date_next,$date, $firstWeekThree, $lastWeekThree,NULL,$soaWeekThreeTotal);
            $soaWeekFourTotal=Reportes::defineAcums($document->soa_next,$document->due_date_next,$date, $firstWeekFour, $lastWeekFour,NULL,$soaWeekFourTotal);
            /***************************         DEFINE ACUMULADOS PARA SOAS PROVISIONADO Y BALANCE           ***************************/
            $soaProvisionedTotal+=$document->soa_provisioned;
            $balanceTotal+=$document->balance;
            /******* DEFINE SOA ACTUAL (La particularidad de este caso es que debe determinar el THIS SOA es SOA DUE o SOA NEXT) ********/
            $soaThisWeek=Reportes::defineValueThisNext(Reportes::defineValueTD($document->soa,$document->due_date,$date, NULL, NULL,NULL),$document->soa_next,$document->due_date_next, $date);
            /***************************            SE ENCARGA DE ARMAR EL CONTENIDO DE LA TABLA              ***************************/
            $body.="<tr>
                      <td {$styleNumberRow} > {$pos} </td>
                      <td {$styleBasic} > ".$document->name." </td>
                      <td {$styleRowActiv} > ".Reportes::defineActive($document->active)." </td>
                          
                      <td {$styleCollPaymPrev} > ".Yii::app()->format->format_decimal(Reportes::definePaymCollect($document->previous_pago_cobro, $document->type_c_p_previous, "value"))." </td>
                      <td {$styleBasic} > ".Utility::formatDateSINE($document->previous_date_pago_cobro,"Y-m-d")." </td> 
                      <td {$styleCollPaymLast} > ".Yii::app()->format->format_decimal(Reportes::definePaymCollect($document->last_week_pago_cobro, $document->type_c_p_last_week, "value"))." </td>
                      <td {$styleBasicDateDue} > ".Utility::formatDateSINE($document->last_week_date_pago_cobro,"Y-m-d")." </td> 
                      <td {$styleCollPaym} > ".Yii::app()->format->format_decimal(Reportes::definePaymCollect($document->last_pago_cobro, $document->type_c_p, "value"))." </td>
                      <td {$styleBasic} > ".Utility::formatDateSINE($document->last_date_pago_cobro,"Y-m-d")." </td> 
                      <td  >  </td>    
                      <td {$styleBasicNumDue} > ".Yii::app()->format->format_decimal(Reportes::defineValueTD($document->soa,$document->due_date,$date, NULL, NULL,"prev"))." </td>
                      <td {$styleBasicDateDue} > ".Utility::formatDateSINE(Reportes::defineValueTD($document->due_date,$document->due_date,$date, NULL, NULL,"prev"),"Y-m-d")." </td>
                      <td {$styleBasicNumDueTwo} > ".Yii::app()->format->format_decimal($soaThisWeek)." </td>
                      <td {$styleBasicDateDueTwo} > ".Utility::formatDateSINE(Reportes::defineValueThisNext(Reportes::defineValueTD($document->due_date,$document->due_date,$date, NULL, NULL,NULL),$document->due_date_next,$document->due_date_next, $date),"Y-m-d")." </td>
                      
                      <td {$styleCollPaym} > {$dueDaysDue} </td>    
                      <td {$styleBasicNumNext} > ".Reportes::defineIncremental( $soaThisWeek, Reportes::defineValueTD($document->soa_next,$document->due_date_next,$date, $firstWeekOne, $lastWeekOne,NULL) )." </td>
                      <td {$styleBasicDateNext} > ".Utility::formatDateSINE(Reportes::defineValueTD($document->due_date_next,$document->due_date_next,$date, $firstWeekOne, $lastWeekOne,NULL),"Y-m-d")." </td>
                      <td {$styleBasicNumNextTwo} > ".Reportes::defineIncremental( $soaThisWeek, Reportes::defineValueTD($document->soa_next,$document->due_date_next,$date, $firstWeekTwo, $lastWeekTwo,NULL) )." </td>
                      <td {$styleBasicDateNextTwo} > ".Utility::formatDateSINE(Reportes::defineValueTD($document->due_date_next,$document->due_date_next,$date, $firstWeekTwo, $lastWeekTwo,NULL),"Y-m-d")." </td>
                      <td {$styleBasicNumNext} > ".Reportes::defineIncremental( $soaThisWeek, Reportes::defineValueTD($document->soa_next,$document->due_date_next,$date, $firstWeekThree, $lastWeekThree,NULL) )." </td>
                      <td {$styleBasicDateNext} > ".Utility::formatDateSINE(Reportes::defineValueTD($document->due_date_next,$document->due_date_next,$date, $firstWeekThree, $lastWeekThree,NULL),"Y-m-d")." </td>
                      <td {$styleBasicNumNextTwo} > ".Reportes::defineIncremental( $soaThisWeek, Reportes::defineValueTD($document->soa_next,$document->due_date_next,$date, $firstWeekFour, $lastWeekFour,NULL) )." </td>
                      <td {$styleBasicDateNextTwo} > ".Utility::formatDateSINE(Reportes::defineValueTD($document->due_date_next,$document->due_date_next,$date, $firstWeekFour, $lastWeekFour,NULL),"Y-m-d")." </td>
                      <td {$styleBasicDateNext} > ".$dueDaysNext." </td>
                      <td {$styleBasicDateNextTwo} > ".Reportes::defineIncremental( $soaThisWeek,$document->soa_provisioned )." </td>
                      <td {$styleNumberRow} >{$pos}</td>
                    </tr>";               
        }
        /***************************                            DEFINE EL HEAD INFERIOR                        ***************************/
         $body.="<tr>
                    <td {$styleNull} colspan='3'></td>
                    <td {$styleDatePCPrev} colspan='2'> PREV PAYMENT/COLLECTION </td>
                    <td {$styleDatePCLast} colspan='2'> LAST PAYMENT/COLLECTION </td>
                    <td {$styleDatePC} colspan='2'> WEEK PAYMENT/COLLECTION </td>
                    <td  >  </td>
                    <td {$styleSoaDue} colspan='2'> SOA(DUE)PREVIOUS </td>
                    <td {$styleSoaDue} colspan='2'> SOA(DUE)THIS WEEK </td>                   
                    
                    <td {$styleNull} ></td> 
                    <td {$styleSoaNext} colspan='2'> WEEK ".Utility::formatDateSINE($firstWeekOne,"d")."-".Utility::formatDateSINE($lastWeekOne,"d")."".Utility::formatDateSINE($lastWeekOne,"M")." </td>
                    <td {$styleSoaNext} colspan='2'> WEEK ".Utility::formatDateSINE($firstWeekTwo,"d")."-".Utility::formatDateSINE($lastWeekTwo,"d")."".Utility::formatDateSINE($lastWeekTwo,"M")." </td>
                    <td {$styleSoaNext} colspan='2'> WEEK ".Utility::formatDateSINE($firstWeekThree,"d")."-".Utility::formatDateSINE($lastWeekThree,"d")."".Utility::formatDateSINE($lastWeekThree,"M")." </td>
                    <td {$styleSoaNext} colspan='2'> WEEK ".Utility::formatDateSINE($firstWeekFour,"d")."-".Utility::formatDateSINE($lastWeekFour,"d")."".Utility::formatDateSINE($lastWeekFour,"M")." </td>
                    <td {$styleNull}></td>
                    <td {$styleDueDateN} > SOA PROVISIONED </td>
                    <td {$styleNull} ></td>
                 </tr>";
         /******************  EN SU MAYORIA SE ENCARGA DE ARMAR LOS MONTOS NEGATIVOS DE SOAS PARA EL HEAD INFERIOR   ********************/
         $body.="<tr>
                    <td {$styleNull} colspan='3'></td>
                    <td {$styleBasicCenterHigherDue} colspan='2'>".Yii::app()->format->format_decimal($PrevPaymentCollectHigher)."</td>
                    <td {$styleBasicCenterHigherDue} colspan='2'>".Yii::app()->format->format_decimal($lastWeekPaymentCollectHigher)."</td>
                    <td {$styleBasicCenterHigherDue} colspan='2'>".Yii::app()->format->format_decimal($thisPaymentCollectHigher)."</td>
                    <td  > </td>
                    <td {$styleBasicCenterHigherDue} colspan='2'>".Yii::app()->format->format_decimal($soaPrevHigher)."</td>
                    <td {$styleBasicCenterHigherDue} colspan='2'>".Yii::app()->format->format_decimal($soaThisWeekHigher)."</td>
                    
                    <td {$styleNull}></td>
                    <td {$styleBasicCenterHigherNext} colspan='2'>".Yii::app()->format->format_decimal($soaWeekOneHigher)."</td>
                    <td {$styleBasicCenterHigherNext} colspan='2'>".Yii::app()->format->format_decimal($soaWeekTwoHigher)."</td>
                    <td {$styleBasicCenterHigherNext} colspan='2'>".Yii::app()->format->format_decimal($soaWeekThreeHigher)."</td>
                    <td {$styleBasicCenterHigherNext} colspan='2'>".Yii::app()->format->format_decimal($soaWeekFourHigher)."</td>
                    <td {$styleNull} ></td>
                    <td {$styleBasicCenterHigherNext} >".Yii::app()->format->format_decimal($soaProvisionedHigher)."</td>
                  </tr>";
         /************************     ENCARGA DE ARMAR LOS MONTOS POSITIVOS DE SOAS PARA EL HEAD INFERIOR     **************************/           
         $body.="<tr>
                    <td {$styleNull} colspan='3'></td>
                    <td {$styleBasicCenterLess} colspan='2'>".Yii::app()->format->format_decimal($PrevPaymentCollectLess)."</td>
                    <td {$styleBasicCenterLess} colspan='2'>".Yii::app()->format->format_decimal($lastWeekPaymentCollectLess)."</td>
                    <td {$styleBasicCenterLess} colspan='2'>".Yii::app()->format->format_decimal($thisPaymentCollectLess)."</td>
                    <td  > </td>
                    <td {$styleBasicCenterLess} colspan='2'>".Yii::app()->format->format_decimal($soaPrevLess)."</td>
                    <td {$styleBasicCenterLess} colspan='2'>".Yii::app()->format->format_decimal($soaThisWeekLess)."</td>
                    <td {$styleNull} ></td>
                    <td {$styleBasicCenterLess} colspan='2'>".Yii::app()->format->format_decimal($soaWeekOneLess)."</td>
                    <td {$styleBasicCenterLess} colspan='2'>".Yii::app()->format->format_decimal($soaWeekTwoLess)."</td>
                    <td {$styleBasicCenterLess} colspan='2'>".Yii::app()->format->format_decimal($soaWeekThreeLess)."</td>
                    <td {$styleBasicCenterLess} colspan='2'>".Yii::app()->format->format_decimal($soaWeekFourLess)."</td>  
                    <td {$styleNull} ></td>
                    <td {$styleBasicCenterLess} >".Yii::app()->format->format_decimal($soaProvisionedLess)."</td>
                 </tr>";
         /************************      ENCARGA DE ARMAR LOS MONTOS TOTALES DE SOAS PARA EL HEAD INFERIOR      **************************/             
         $body.="<tr>
                    <td {$styleNull} colspan='3'></td>
                    <td {$styleDatePCPrev} colspan='2'>".Yii::app()->format->format_decimal($PrevPaymentCollectHigher - ($PrevPaymentCollectLess*-1))."</td>
                    <td {$styleDatePCLast} colspan='2'>".Yii::app()->format->format_decimal($lastWeekPaymentCollectHigher - ($lastWeekPaymentCollectLess*-1))."</td>
                    <td {$styleDatePC} colspan='2'>".Yii::app()->format->format_decimal($thisPaymentCollectHigher - ($thisPaymentCollectLess*-1))."</td>
                    <td  > </td>
                    <td {$styleSoaDue} colspan='2'>".Yii::app()->format->format_decimal($soaPrevTotal)."</td>
                    <td {$styleSoaDue} colspan='2'>".Yii::app()->format->format_decimal($soaThisWeekTotal)."</td>
                    <td {$styleNull} ></td>
                    <td {$styleSoaNext} colspan='2'>".Yii::app()->format->format_decimal($soaWeekOneTotal)."</td>
                    <td {$styleSoaNext} colspan='2'>".Yii::app()->format->format_decimal($soaWeekTwoTotal)."</td>
                    <td {$styleSoaNext} colspan='2'>".Yii::app()->format->format_decimal($soaWeekThreeTotal)."</td>
                    <td {$styleSoaNext} colspan='2'>".Yii::app()->format->format_decimal($soaWeekFourTotal)."</td>
                    <td {$styleNull} ></td>
                    <td {$styleSoaNext} >".Yii::app()->format->format_decimal($soaProvisionedTotal)."</td>
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
        /*Monto  pago o cobro*/
        $paymentCollectAmount="(select amount 
                                from accounting_document
                                where id_type_accounting_document IN(select id from type_accounting_document where name in ('Pago','Cobro'))
                                  and id_carrier IN (SELECT id FROM carrier WHERE id_carrier_groups=cg.id)";
        /*monto  pago o cobro*/ 
        $paymentCollectDate="(select max(issue_date) as date
                              from accounting_document
                              where id_type_accounting_document IN(select id from type_accounting_document where name in ('Pago','Cobro'))
                                and id_carrier IN (SELECT id FROM carrier WHERE id_carrier_groups=cg.id)";
        /*tipo (pago o cobro)*/
        $paymentCollectType="(select t.name 
                              from accounting_document a, type_accounting_document t
                              where id_type_accounting_document IN(select id from type_accounting_document where name in ('Pago','Cobro'))
                                and id_carrier IN (SELECT id FROM carrier WHERE id_carrier_groups=cg.id)";
        
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
                          WHERE id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) AND id_type_accounting_document=2 ) d ";/* esto es lo que continua en el caso de due_date= WHERE d.date<='{$date}')*/    

        $sql="/*filtro el due_date null*/ 
              SELECT * FROM 
                 (SELECT 
                    DISTINCT cg.id AS id, 
               /*-----------------------------------------------------------------------------------------------------------*/   
                  /*active carrier*/       
                   (SELECT id_managers 
                    FROM carrier_managers 
                    WHERE id_carrier IN(Select id from carrier where id_carrier_groups=cg.id)
                      AND end_date IS NULL
                      limit 1) AS active,
               /*-----------------------------------------------------------------------------------------------------------*/      
                  /*monto del pago o cobro en semanas previas a la actual y la pasada*/
                   {$paymentCollectAmount} and issue_date<='".DateManagement::firstOrLastDayWeek(DateManagement::calculateWeek("-2", $date), "last")."' order by issue_date desc LIMIT 1) AS previous_pago_cobro,

                  /*fecha del pago o cobro en semanas previas a la actual y la pasada*/ 
                   {$paymentCollectDate} and issue_date<='".DateManagement::firstOrLastDayWeek(DateManagement::calculateWeek("-2", $date), "last")."') AS previous_date_pago_cobro ,

                  /*tipo (pago o cobro) en semanas previas a la actual y la pasada*/
                   {$paymentCollectType} and issue_date<='".DateManagement::firstOrLastDayWeek(DateManagement::calculateWeek("-2", $date), "last")."' and id_type_accounting_document=t.id order by issue_date desc LIMIT 1) AS type_c_p_previous,
               /*-----------------------------------------------------------------------------------------------------------*/
                  /* Monto del pago o cobro para la semana anterior*/
                   {$paymentCollectAmount} and issue_date>='".DateManagement::firstOrLastDayWeek(DateManagement::calculateWeek("-1", $date), "first")."' and issue_date<='".DateManagement::firstOrLastDayWeek(DateManagement::calculateWeek("-1", $date), "last")."' order by issue_date desc LIMIT 1) AS last_week_pago_cobro,

                  /*fecha del pago o cobro semana anterior*/ 
                   {$paymentCollectDate} and issue_date>='".DateManagement::firstOrLastDayWeek(DateManagement::calculateWeek("-1", $date), "first")."' and issue_date<='".DateManagement::firstOrLastDayWeek(DateManagement::calculateWeek("-1", $date), "last")."') AS last_week_date_pago_cobro ,

                  /*tipo (pago o cobro) semana anterior*/
                   {$paymentCollectType} and issue_date>='".DateManagement::firstOrLastDayWeek(DateManagement::calculateWeek("-1", $date), "first")."' and issue_date<='".DateManagement::firstOrLastDayWeek(DateManagement::calculateWeek("-1", $date), "last")."' and id_type_accounting_document=t.id order by issue_date desc LIMIT 1) AS type_c_p_last_week,

               /*-----------------------------------------------------------------------------------------------------------*/
                  /*Monto del ultimo pago o cobro*/
                   {$paymentCollectAmount} and issue_date>='".DateManagement::firstOrLastDayWeek($date, "first")."' order by issue_date desc LIMIT 1) AS last_pago_cobro,

                  /*monto del ultimo pago o cobro*/ 
                   {$paymentCollectDate} and issue_date>='".DateManagement::firstOrLastDayWeek($date, "first")."') AS last_date_pago_cobro ,

                  /*tipo (pago o cobro)*/
                   {$paymentCollectType} and issue_date>='".DateManagement::firstOrLastDayWeek($date, "first")."' and id_type_accounting_document=t.id order by issue_date desc LIMIT 1) AS type_c_p,
               /*-----------------------------------------------------------------------------------------------------------*/
                  /*El Nombre del grupo*/ 
                    cg.name AS name,
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
                     {$due_date} WHERE d.date<='{$date}') AS due_date,
                /*-----------------------------------------------------------------------------------------------------------*/        
                   /*el soa next*/
                     (SELECT (i.amount+(p.amount-n.amount)) AS amount
                      FROM (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document=9 AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id)) i,
                           (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(1,3,7,15) AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) ) p,
                           (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(2,4,8,14) AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) ) n) AS soa_next, 
                /*-----------------------------------------------------------------------------------------------------------*/
                   /*el due date del soa next*/
                     {$due_date}) AS due_date_next,
                                
                /*-----------------------------------------------------------------------------------------------------------*/
                   /*Balance*/
                     (SELECT (i.amount + p.amount + pp.amount + dp.amount - n.amount - dn.amount - pn.amount) AS amount
                      FROM (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document=9 and id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id)) i,
                           (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(1,3,7,15) AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) AND issue_date<='{$date}' ) p,
                           (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(2,4,8,14) AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) AND issue_date<='{$date}' ) n,
                           (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(6) AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) AND issue_date<='{$date}' AND id_accounting_document NOT IN (SELECT id_accounting_document FROM accounting_document WHERE id_type_accounting_document IN (8) AND id_accounting_document IS NOT NULL)) dp,
                           (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(5) AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) AND issue_date<='{$date}' AND id_accounting_document NOT IN (SELECT id_accounting_document FROM accounting_document WHERE id_type_accounting_document IN (7) AND id_accounting_document IS NOT NULL)) dn,
                           (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(10,12) AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) AND issue_date<='{$date}' AND id_accounting_document IS NULL) pp,
                           (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(11,13) AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) AND issue_date<='{$date}' AND id_accounting_document IS NULL) pn) AS balance,
                /*-----------------------------------------------------------------------------------------------------------*/   
                   /*SOA provisionado*/
                     (SELECT (i.amount + p.amount + pp.amount - n.amount - pn.amount) AS amount
                      FROM (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document=9 and id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id)) i,
                           (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(1,3,7,15) AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) ) p,
                           (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(2,4,8,14) AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) ) n,
                           (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(10,12) AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) AND id_accounting_document IS NULL AND issue_date>='2013-10-01') pp,
                           (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(11,13) AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) AND id_accounting_document IS NULL AND issue_date>='2013-10-01') pn) AS soa_provisioned
              FROM carrier_groups cg,
                   carrier c {$tableNext}
                   
              WHERE c.id_carrier_groups=cg.id 
                    {$wherePaymentTerm}
                    {$intercompany}  
              ORDER BY cg.name ASC)activity {$no_activity}";
        return AccountingDocument::model()->findAllBySql($sql);
    }
}
?>