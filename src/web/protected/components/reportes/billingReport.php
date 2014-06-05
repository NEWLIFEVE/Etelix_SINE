 <?php

/**
 * @package reportes
 * @version 1.0
 */
class billingReport extends Reportes 
{
    private $carriersSine=NULL;
    private $totalBalanceSine;
    private $totalBalanceBilling;
    private $totalDifference;
    private $countEqual=0;
    private $countDiff=0;
    private $countProv=0;
    private $countBilNull=0;
    private $countHistTp=0;

    private $styleNumberRow ="style='border:1px solid silver;text-align:center;background:#83898F;color:white;'";
    private $styleBasic ="style='border:1px solid silver;text-align:center;'";
    private $styleWhite ="style='border:1px solid silver;text-align:center;'";
    private $styleYellow ="style='border:1px solid silver;text-align:center;background:#FAE08D;'";
    private $stylePinck ="style='border:1px solid silver;text-align:center;background:#F3D6D7;'";
    private $styleSky ="style='text-align:center;background:#D3E7EE;border:1px solid silver;'";
    private $stylePurple ="style='border:1px solid silver;text-align:center;background:#D1BFEC;'";
    private $styleCarrierHead ="style='border:1px solid silver;background:silver;text-align:center;color:white;'";
    private $styleSine ="style='border:1px solid silver;background:#3466B4;text-align:center;color:white;'";
    private $styleBilling ="style='border:1px solid silver;background:#248CB4;text-align:center;color:white;'";
    private $styleDifference ="style='border:1px solid silver;background:#E99241;text-align:center;color:white;'";
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
    public function report($date,$interCompany,$noActivity,$typePaymentTerm,$paymentTerm,$toDateLastPeriod)
    {
        /*********************   AYUDA A AUMENTAR EL TIEMPO PARA GENERAR EL REPORTE CUANDO SON MUCHOS REGISTROS   **********************/
        ini_set('max_execution_time', 500);
        if($date==null) $date=date('Y-m-d');
        $documents=$this->_getData($date,$interCompany,$noActivity,$typePaymentTerm,$paymentTerm,$toDateLastPeriod);
        $balanceSine=$balanceBilling=$difference=0;
        $body=NULL;
        if($documents!=NULL)
        {
            $body.="<table>
                        <tr>
                            <td colspan='10'>
                                <h2> ".Reportes::defineNameExtra($paymentTerm,$typePaymentTerm, NULL)."</h2>
                                al {$date}
                            </td>
                        </tr>
                   </table>
                   <table style='width: 100%;'>
                    
                    <tr>
                        <td {$this->styleNumberRow} >N°</td>
                        <td {$this->styleCarrierHead} > CARRIER </td>
                        <td {$this->styleSine} colspan='3'> SINE </td>
                        <td {$this->styleBilling} colspan='2'> BILLING </td>
                        <td {$this->styleDifference} colspan='2'> DIFFERENCE </td>
                        <td {$this->styleNumberRow} >N°</td>
                    </tr>";
            foreach($documents as $key => $document)
            {
                if($this->carriersSine==NULL)
                    $this->carriersSine.="'".$document->name."'";    
                else
                    $this->carriersSine.=",'".$document->name."'";
                $pos=$key+1;
                $balanceSine+=$document->balance;
                $balanceBilling+=$document->balance_billing;
                $difference+=$document->difference;
 
                $this->defineCategoryAndStyle($document);
                
                $body.="<tr {$this->styleBasic} >";
                    $body.="<td {$this->styleNumberRow} >{$pos}</td>";
                    $body.="<td {$this->styleBasic} >".$document->name."</td>";
                    $body.="<td {$this->styleBasic} colspan='3'>".Yii::app()->format->format_decimal($document->balance)."</td>";
                    $body.="<td {$this->styleBasic} colspan='2'>".Yii::app()->format->format_decimal($document->balance_billing)."</td>";
                    $body.="<td {$this->styleBasic} colspan='2'>".Yii::app()->format->format_decimal($document->difference)."</td>";
                    $body.="<td {$this->styleNumberRow} >{$pos}</td>";
                $body.="</tr>";
                $this->styleBasic=$this->styleWhite;
            }
            $body.="<tr>
                        <td {$this->styleNull} ></td>
                        <td {$this->styleCarrierHead} rowspan='2'> TOTAL </td>
                        <td {$this->styleSine} colspan='3'> SINE </td>
                        <td {$this->styleBilling} colspan='2'> BILLING </td>
                        <td {$this->styleDifference} colspan='2'> DIFFERENCE </td>
                        <td {$this->styleNull} ></td>
                    </tr>
                    <tr>
                        <td {$this->styleNull} ></td>
                        <td {$this->styleBasic} colspan='3'>".Yii::app()->format->format_decimal($balanceSine)."</td>
                        <td {$this->styleBasic} colspan='2'>".Yii::app()->format->format_decimal($balanceBilling)."</td>
                        <td {$this->styleBasic} colspan='2'>".Yii::app()->format->format_decimal($difference)."</td>
                        <td {$this->styleNull} ></td>
                    </tr>
                </table>";  
             $this->totalBalanceSine+=$balanceSine;
             $this->totalBalanceBilling+=$balanceBilling;
             $this->totalDifference+=$difference;
        }  
        return $body;
    }
    public function defineCategoryAndStyle($document)
    {
        if($document->carrier_billing == null){
            $this->countBilNull+=1;
            return $this->styleBasic=$this->stylePinck;
        }else{
                if($document->difference > -1 && $document->difference < 1 ){
                    $this->countEqual+=1;
                    return $this->styleBasic=$this->styleWhite;
                }
                if($document->tp >=1){
                    $this->countHistTp+=1;
                    return $this->styleBasic=$this->styleSky;
                }else{
                    if($document->provision_traffic_received >=1){
                        $this->countProv+=1;
                        return $this->styleBasic=$this->stylePurple;
                    }
                    if($document->difference > 1 || $document->difference < -1 ){
                    $this->countDiff+=1;
                    return $this->styleBasic=$this->styleYellow;
                    }
                }
                
                
        }
    }
    
    /**
     * Metodo encargado de armar el resumen total cuando se consulta recredi para todos los termino pago.
     * @return string
     */
    public function totalsGeneral()
    {
        $body="<h2 style='color:#06ACFA!important;'>RESUMEN TOTAL GENERAL</h2> <br>";
        $body.="<table style='width: 100%;'>
                    <tr>
                        <td {$this->styleSine} colspan='3'> SINE </td>
                        <td {$this->styleBilling} colspan='3'> BILLING </td>
                        <td {$this->styleDifference} colspan='3'> DIFFERENCE </td>
                    </tr>";
       
        $body.="<tr>
                    <td {$this->styleBasic} colspan='3'>".Yii::app()->format->format_decimal($this->totalBalanceSine)."</td>
                    <td {$this->styleBasic} colspan='3'>".Yii::app()->format->format_decimal($this->totalBalanceBilling)."</td>
                    <td {$this->styleBasic} colspan='3'>".Yii::app()->format->format_decimal($this->totalDifference)."</td>
                  </tr>
                </table><br>";
        return $body;
    }
    /**
     * 
     * @return type
     */
    public function countCategory()
    {
        $body="<h2 style='color:#06ACFA!important;'>Summary count by category </h2> <br>
                <table style='width: 27%;text-align: center!important;'>
                 <tr>    <td {$this->styleWhite}  colspan='4'> {$this->countEqual}   casos  </td>    </tr>
                 <tr>    <td {$this->styleYellow} colspan='4'> {$this->countDiff}    casos  </td>    </tr>
                 <tr>    <td {$this->stylePurple} colspan='4'> {$this->countProv}    casos  </td>    </tr>
                 <tr>    <td {$this->stylePinck}  colspan='4'> {$this->countBilNull} casos  </td>    </tr>
                 <tr>    <td {$this->styleSky}    colspan='4'> {$this->countHistTp}  casos  </td>    </tr>
                </table>";
        return $body;
    }
    /**
     * 
     */
    public function getCarriersBillingNotSine()
    {
        $sql="SELECT * FROM billing WHERE carrier NOT IN ({$this->carriersSine})";
        $modelCarrierBilling= Billing::model()->findAllBySql($sql);
        if($modelCarrierBilling!=NULL)
        {
            $body="<h2 style='color:#06ACFA!important;'>Operadores BILLING sin coincidencias con SINE </h2>";
            $body.="<table>
                         <tr>
                            <td {$this->styleNumberRow} >N°</td>
                            <td {$this->styleCarrierHead} >Carrier</td>
                            <td {$this->styleBilling} >Balance</td>
                            <td {$this->styleNumberRow} >N°</td>
                        </tr>";
            foreach ($modelCarrierBilling as $key => $model)
            {
                $pos=$key+1;
                $body.="<tr>
                            <td {$this->styleNumberRow } >{$pos}</td>
                            <td {$this->styleBasic} >".$model->carrier."</td>
                            <td {$this->styleBasic} >".Yii::app()->format->format_decimal($model->amount)."</td>
                            <td {$this->styleNumberRow } >{$pos}</td>
                        </tr>";

            }
            $body.="</table>";
            return $body;
        }else{
            return "<h3 style='color:#DAB6B7!important;'>No hay Operadores BILLING sin coincidencias con SINE </h3>";
        }   
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
    private function _getData($date,$interCompany=TRUE,$noActivity=TRUE,$typePaymentTerm,$paymentTerm,$toDateLastPeriod)
    {
        if($interCompany)           $interCompany="";
        elseif($interCompany==FALSE) $interCompany="AND cg.id NOT IN(SELECT id FROM carrier_groups WHERE name IN('FULLREDPERU','R-ETELIX.COM PERU','CABINAS PERU'))";

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

        $sql="SELECT *, CASE WHEN balance_billing IS NULL THEN (balance) ELSE (balance - balance_billing)END AS difference
              FROM  (SELECT 
                     DISTINCT cg.id AS id, 
                     cg.name AS name, 
                     /*la variable select completa el select principal, en su estado natural trae todos los parametros y en el interno comienza con el id y nombre de grupo para los totales el select principal extrae la suma de cada valor y no extrae los datos basicos de grupo*/
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
                   (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount 
                    FROM accounting_document 
                    WHERE id_type_accounting_document IN(11,13) AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) AND to_date<'{$toDateLastPeriod}' AND id_accounting_document IS NULL) AS provision_traffic_received,
               /*-----------------------------------------------------------------------------------------------------------*/  
                   (SELECT count(tph)
                        FROM(SELECT count(ctps.id)AS tph
                             FROM contrato con, 
                                  carrier c,
                                  contrato_termino_pago_supplier ctps,
                                  termino_pago tp
                             WHERE con.id_carrier=c.id
                               AND c.id IN(select id from carrier where id_carrier_groups IN(select id from carrier_groups where name=cg.name))
                               AND con.id=ctps.id_contrato
                               AND ctps.id_termino_pago_supplier=tp.id
                               AND ctps.end_date IS NOT NULL
                             GROUP BY con.id,ctps.id,tp.name,c.name) tph) AS tp,
               /*-----------------------------------------------------------------------------------------------------------*/       
                   (SELECT amount from billing
                    where carrier = cg.name and date_balance='{$date}')AS balance_billing,
               /*-----------------------------------------------------------------------------------------------------------*/  
                   (SELECT carrier from billing
                    where carrier = cg.name and date_balance='{$date}')AS carrier_billing

              FROM carrier_groups cg,
                   carrier c {$tableNext}
                   
              WHERE c.id_carrier_groups=cg.id 
                    {$wherePaymentTerm}
                    {$interCompany}  
              ORDER BY cg.name ASC)activity";
        return AccountingDocument::model()->findAllBySql($sql);
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
        ini_set('max_execution_time', 2000);
        ini_set('memory_limit', '512M');
        $var="";
        $backLegend="<table style='width: 100%;border:1px solid white;'>
                      <tr> 
                         <td colspan='8' style='width: 72%;'>";
        $legend="        <td coslpan='4'>
                            <table>
                             <tr>    <td colspan='5' style='font-weight: bold;'> Legend </td>    </tr>
                             <tr>    <td coslpan='5' style='width:100%;border-top: 1px solid silver;'> SINE y billing sin diferencias notables </td><td style='border:solid 1px silver;width:12%;color:white;'>col</td>    </tr>
                             <tr>    <td coslpan='5' style='width:100%;'> Diferencias que hay que investigar </td><td style='background:#FAE08D;width:12%;border-bottom: 3px solid white;'></td>    </tr>
                             <tr>    <td coslpan='5' style='width:100%;'> Diferencia por provisiones </td><td style='background:#D1BFEC;width:12%;border-bottom: 3px solid white;'></td>    </tr>
                             <tr>    <td coslpan='5' style='width:100%;'> No se encuentra el grupo en billing </td><td style='background:#F3D6D7;width:12%;border-bottom: 3px solid white;'></td>    </tr>
                             <tr>    <td coslpan='5' style='width:100%;'> Se han cambiado los termino pago </td><td style='background:#D3E7EE;width:12%;'></td>    </tr>
                            </table>
                         </td> 
                      </tr>
                   </table>";
        
        if($paymentTerms=="todos") {
            $paymentTerms= TerminoPago::getModel();
            
            if($typePaymentTerm===NULL){                        /*Este caso es si se selecciono traer ambos tipos de relacion comercial*/
                $var.=$backLegend."<h1 style='color:#06ACFA!important;'>DIFFERENCE CUSTOMER</h1> <br>".$legend;
                foreach ($paymentTerms as $key => $paymentTerm) /*Busca todos los termino pago en la relacion customer*/
                {
                   
                   if($paymentTerm->name!="Sin estatus"){
                       $period=TerminoPago::getModelFind($paymentTerm->id)->period;
                       $toDateLastPeriod=Reportes::defineToDatePeriod($period, $date);
                       $fromDateLastPeriod=Reportes::defineFromDate($period,$toDateLastPeriod);
                       $var.= $this->report($date,$interCompany,$noActivity,FALSE,$paymentTerm->id, $fromDateLastPeriod);
                   }
                }
                $var.="<br> <h1 style='color:#06ACFA!important;'>DIFFERENCE SUPPLIER</h1> <br>";
                foreach ($paymentTerms as $key => $paymentTerm) /*Concatena al customer y busca todos los termino pago en la relacion supplier*/
                {
                   if($paymentTerm->name!="Sin estatus"){
                       $period=TerminoPago::getModelFind($paymentTerm->id)->period;
                       $toDateLastPeriod=Reportes::defineToDatePeriod($period, $date);
                       $fromDateLastPeriod=Reportes::defineFromDate($period,$toDateLastPeriod);
                       $var.= $this->report($date,$interCompany,$noActivity,TRUE,$paymentTerm->id, $fromDateLastPeriod);
                   }
                }
                $var.=$this->countCategory().$this->getCarriersBillingNotSine();
            }else{
                $var.=$backLegend."<h1>DIFFERENCE</h1>".$legend;
                foreach ($paymentTerms as $key => $paymentTerm) /*Busca todos los termino pago en la relacion seleccionada, (solo una:customer o supplier)*/
                {
                   if($paymentTerm->name!="Sin estatus"){
                       $period=TerminoPago::getModelFind($paymentTerm->id)->period;
                       $toDateLastPeriod=Reportes::defineToDatePeriod($period, $date);
                       $fromDateLastPeriod=Reportes::defineFromDate($period,$toDateLastPeriod);
                       $var.= $this->report($date,$interCompany,$noActivity,$typePaymentTerm,$paymentTerm->id,$fromDateLastPeriod);
                   }
                }
                $var.= $this->totalsGeneral().$this->countCategory().$this->getCarriersBillingNotSine();
            }
        }else{                                                  /*Busca un solo termino pago en la relacion seleccionada, (solo una:customer o supplier)*/
            $period=TerminoPago::getModelFind($paymentTerms)->period;
            $toDateLastPeriod=Reportes::defineToDatePeriod($period, $date);
            $fromDateLastPeriod=Reportes::defineFromDate($period,$toDateLastPeriod);
            $data= $this->report($date,$interCompany,$noActivity,$typePaymentTerm,$paymentTerms,$fromDateLastPeriod);
            if($data!=NULL)
                $var.=$backLegend."<h1>DIFFERENCE</h1>".$legend. $data .$this->countCategory();
            else
                $var.="<h3>No hay data para este termino pago en la relacion comercial seleccionada</h3>";
            
        } 
        return $var;
    }
}
?>