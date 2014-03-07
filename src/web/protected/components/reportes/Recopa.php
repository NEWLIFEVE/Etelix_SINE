 <?php
    /**
     * @package reportes
     */
    class recopa extends Reportes 
    {
        public static function reporte($date,$id_filter_oper,$expired) 
        {
            $carrierGroups=CarrierGroups::getAllGroups();
            $seg=count($carrierGroups)*3;
            ini_set('max_execution_time', $seg);
            $acum_beforeCC=$acum_nowCC=$acum_next1CC=$acum_next2CC=$acum_next3CC=$acum_next4CC=$acum_next5CC=$acum_next6CC=$acum_next7CC=$acum_next8CC=$acum_next9CC=$acum_next10CC=$acum_beforeCP=$acum_nowCP=$acum_next1CP=$acum_next2CP=$acum_next3CP=$acum_next4CP=$acum_next5CP=$acum_next6CP=$acum_next7CP=$acum_next8CP=$acum_next9CP=$acum_next10CP=0;
            $models=self::getModel($date,$id_filter_oper);
            
            $recopa= "<h1>RECOPA <h3>(".$date." - ".date("g:i a").")</h3></h1>";
            $recopa.="<table>
                                 <tr>
                                  <td ". self::defineColorTD(null, "silver" ) ."> Operadores </td>
                                  <td ". self::defineColorTD(null, "#3466B4") .">SOA</td>
                                  <td ". self::defineColorTD(null, "silver" ) .">Due date</td>
                                  <td ". self::defineColorTD(null, "#F89289") .">". Reportes::sumRestDate("7", $date,"-") ."</td>
                                  <td ". self::defineColorTD(null, "#06ACFA") ."> $date </td>
                                  <td ". self::defineColorTD(null, "#049C47") .">". Reportes::sumRestDate("7", $date,"+") ."</td>
                                  <td ". self::defineColorTD(null, "#049C47") .">". Reportes::sumRestDate("14", $date,"+") ."</td>
                                  <td ". self::defineColorTD(null, "#049C47") .">". Reportes::sumRestDate("21", $date,"+") ."</td>
                                  <td ". self::defineColorTD(null, "#049C47") .">". Reportes::sumRestDate("28", $date,"+") ."</td>
                                  <td ". self::defineColorTD(null, "#049C47") .">". Reportes::sumRestDate("35", $date,"+") ."</td>
                                  <td ". self::defineColorTD(null, "#049C47") .">". Reportes::sumRestDate("42", $date,"+") ."</td>
                                  <td ". self::defineColorTD(null, "#049C47") .">". Reportes::sumRestDate("49", $date,"+") ."</td>
                                  <td ". self::defineColorTD(null, "#049C47") .">". Reportes::sumRestDate("56", $date,"+") ."</td>
                                  <td ". self::defineColorTD(null, "#049C47") .">". Reportes::sumRestDate("63", $date,"+") ."</td>
                                  <td ". self::defineColorTD(null, "#049C47") .">". Reportes::sumRestDate("70", $date,"+") ."</td>
                                 </tr>";
                foreach ($models as $key => $model)
                {
                    if(self::defineFilterExpired($model->due_date,$date,$expired) && $model->due_date!=null && $model->soa != null)
                    {
                        $acum_beforeCC=self::defineAcumCC(Reportes::sumRestDate("7", $date,"-"),$model->due_date,$model->soa,$acum_beforeCC);
                        $acum_nowCC   =self::defineAcumCC($date,$model->due_date,$model->soa,$acum_nowCC);
                        $acum_next1CC =self::defineAcumCC(Reportes::sumRestDate("7", $date,"+"),$model->due_date,$model->soa,$acum_next1CC);
                        $acum_next2CC =self::defineAcumCC(Reportes::sumRestDate("14", $date,"+"),$model->due_date,$model->soa,$acum_next2CC);
                        $acum_next3CC =self::defineAcumCC(Reportes::sumRestDate("21", $date,"+"),$model->due_date,$model->soa,$acum_next3CC);
                        $acum_next4CC =self::defineAcumCC(Reportes::sumRestDate("28", $date,"+"),$model->due_date,$model->soa,$acum_next4CC);
                        $acum_next5CC =self::defineAcumCC(Reportes::sumRestDate("35", $date,"+"),$model->due_date,$model->soa,$acum_next5CC);
                        $acum_next6CC =self::defineAcumCC(Reportes::sumRestDate("42", $date,"+"),$model->due_date,$model->soa,$acum_next6CC);
                        $acum_next7CC =self::defineAcumCC(Reportes::sumRestDate("49", $date,"+"),$model->due_date,$model->soa,$acum_next7CC);
                        $acum_next8CC =self::defineAcumCC(Reportes::sumRestDate("56", $date,"+"),$model->due_date,$model->soa,$acum_next8CC);
                        $acum_next9CC =self::defineAcumCC(Reportes::sumRestDate("63", $date,"+"),$model->due_date,$model->soa,$acum_next9CC);
                        $acum_next10CC=self::defineAcumCC(Reportes::sumRestDate("70", $date,"+"),$model->due_date,$model->soa,$acum_next10CC);

                        $acum_beforeCP=self::defineAcumCP(Reportes::sumRestDate("7", $date,"-"),$model->due_date,$model->soa,$acum_beforeCP);
                        $acum_nowCP   =self::defineAcumCP($date,$model->due_date,$model->soa,$acum_nowCP);
                        $acum_next1CP =self::defineAcumCP(Reportes::sumRestDate("7", $date,"+"),$model->due_date,$model->soa,$acum_next1CP);
                        $acum_next2CP =self::defineAcumCP(Reportes::sumRestDate("14", $date,"+"),$model->due_date,$model->soa,$acum_next2CP);
                        $acum_next3CP =self::defineAcumCP(Reportes::sumRestDate("21", $date,"+"),$model->due_date,$model->soa,$acum_next3CP);
                        $acum_next4CP =self::defineAcumCP(Reportes::sumRestDate("28", $date,"+"),$model->due_date,$model->soa,$acum_next4CP);
                        $acum_next5CP =self::defineAcumCP(Reportes::sumRestDate("35", $date,"+"),$model->due_date,$model->soa,$acum_next5CP);
                        $acum_next6CP =self::defineAcumCP(Reportes::sumRestDate("42", $date,"+"),$model->due_date,$model->soa,$acum_next6CP);
                        $acum_next7CP =self::defineAcumCP(Reportes::sumRestDate("49", $date,"+"),$model->due_date,$model->soa,$acum_next7CP);
                        $acum_next8CP =self::defineAcumCP(Reportes::sumRestDate("56", $date,"+"),$model->due_date,$model->soa,$acum_next8CP);
                        $acum_next9CP =self::defineAcumCP(Reportes::sumRestDate("63", $date,"+"),$model->due_date,$model->soa,$acum_next9CP);
                        $acum_next10CP=self::defineAcumCP(Reportes::sumRestDate("70", $date,"+"),$model->due_date,$model->soa,$acum_next10CP);
                        $recopa.="<tr>
                                         <td ". self::defineColorTD(null, "white") ."> $model->name </td>
                                         <td ". self::defineColorTD(null, "white" ) .">". Yii::app()->format->format_decimal($model->soa). "</td>
                                         <td ". self::defineColorTD(null, "white" ) .">". $model->due_date ."</td>
                                         <td ". self::defineTD($date,Reportes::sumRestDate("7", $date,"-"),$model->due_date,$model->soa) ."</td>
                                         <td ". self::defineTD($date,$date,$model->due_date,$model->soa) ."</td>
                                         <td ". self::defineTD($date,Reportes::sumRestDate("7", $date,"+"),$model->due_date,$model->soa) ."</td>
                                         <td ". self::defineTD($date,Reportes::sumRestDate("14", $date,"+"),$model->due_date,$model->soa) ."</td>
                                         <td ". self::defineTD($date,Reportes::sumRestDate("21", $date,"+"),$model->due_date,$model->soa) ."</td>
                                         <td ". self::defineTD($date,Reportes::sumRestDate("28", $date,"+"),$model->due_date,$model->soa) ."</td>
                                         <td ". self::defineTD($date,Reportes::sumRestDate("35", $date,"+"),$model->due_date,$model->soa) ."</td>
                                         <td ". self::defineTD($date,Reportes::sumRestDate("42", $date,"+"),$model->due_date,$model->soa) ."</td>
                                         <td ". self::defineTD($date,Reportes::sumRestDate("49", $date,"+"),$model->due_date,$model->soa) ."</td>
                                         <td ". self::defineTD($date,Reportes::sumRestDate("56", $date,"+"),$model->due_date,$model->soa) ."</td>
                                         <td ". self::defineTD($date,Reportes::sumRestDate("63", $date,"+"),$model->due_date,$model->soa) ."</td>
                                         <td ". self::defineTD($date,Reportes::sumRestDate("70", $date,"+"),$model->due_date,$model->soa) ."</td>
                                        </tr>";
                   }
               }
               $recopa.="<tr>
                                <td colspan='13'></dt>
                               </tr>";
               $recopa.="<tr>
                                <td ". self::defineColorTD(null, "#3466B4")."colspan='3'>Totales C/C</td>
                                <td ". self::defineColorTD($acum_beforeCC, "white").">".Yii::app()->format->format_decimal($acum_beforeCC)."</td>
                                <td ". self::defineColorTD($acum_nowCC, "white") .  ">".Yii::app()->format->format_decimal($acum_nowCC  )."</td>
                                <td ". self::defineColorTD($acum_next1CC, "white") .">".Yii::app()->format->format_decimal($acum_next1CC)."</td>
                                <td ". self::defineColorTD($acum_next2CC, "white") .">".Yii::app()->format->format_decimal($acum_next2CC)."</td>
                                <td ". self::defineColorTD($acum_next3CC, "white") .">".Yii::app()->format->format_decimal($acum_next3CC)."</td>
                                <td ". self::defineColorTD($acum_next4CC, "white") .">".Yii::app()->format->format_decimal($acum_next4CC)."</td>
                                <td ". self::defineColorTD($acum_next5CC, "white") .">".Yii::app()->format->format_decimal($acum_next5CC)."</td>
                                <td ". self::defineColorTD($acum_next6CC, "white") .">".Yii::app()->format->format_decimal($acum_next6CC)."</td>
                                <td ". self::defineColorTD($acum_next7CC, "white") .">".Yii::app()->format->format_decimal($acum_next7CC)."</td>
                                <td ". self::defineColorTD($acum_next8CC, "white") .">".Yii::app()->format->format_decimal($acum_next8CC)."</td>
                                <td ". self::defineColorTD($acum_next9CC, "white") .">".Yii::app()->format->format_decimal($acum_next9CC)."</td>
                                <td ". self::defineColorTD($acum_next10CC,"white") .">".Yii::app()->format->format_decimal($acum_next10CC)."</td>
                               </tr>";
               $recopa.="<tr>
                                <td ". self::defineColorTD(null, "#E99241")."colspan='3'>Totales C/P</td>
                                <td ". self::defineColorTD($acum_beforeCP, "white").">".Yii::app()->format->format_decimal($acum_beforeCP)."</td>
                                <td ". self::defineColorTD($acum_nowCP, "white")   .">".Yii::app()->format->format_decimal($acum_nowCP)."</td>
                                <td ". self::defineColorTD($acum_next1CP, "white") .">".Yii::app()->format->format_decimal($acum_next1CP)."</td>
                                <td ". self::defineColorTD($acum_next2CP, "white") .">".Yii::app()->format->format_decimal($acum_next2CP)."</td>
                                <td ". self::defineColorTD($acum_next3CP, "white") .">".Yii::app()->format->format_decimal($acum_next3CP)."</td>
                                <td ". self::defineColorTD($acum_next4CP, "white") .">".Yii::app()->format->format_decimal($acum_next4CP)."</td>
                                <td ". self::defineColorTD($acum_next5CP, "white") .">".Yii::app()->format->format_decimal($acum_next5CP)."</td>
                                <td ". self::defineColorTD($acum_next6CP, "white") .">".Yii::app()->format->format_decimal($acum_next6CP)."</td>
                                <td ". self::defineColorTD($acum_next7CP, "white") .">".Yii::app()->format->format_decimal($acum_next7CP)."</td>
                                <td ". self::defineColorTD($acum_next8CP, "white") .">".Yii::app()->format->format_decimal($acum_next8CP)."</td>
                                <td ". self::defineColorTD($acum_next9CP, "white") .">".Yii::app()->format->format_decimal($acum_next9CP)."</td>
                                <td ". self::defineColorTD($acum_next10CP,"white") .">".Yii::app()->format->format_decimal($acum_next10CP)."</td>
                               </tr>";
               $recopa.="<tr ". self::defineColorTD(null, "#049C47").">
                                <td colspan='3'>Posición Neta</td>
                                <td >".Yii::app()->format->format_decimal($acum_beforeCC + $acum_beforeCP)."</td>
                                <td >".Yii::app()->format->format_decimal($acum_nowCC + $acum_nowCP)."</td>
                                <td >".Yii::app()->format->format_decimal($acum_next1CC + $acum_next1CP)."</td>
                                <td >".Yii::app()->format->format_decimal($acum_next2CC + $acum_next2CP)."</td>
                                <td >".Yii::app()->format->format_decimal($acum_next3CC + $acum_next3CP)."</td>
                                <td >".Yii::app()->format->format_decimal($acum_next4CC + $acum_next4CP)."</td>
                                <td >".Yii::app()->format->format_decimal($acum_next5CC + $acum_next5CP)."</td>
                                <td >".Yii::app()->format->format_decimal($acum_next6CC + $acum_next6CP)."</td>
                                <td >".Yii::app()->format->format_decimal($acum_next7CC + $acum_next7CP)."</td>
                                <td >".Yii::app()->format->format_decimal($acum_next8CC + $acum_next8CP)."</td>
                                <td >".Yii::app()->format->format_decimal($acum_next9CC + $acum_next9CP)."</td>
                                <td >".Yii::app()->format->format_decimal($acum_next10CC + $acum_next10CP)."</td>
                               </tr>";
              $recopa.="</table>";
           return $recopa;
        }
        
        public static function defineAcumCC($dateActual,$due_date,$amount,$acumulado)
        {
            if($due_date >= $dateActual && $due_date <= Reportes::sumRestDate("6", $dateActual,"+")){
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
            if($due_date >= $dateActual && $due_date <= Reportes::sumRestDate("6", $dateActual,"+")){
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
        public static function defineTD($date,$dateNow,$due_date,$amount)
        {
            if($due_date >= $dateNow && $due_date <= Reportes::sumRestDate("6", $dateNow,"+")){
                return  self::defineColorTD($amount,null) .">".Yii::app()->format->format_decimal($amount);
            }
            elseif($due_date < Reportes::sumRestDate("7", $date,"-")){
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
        

        public static function getModel($date,$filter_oper)
        {
            if($filter_oper=="0") $filter="(i.amount+(p.amount-n.amount)) AS amount";
            if($filter_oper=="1") $filter="CASE WHEN ABS(i.amount+(p.amount-n.amount)) > ABS(2000) THEN (i.amount+(p.amount-n.amount)) END AS amount";
            if($filter_oper=="2") $filter="CASE WHEN ABS(i.amount+(p.amount-n.amount)) < ABS(2000) THEN (i.amount+(p.amount-n.amount)) END AS amount";
  
            $sql="SELECT cg.id, 
                /*Traigo el nombre del grupo*/
                   cg.name,
                   /*Traigo el soa total*/
                   (SELECT  {$filter}
                FROM (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document where id_type_accounting_document=9 and id_carrier IN(Select id from carrier where id_carrier_groups = cg.id)) i,
                         (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(1,3,8,15) AND id_carrier IN(Select id from carrier where id_carrier_groups = cg.id) AND issue_date<='{$date}') p,
                         (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(2,4,7,14) AND id_carrier IN(Select id from carrier where id_carrier_groups = cg.id) AND issue_date<='{$date}') n) AS soa,
                /*Traigo el mayor due_date*/
                (SELECT MAX(date)
                 FROM (SELECT CASE WHEN(SELECT tp.expiration
                                        FROM carrier c, contrato con, contrato_termino_pago ctp, termino_pago tp
                                        WHERE con.id_carrier=c.id AND ctp.id_contrato=con.id AND ctp.id_termino_pago=tp.id AND con.end_date IS NULL AND con.sign_date IS NOT NULL AND c.id IN(Select id from carrier where id_carrier_groups = cg.id)
                                        LIMIT 1)=0 THEN MAX(issue_date)
                                   WHEN(SELECT tp.expiration
                                        FROM carrier c, contrato con, contrato_termino_pago ctp, termino_pago tp
                                        WHERE con.id_carrier=c.id AND ctp.id_contrato=con.id AND ctp.id_termino_pago=tp.id AND con.end_date IS NULL AND con.sign_date IS NOT NULL AND c.id IN(Select id from carrier where id_carrier_groups = cg.id)
                                        LIMIT 1)=3 THEN CAST(MAX(issue_date) + interval '3 days' AS date)
                               WHEN(SELECT tp.expiration
                                FROM carrier c, contrato con, contrato_termino_pago ctp, termino_pago tp
                                WHERE con.id_carrier=c.id AND ctp.id_contrato=con.id AND ctp.id_termino_pago=tp.id AND con.end_date IS NULL AND con.sign_date IS NOT NULL AND c.id IN(Select id from carrier where id_carrier_groups = cg.id)
                                LIMIT 1)=5 THEN CAST(MAX(issue_date) + interval '5 days' AS date)
                               WHEN(SELECT tp.expiration
                                FROM carrier c, contrato con, contrato_termino_pago ctp, termino_pago tp
                                WHERE con.id_carrier=c.id AND ctp.id_contrato=con.id AND ctp.id_termino_pago=tp.id AND con.end_date IS NULL AND con.sign_date IS NOT NULL AND c.id IN(Select id from carrier where id_carrier_groups = cg.id)
                                LIMIT 1)=7 THEN CAST(MAX(issue_date) + interval '7 days' AS date)
                               WHEN(SELECT tp.expiration
                                FROM carrier c, contrato con, contrato_termino_pago ctp, termino_pago tp
                                WHERE con.id_carrier=c.id AND ctp.id_contrato=con.id AND ctp.id_termino_pago=tp.id AND con.end_date IS NULL AND con.sign_date IS NOT NULL AND c.id IN(Select id from carrier where id_carrier_groups = cg.id)
                                LIMIT 1)=15 THEN CAST(MAX(issue_date) + interval '15 days' AS date)
                               WHEN(SELECT tp.expiration
                                FROM carrier c, contrato con, contrato_termino_pago ctp, termino_pago tp
                                WHERE con.id_carrier=c.id AND ctp.id_contrato=con.id AND ctp.id_termino_pago=tp.id AND con.end_date IS NULL AND con.sign_date IS NOT NULL AND c.id IN(Select id from carrier where id_carrier_groups = cg.id)
                                LIMIT 1)=30 THEN CAST(MAX(issue_date) + interval '30 days' AS date)
                               WHEN(SELECT tp.expiration
                                FROM carrier c, contrato con, contrato_termino_pago ctp, termino_pago tp
                                WHERE con.id_carrier=c.id AND ctp.id_contrato=con.id AND ctp.id_termino_pago=tp.id AND con.end_date IS NULL AND con.sign_date IS NOT NULL AND c.id IN(Select id from carrier where id_carrier_groups = cg.id)
                                LIMIT 1) IS NULL THEN CAST(MAX(issue_date) + interval '7 days' AS date)END AS date 
                       FROM accounting_document 
                       WHERE id_carrier IN(Select id from carrier where id_carrier_groups = cg.id) AND id_type_accounting_document=1
                       UNION
                       SELECT CASE WHEN(SELECT tp.expiration
                         FROM carrier c, contrato con, contrato_termino_pago_supplier ctp, termino_pago tp
                         WHERE con.id_carrier=c.id AND ctp.id_contrato=con.id AND ctp.id_termino_pago_supplier=tp.id AND con.end_date IS NULL AND con.sign_date IS NOT NULL AND c.id IN(Select id from carrier where id_carrier_groups = cg.id)
                         LIMIT 1)=0 THEN MAX(valid_received_date)
                        WHEN(SELECT tp.expiration
                         FROM carrier c, contrato con, contrato_termino_pago_supplier ctp, termino_pago tp
                         WHERE con.id_carrier=c.id AND ctp.id_contrato=con.id AND ctp.id_termino_pago_supplier=tp.id AND con.end_date IS NULL AND con.sign_date IS NOT NULL AND c.id IN(Select id from carrier where id_carrier_groups = cg.id)
                         LIMIT 1)=3 THEN CAST(MAX(valid_received_date) + interval '3 days' AS date)
                        WHEN(SELECT tp.expiration
                         FROM carrier c, contrato con, contrato_termino_pago_supplier ctp, termino_pago tp
                         WHERE con.id_carrier=c.id AND ctp.id_contrato=con.id AND ctp.id_termino_pago_supplier=tp.id AND con.end_date IS NULL AND con.sign_date IS NOT NULL AND c.id IN(Select id from carrier where id_carrier_groups = cg.id)
                         LIMIT 1)=5 THEN CAST(MAX(valid_received_date) + interval '5 days' AS date)
                        WHEN(SELECT tp.expiration
                         FROM carrier c, contrato con, contrato_termino_pago_supplier ctp, termino_pago tp
                         WHERE con.id_carrier=c.id AND ctp.id_contrato=con.id AND ctp.id_termino_pago_supplier=tp.id AND con.end_date IS NULL AND con.sign_date IS NOT NULL AND c.id IN(Select id from carrier where id_carrier_groups = cg.id)
                         LIMIT 1)=7 THEN CAST(MAX(valid_received_date) + interval '7 days' AS date)
                        WHEN(SELECT tp.expiration
                         FROM carrier c, contrato con, contrato_termino_pago_supplier ctp, termino_pago tp
                         WHERE con.id_carrier=c.id AND ctp.id_contrato=con.id AND ctp.id_termino_pago_supplier=tp.id AND con.end_date IS NULL AND con.sign_date IS NOT NULL AND c.id IN(Select id from carrier where id_carrier_groups = cg.id)
                         LIMIT 1)=15 THEN CAST(MAX(valid_received_date) + interval '15 days' AS date)
                        WHEN(SELECT tp.expiration
                         FROM carrier c, contrato con, contrato_termino_pago_supplier ctp, termino_pago tp
                         WHERE con.id_carrier=c.id AND ctp.id_contrato=con.id AND ctp.id_termino_pago_supplier=tp.id AND con.end_date IS NULL AND con.sign_date IS NOT NULL AND c.id IN(Select id from carrier where id_carrier_groups = cg.id)
                         LIMIT 1)=30 THEN CAST(MAX(valid_received_date) + interval '30 days' AS date)
                        WHEN(SELECT tp.expiration
                         FROM carrier c, contrato con, contrato_termino_pago_supplier ctp, termino_pago tp
                         WHERE con.id_carrier=c.id AND ctp.id_contrato=con.id AND ctp.id_termino_pago_supplier=tp.id AND con.end_date IS NULL AND con.sign_date IS NOT NULL AND c.id IN(Select id from carrier where id_carrier_groups = cg.id)
                         LIMIT 1) IS NULL THEN CAST(MAX(valid_received_date) + interval '7 days' AS date) END AS date 
                    FROM accounting_document 
                    WHERE id_carrier IN(Select id from carrier where id_carrier_groups = cg.id) AND id_type_accounting_document=2) d) AS due_date
                FROM carrier_groups cg
                ORDER BY cg.name ASC";
            return AccountingDocument::model()->findAllBySql($sql);
        }
        public static function defineFilterExpired($due_date,$date,$filter)
        {
            switch ($filter) {
                case "":
                    return $due_date!=null;//trae todos los soa vencidos de mas de dos semanas
                    break;
                case "No":
                    return $due_date>=Reportes::sumRestDate("14", $date,"-");
                    break;
                default:
                    return $due_date!=null;
                    break;
            }
        }
    }
    ?>