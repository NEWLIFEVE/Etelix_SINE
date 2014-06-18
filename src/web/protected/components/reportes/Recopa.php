 <?php
    /**
     * @package reportes
     */
    class recopa extends Reportes 
    {
        public static function reporte($date,$id_filter_oper,$expired,$order) 
        {
            $carrierGroups=CarrierGroups::getAllGroups();
            $seg=count($carrierGroups)*3;
            ini_set('max_execution_time', $seg);
            $acum_beforeCC=$acum_nowCC=$acum_next1CC=$acum_next2CC=$acum_next3CC=$acum_next4CC=$acum_next5CC=$acum_next6CC=$acum_next7CC=$acum_next8CC=$acum_next9CC=$acum_next10CC=$acum_beforeCP=$acum_nowCP=$acum_next1CP=$acum_next2CP=$acum_next3CP=$acum_next4CP=$acum_next5CP=$acum_next6CP=$acum_next7CP=$acum_next8CP=$acum_next9CP=$acum_next10CP=0;
            $models=self::getModel($date,$id_filter_oper,$order);
            
            $recopa= "<h1>RECOPA <h3>(".$date." - ".date("g:i a").")</h3></h1>";
            $recopa.="<table>
                                 <tr>
                                  <td ". self::defineColorTD(null, "#83898F" ) ."> N° </td>
                                  <td ". self::defineColorTD(null, "silver" ) ."> Operadores </td>
                                  <td ". self::defineColorTD(null, "#3466B4") .">SOA</td>
                                  <td ". self::defineColorTD(null, "silver" ) .">Due date</td>
                                  <td ". self::defineColorTD(null, "#F89289") .">". DateManagement::calculateWeek("-1", $date) ."</td>
                                  <td ". self::defineColorTD(null, "#06ACFA") ."> $date </td>
                                  <td ". self::defineColorTD(null, "#049C47") .">". DateManagement::calculateWeek("+1", $date) ."</td>
                                  <td ". self::defineColorTD(null, "#049C47") .">". DateManagement::calculateWeek("+2", $date) ."</td>
                                  <td ". self::defineColorTD(null, "#049C47") .">". DateManagement::calculateWeek("+3", $date) ."</td>
                                  <td ". self::defineColorTD(null, "#049C47") .">". DateManagement::calculateWeek("+4", $date) ."</td>
                                  <td ". self::defineColorTD(null, "#049C47") .">". DateManagement::calculateWeek("+5", $date) ."</td>
                                  <td ". self::defineColorTD(null, "#049C47") .">". DateManagement::calculateWeek("+6", $date) ."</td>
                                  <td ". self::defineColorTD(null, "#049C47") .">". DateManagement::calculateWeek("+7", $date) ."</td>
                                  <td ". self::defineColorTD(null, "#049C47") .">". DateManagement::calculateWeek("+8", $date) ."</td>
                                  <td ". self::defineColorTD(null, "#049C47") .">". DateManagement::calculateWeek("+9", $date) ."</td>
                                  <td ". self::defineColorTD(null, "#049C47") .">". DateManagement::calculateWeek("+10", $date) ."</td>
                                  <td ". self::defineColorTD(null, "#83898F" ) ."> N° </td>
                                 </tr>";
//                foreach ($models as $key => $model)
//                {
////                    if(self::defineFilterExpired($model->due_date,$date,$expired) && $model->due_date!=null && $model->soa != null)
////                    {
//                        $pos=$key+1;
//                        $recopa.="<tr>
//                                         <td ". self::defineColorTD(null, "#83898F") ."> $pos </td>
//                                         <td ". self::defineColorTD(null, "white") ."> $model->name </td>
//                                         <td ". self::defineColorTD(null, "white" ) .">". Yii::app()->format->format_decimal($model->soa). "</td>
//                                         <td ". self::defineColorTD(null, "white" ) .">". $model->due_date ."</td>
//                                         <td ". self::defineTD($date,DateManagement::calculateDate("-7", $date),$model->due_date,$model->soa,$model->balance) ."</td>
//                                         <td ". self::defineTD($date,$date,$model->due_date,$model->soa,$model->balance) ."</td>
//                                         <td ". self::defineTD($date,DateManagement::calculateDate("+7", $date),$model->due_date,$model->soa,$model->balance) ."</td>
//                                         <td ". self::defineTD($date,DateManagement::calculateDate("+14", $date),$model->due_date,$model->soa,$model->balance) ."</td>
//                                         <td ". self::defineTD($date,DateManagement::calculateDate("+21", $date),$model->due_date,$model->soa,$model->balance) ."</td>
//                                         <td ". self::defineTD($date,DateManagement::calculateDate("+28", $date),$model->due_date,$model->soa,$model->balance) ."</td>
//                                         <td ". self::defineTD($date,DateManagement::calculateDate("+35", $date),$model->due_date,$model->soa,$model->balance) ."</td>
//                                         <td ". self::defineTD($date,DateManagement::calculateDate("+42", $date),$model->due_date,$model->soa,$model->balance) ."</td>
//                                         <td ". self::defineTD($date,DateManagement::calculateDate("+49", $date),$model->due_date,$model->soa,$model->balance) ."</td>
//                                         <td ". self::defineTD($date,DateManagement::calculateDate("+56", $date),$model->due_date,$model->soa,$model->balance) ."</td>
//                                         <td ". self::defineTD($date,DateManagement::calculateDate("+63", $date),$model->due_date,$model->soa,$model->balance) ."</td>
//                                         <td ". self::defineTD($date,DateManagement::calculateDate("+70", $date),$model->due_date,$model->soa,$model->balance) ."</td>
//                                         <td ". self::defineColorTD(null, "#83898F") ."> $pos </td>
//                                        </tr>";
//                        $acum_beforeCC=self::defineAcumCC(DateManagement::calculateDate("-7", $date),$model->due_date,$model->soa,$acum_beforeCC);
//                        $acum_nowCC   =self::defineAcumCC($date,$model->due_date,$model->soa,$acum_nowCC);
//                        $acum_next1CC =self::defineAcumCC(DateManagement::calculateDate("+7", $date),$model->due_date,$model->soa,$acum_next1CC);
//                        $acum_next2CC =self::defineAcumCC(DateManagement::calculateDate("+14", $date),$model->due_date,$model->soa,$acum_next2CC);
//                        $acum_next3CC =self::defineAcumCC(DateManagement::calculateDate("+21", $date),$model->due_date,$model->soa,$acum_next3CC);
//                        $acum_next4CC =self::defineAcumCC(DateManagement::calculateDate("+28", $date),$model->due_date,$model->soa,$acum_next4CC);
//                        $acum_next5CC =self::defineAcumCC(DateManagement::calculateDate("+35", $date),$model->due_date,$model->soa,$acum_next5CC);
//                        $acum_next6CC =self::defineAcumCC(DateManagement::calculateDate("+42", $date),$model->due_date,$model->soa,$acum_next6CC);
//                        $acum_next7CC =self::defineAcumCC(DateManagement::calculateDate("+49", $date),$model->due_date,$model->soa,$acum_next7CC);
//                        $acum_next8CC =self::defineAcumCC(DateManagement::calculateDate("+56", $date),$model->due_date,$model->soa,$acum_next8CC);
//                        $acum_next9CC =self::defineAcumCC(DateManagement::calculateDate("+63", $date),$model->due_date,$model->soa,$acum_next9CC);
//                        $acum_next10CC=self::defineAcumCC(DateManagement::calculateDate("+70", $date),$model->due_date,$model->soa,$acum_next10CC);
//
//                        $acum_beforeCP=self::defineAcumCP(DateManagement::calculateDate("-7", $date),$model->due_date,$model->soa,$acum_beforeCP);
//                        $acum_nowCP   =self::defineAcumCP($date,$model->due_date,$model->soa,$acum_nowCP);
//                        $acum_next1CP =self::defineAcumCP(DateManagement::calculateDate("+7", $date),$model->due_date,$model->soa,$acum_next1CP);
//                        $acum_next2CP =self::defineAcumCP(DateManagement::calculateDate("+14", $date),$model->due_date,$model->soa,$acum_next2CP);
//                        $acum_next3CP =self::defineAcumCP(DateManagement::calculateDate("+21", $date),$model->due_date,$model->soa,$acum_next3CP);
//                        $acum_next4CP =self::defineAcumCP(DateManagement::calculateDate("+28", $date),$model->due_date,$model->soa,$acum_next4CP);
//                        $acum_next5CP =self::defineAcumCP(DateManagement::calculateDate("+35", $date),$model->due_date,$model->soa,$acum_next5CP);
//                        $acum_next6CP =self::defineAcumCP(DateManagement::calculateDate("+42", $date),$model->due_date,$model->soa,$acum_next6CP);
//                        $acum_next7CP =self::defineAcumCP(DateManagement::calculateDate("+49", $date),$model->due_date,$model->soa,$acum_next7CP);
//                        $acum_next8CP =self::defineAcumCP(DateManagement::calculateDate("+56", $date),$model->due_date,$model->soa,$acum_next8CP);
//                        $acum_next9CP =self::defineAcumCP(DateManagement::calculateDate("+63", $date),$model->due_date,$model->soa,$acum_next9CP);
//                        $acum_next10CP=self::defineAcumCP(DateManagement::calculateDate("+70", $date),$model->due_date,$model->soa,$acum_next10CP);
////                   }
//               }
//               $recopa.="<tr>
//                                <td colspan='15'></dt>
//                               </tr>";
//               $recopa.="<tr>
//                                <td ". self::defineColorTD(null, "#3466B4")."colspan='4'>Totales C/C</td>
//                                <td ". self::defineColorTD($acum_beforeCC, "white").">".Yii::app()->format->format_decimal($acum_beforeCC)."</td>
//                                <td ". self::defineColorTD($acum_nowCC, "white") .  ">".Yii::app()->format->format_decimal($acum_nowCC  )."</td>
//                                <td ". self::defineColorTD($acum_next1CC, "white") .">".Yii::app()->format->format_decimal($acum_next1CC)."</td>
//                                <td ". self::defineColorTD($acum_next2CC, "white") .">".Yii::app()->format->format_decimal($acum_next2CC)."</td>
//                                <td ". self::defineColorTD($acum_next3CC, "white") .">".Yii::app()->format->format_decimal($acum_next3CC)."</td>
//                                <td ". self::defineColorTD($acum_next4CC, "white") .">".Yii::app()->format->format_decimal($acum_next4CC)."</td>
//                                <td ". self::defineColorTD($acum_next5CC, "white") .">".Yii::app()->format->format_decimal($acum_next5CC)."</td>
//                                <td ". self::defineColorTD($acum_next6CC, "white") .">".Yii::app()->format->format_decimal($acum_next6CC)."</td>
//                                <td ". self::defineColorTD($acum_next7CC, "white") .">".Yii::app()->format->format_decimal($acum_next7CC)."</td>
//                                <td ". self::defineColorTD($acum_next8CC, "white") .">".Yii::app()->format->format_decimal($acum_next8CC)."</td>
//                                <td ". self::defineColorTD($acum_next9CC, "white") .">".Yii::app()->format->format_decimal($acum_next9CC)."</td>
//                                <td colspan='2' ". self::defineColorTD($acum_next10CC,"white") .">".Yii::app()->format->format_decimal($acum_next10CC)."</td>
//                               </tr>";
//               $recopa.="<tr>
//                                <td ". self::defineColorTD(null, "#E99241")."colspan='4'>Totales C/P</td>
//                                <td ". self::defineColorTD($acum_beforeCP, "white").">".Yii::app()->format->format_decimal($acum_beforeCP)."</td>
//                                <td ". self::defineColorTD($acum_nowCP, "white")   .">".Yii::app()->format->format_decimal($acum_nowCP)."</td>
//                                <td ". self::defineColorTD($acum_next1CP, "white") .">".Yii::app()->format->format_decimal($acum_next1CP)."</td>
//                                <td ". self::defineColorTD($acum_next2CP, "white") .">".Yii::app()->format->format_decimal($acum_next2CP)."</td>
//                                <td ". self::defineColorTD($acum_next3CP, "white") .">".Yii::app()->format->format_decimal($acum_next3CP)."</td>
//                                <td ". self::defineColorTD($acum_next4CP, "white") .">".Yii::app()->format->format_decimal($acum_next4CP)."</td>
//                                <td ". self::defineColorTD($acum_next5CP, "white") .">".Yii::app()->format->format_decimal($acum_next5CP)."</td>
//                                <td ". self::defineColorTD($acum_next6CP, "white") .">".Yii::app()->format->format_decimal($acum_next6CP)."</td>
//                                <td ". self::defineColorTD($acum_next7CP, "white") .">".Yii::app()->format->format_decimal($acum_next7CP)."</td>
//                                <td ". self::defineColorTD($acum_next8CP, "white") .">".Yii::app()->format->format_decimal($acum_next8CP)."</td>
//                                <td ". self::defineColorTD($acum_next9CP, "white") .">".Yii::app()->format->format_decimal($acum_next9CP)."</td>
//                                <td colspan='2' ". self::defineColorTD($acum_next10CP,"white") .">".Yii::app()->format->format_decimal($acum_next10CP)."</td>
//                               </tr>";
//               $recopa.="<tr ". self::defineColorTD(null, "#049C47").">
//                                <td colspan='4'>Posición Neta</td>
//                                <td >".Yii::app()->format->format_decimal($acum_beforeCC + $acum_beforeCP)."</td>
//                                <td >".Yii::app()->format->format_decimal($acum_nowCC + $acum_nowCP)."</td>
//                                <td >".Yii::app()->format->format_decimal($acum_next1CC + $acum_next1CP)."</td>
//                                <td >".Yii::app()->format->format_decimal($acum_next2CC + $acum_next2CP)."</td>
//                                <td >".Yii::app()->format->format_decimal($acum_next3CC + $acum_next3CP)."</td>
//                                <td >".Yii::app()->format->format_decimal($acum_next4CC + $acum_next4CP)."</td>
//                                <td >".Yii::app()->format->format_decimal($acum_next5CC + $acum_next5CP)."</td>
//                                <td >".Yii::app()->format->format_decimal($acum_next6CC + $acum_next6CP)."</td>
//                                <td >".Yii::app()->format->format_decimal($acum_next7CC + $acum_next7CP)."</td>
//                                <td >".Yii::app()->format->format_decimal($acum_next8CC + $acum_next8CP)."</td>
//                                <td >".Yii::app()->format->format_decimal($acum_next9CC + $acum_next9CP)."</td>
//                                <td colspan='2'>".Yii::app()->format->format_decimal($acum_next10CC + $acum_next10CP)."</td>
//                               </tr>";
              $recopa.="</table>";
           return $recopa;
        }
        
        public static function defineAcumCC($dateActual,$due_date,$amount,$acumulado)
        {
            if( $due_date >= DateManagement::calculateDate("-6", $dateActual) && $due_date <= $dateActual){
                if($amount != null||$amount != 0){
                    if($amount > 0){
                       return $acumulado + $amount;
                    }else{
                       return $acumulado;
                    }
                }else{
                   return $acumulado;
                }
            }else{
                return $acumulado;
            }
        }
        public static function defineAcumCP($dateActual,$due_date,$amount,$acumulado)
        {
            if( $due_date >= DateManagement::calculateDate("-6", $dateActual) && $due_date <= $dateActual){
                if($amount != null||$amount != 0){
                    if($amount < 0){
                       return  $acumulado + $amount;
                    }else{
                       return $acumulado;
                    }
               }else{
                   return $acumulado;
               }
            }else{
                return $acumulado;
            }
        }
        public static function defineTD($date,$dateNow,$due_date,$amount,$balance)
        {
            if($due_date >= DateManagement::calculateDate("-6", $dateNow) && $due_date <= $dateNow){
                return  self::defineColorTD($amount,null) .">".Yii::app()->format->format_decimal($amount);
            }
            elseif($due_date >=  DateManagement::calculateDate("-14", $date) && $dateNow >=  DateManagement::calculateDate("+7", $due_date) && $dateNow >=  $date){
//                return  self::defineColorTD(null, "white") .">".Yii::app()->format->format_decimal($balance);
                return  self::defineColorTD(null, "white") .">";
            }
            elseif($due_date < DateManagement::calculateDate("-14", $date)){
                return  self::defineColorTD($amount,null);
            }
            else{
                return  self::defineColorTD(null, "white") .">";
            }
        }
        public static function defineColorTD($amount, $backColor)
        {
            if($amount!=null){  
                if($amount>2000){
                    return "style='border:1px solid black;color:white;text-align:left;background:#3466B4;'";
                }
                elseif($amount<-2000){
                    return "style='border:1px solid black;color:white;text-align:left;background:#E99241;'"; 
                }
                elseif($amount<0 && $amount>-2000 || $amount>0 && $amount<2000){
                    return "style='border:1px solid black;color:white;text-align:left;background:#FCD746;'"; 
                }
            }elseif($amount==null){
                $colorText="white";
                if($backColor=="white")$colorText="black";
                return "style='border:1px solid black;color:$colorText;text-align:left;background:$backColor;'";
            }
        }
        

        public static function getModel($date,$filter_oper,$order=FALSE)
        {
            if($filter_oper=="0"){ 
                $filter="(i.amount+e.amount+p.amount-n.amount-r.amount) AS amount";
                $filterNext="(i.amount+p.amount-n.amount) AS amount";
            }if($filter_oper=="1"){
                $filter="CASE WHEN ABS(i.amount+e.amount+p.amount-n.amount-r.amount) > ABS(2000) THEN (i.amount+e.amount+p.amount-n.amount-r.amount) END AS amount";
                $filterNext="CASE WHEN ABS(i.amount+p.amount-n.amount) > ABS(2000) THEN (i.amount+p.amount-n.amount) END AS amount";
            }if($filter_oper=="2"){
                $filter="CASE WHEN ABS(i.amount+e.amount+p.amount-n.amount-r.amount) < ABS(2000) THEN (i.amount+e.amount+p.amount-n.amount-r.amount) END AS amount";
                $filterNext="CASE WHEN ABS(i.amount+p.amount-n.amount) AS amount < ABS(2000) THEN (i.amount+p.amount-n.amount) AS amount END AS amount";
            }
           
            $order_name=" ORDER BY cg.name"; $order_amount="";
            if($order){
                $order_name=" ORDER BY cg.name";
                $order_amount="";
            }
            if($order==TRUE){
                $order_amount=" ORDER BY soa desc ";
                $order_name="";
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
            $sql="select * from
                (SELECT cg.id, 
                    /*Traigo el nombre del grupo*/
                       cg.name,
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

                       /*Traigo el soa total*/
                        (SELECT {$filter}
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

                     (SELECT {$filterNext}
                      FROM (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document=9 AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id)) i,
                           (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(1,3,7,15) AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) ) p,
                           (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(2,4,8,14) AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) ) n) AS soa_next, 
                   
                   /*el due date del soa next*/

                           {$due_date}) AS due_date_next









                        



                   /*Balance
                     (SELECT (i.amount+(p.amount-n.amount)) AS amount
                      FROM (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document=9 and id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id)) i,
                           (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(12) AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) AND issue_date<='{$date}' AND confirm != -1) p,
                           (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(13) AND id_carrier IN(SELECT id FROM carrier WHERE id_carrier_groups=cg.id) AND issue_date<='{$date}' AND confirm != -1) n) AS balance*/
                        FROM carrier_groups cg 
                         /*WHERE cg.id IN (SELECT cg.id FROM carrier c, contrato con,  contrato_termino_pago ctp, termino_pago tp, carrier_groups cg 
                                        WHERE con.id_carrier=c.id AND ctp.id_contrato=con.id AND ctp.id_termino_pago=tp.id AND con.end_date IS NULL 
                                              AND con.sign_date IS NOT NULL AND tp.expiration=7 AND c.id_carrier_groups=cg.id {$order_name})*/)orden {$order_amount}";
            return AccountingDocument::model()->findAllBySql($sql);
        }
        public static function defineFilterExpired($due_date,$date,$filter)
        {
            switch ($filter) {
                case "":
                    return $due_date!=null;//trae todos los soa vencidos de mas de dos semanas
                    break;
                case "No":
                    return $due_date >= DateManagement::calculateDate("-14", $date);
                    break;
                default:
                    return $due_date!=null;
                    break;
            }
        }
    }
    ?>