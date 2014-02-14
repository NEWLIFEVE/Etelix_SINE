 <?php

    /**
     * @package reportes
     */
    class recopa extends Reportes 
    {
        public static function reporte($date) 
        {
            $acum_beforeCC=$acum_nowCC=$acum_next1CC=$acum_next2CC=$acum_next3CC=$acum_next4CC=$acum_next5CC=$acum_next6CC=$acum_next7CC=$acum_next8CC=$acum_next9CC=$acum_next10CC=$acum_beforeCP=$acum_nowCP=$acum_next1CP=$acum_next2CP=$acum_next3CP=$acum_next4CP=$acum_next5CP=$acum_next6CP=$acum_next7CP=$acum_next8CP=$acum_next9CP=$acum_next10CP=0;
            $carrierGroups=Recredi::getAllGroups();
            $seg=count($carrierGroups)*2;
            ini_set('max_execution_time', $seg);
            
            $tabla_recopa= "<h1>RECOPA <h3>(".$date." - ".date("g:i a").")</h3></h1>";
                $tabla_recopa.="<table>
                                 <tr>
                                  <td ". self::defineColorTD(null, "silver" ) ."> Operadores </td>
                                  <td ". self::defineColorTD(null, "#3466B4") .">SOA</td>
                                  <td ". self::defineColorTD(null, "silver" ) .">Due date</td>
                                  <td ". self::defineColorTD(null, "#F89289") .">". Reportes::define_due_date("7", $date,"-") ."</td>
                                  <td ". self::defineColorTD(null, "#06ACFA") ."> $date </td>
                                  <td ". self::defineColorTD(null, "#049C47") .">". Reportes::define_due_date("7", $date,"+") ."</td>
                                  <td ". self::defineColorTD(null, "#049C47") .">". Reportes::define_due_date("14", $date,"+") ."</td>
                                  <td ". self::defineColorTD(null, "#049C47") .">". Reportes::define_due_date("21", $date,"+") ."</td>
                                  <td ". self::defineColorTD(null, "#049C47") .">". Reportes::define_due_date("28", $date,"+") ."</td>
                                  <td ". self::defineColorTD(null, "#049C47") .">". Reportes::define_due_date("35", $date,"+") ."</td>
                                  <td ". self::defineColorTD(null, "#049C47") .">". Reportes::define_due_date("42", $date,"+") ."</td>
                                  <td ". self::defineColorTD(null, "#049C47") .">". Reportes::define_due_date("49", $date,"+") ."</td>
                                  <td ". self::defineColorTD(null, "#049C47") .">". Reportes::define_due_date("56", $date,"+") ."</td>
                                  <td ". self::defineColorTD(null, "#049C47") .">". Reportes::define_due_date("63", $date,"+") ."</td>
                                  <td ". self::defineColorTD(null, "#049C47") .">". Reportes::define_due_date("70", $date,"+") ."</td>
                                 </tr>";
                foreach ($carrierGroups as $key => $group)
                {
                    $SOA=Recredi::getSoaCarrier($group->id,$date);
                    $SOA_date_top=Recredi::getSoaDateCarrier($group->id);

                    $tabla_recopa.=" <tr>
                                      <td ". self::defineColorTD(null, "white") ."> $group->name </td>
                                      <td ". self::defineColorTD(null, "white" ) .">". Yii::app()->format->format_decimal($SOA->amount). "</td>
                                      <td ". self::defineColorTD(null, "white" ) .">". $SOA_date_top ."</td>
                                      <td ". self::defineTD(Reportes::define_due_date("7", $date,"-"),$SOA_date_top,$SOA->amount) ."</td>
                                      <td ". self::defineTD($date,$SOA_date_top,$SOA->amount) ."</td>
                                      <td ". self::defineTD(Reportes::define_due_date("7", $date,"+"),$SOA_date_top,$SOA->amount) ."</td>
                                      <td ". self::defineTD(Reportes::define_due_date("14", $date,"+"),$SOA_date_top,$SOA->amount) ."</td>
                                      <td ". self::defineTD(Reportes::define_due_date("21", $date,"+"),$SOA_date_top,$SOA->amount) ."</td>
                                      <td ". self::defineTD(Reportes::define_due_date("28", $date,"+"),$SOA_date_top,$SOA->amount) ."</td>
                                      <td ". self::defineTD(Reportes::define_due_date("35", $date,"+"),$SOA_date_top,$SOA->amount) ."</td>
                                      <td ". self::defineTD(Reportes::define_due_date("42", $date,"+"),$SOA_date_top,$SOA->amount) ."</td>
                                      <td ". self::defineTD(Reportes::define_due_date("49", $date,"+"),$SOA_date_top,$SOA->amount) ."</td>
                                      <td ". self::defineTD(Reportes::define_due_date("56", $date,"+"),$SOA_date_top,$SOA->amount) ."</td>
                                      <td ". self::defineTD(Reportes::define_due_date("63", $date,"+"),$SOA_date_top,$SOA->amount) ."</td>
                                      <td ". self::defineTD(Reportes::define_due_date("70", $date,"+"),$SOA_date_top,$SOA->amount) ."</td>
                                     </tr>";
                    
                    $acum_beforeCC=self::defineAcumCC(Reportes::define_due_date("7", $date,"-"),$SOA_date_top,$SOA->amount,$acum_beforeCC);
                    var_dump("acum before cc: ".$acum_beforeCC);
                    $acum_nowCC   =self::defineAcumCC($date,$SOA_date_top,$SOA->amount,$acum_nowCC);
                    $acum_next1CC =self::defineAcumCC(Reportes::define_due_date("7", $date,"+"),$SOA_date_top,$SOA->amount,$acum_next1CC);
                    $acum_next2CC =self::defineAcumCC(Reportes::define_due_date("14", $date,"+"),$SOA_date_top,$SOA->amount,$acum_next2CC);
                    $acum_next3CC =self::defineAcumCC(Reportes::define_due_date("21", $date,"+"),$SOA_date_top,$SOA->amount,$acum_next3CC);
                    $acum_next4CC =self::defineAcumCC(Reportes::define_due_date("28", $date,"+"),$SOA_date_top,$SOA->amount,$acum_next4CC);
                    $acum_next5CC =self::defineAcumCC(Reportes::define_due_date("35", $date,"+"),$SOA_date_top,$SOA->amount,$acum_next5CC);
                    $acum_next6CC =self::defineAcumCC(Reportes::define_due_date("42", $date,"+"),$SOA_date_top,$SOA->amount,$acum_next6CC);
                    $acum_next7CC =self::defineAcumCC(Reportes::define_due_date("49", $date,"+"),$SOA_date_top,$SOA->amount,$acum_next7CC);
                    $acum_next8CC =self::defineAcumCC(Reportes::define_due_date("56", $date,"+"),$SOA_date_top,$SOA->amount,$acum_next8CC);
                    $acum_next9CC =self::defineAcumCC(Reportes::define_due_date("63", $date,"+"),$SOA_date_top,$SOA->amount,$acum_next9CC);
                    $acum_next10CC=self::defineAcumCC(Reportes::define_due_date("70", $date,"+"),$SOA_date_top,$SOA->amount,$acum_next10CC);

                    $acum_beforeCP=self::defineAcumCP(Reportes::define_due_date("7", $date,"-"),$SOA_date_top,$SOA->amount,$acum_beforeCP);
                    var_dump("acum before cp: ".$acum_beforeCP);
                    $acum_nowCP   =self::defineAcumCP($date,$SOA_date_top,$SOA->amount,$acum_nowCP);
                    $acum_next1CP =self::defineAcumCP(Reportes::define_due_date("7", $date,"+"),$SOA_date_top,$SOA->amount,$acum_next1CP);
                    $acum_next2CP =self::defineAcumCP(Reportes::define_due_date("14", $date,"+"),$SOA_date_top,$SOA->amount,$acum_next2CP);
                    $acum_next3CP =self::defineAcumCP(Reportes::define_due_date("21", $date,"+"),$SOA_date_top,$SOA->amount,$acum_next3CP);
                    $acum_next4CP =self::defineAcumCP(Reportes::define_due_date("28", $date,"+"),$SOA_date_top,$SOA->amount,$acum_next4CP);
                    $acum_next5CP =self::defineAcumCP(Reportes::define_due_date("35", $date,"+"),$SOA_date_top,$SOA->amount,$acum_next5CP);
                    $acum_next6CP =self::defineAcumCP(Reportes::define_due_date("42", $date,"+"),$SOA_date_top,$SOA->amount,$acum_next6CP);
                    $acum_next7CP =self::defineAcumCP(Reportes::define_due_date("49", $date,"+"),$SOA_date_top,$SOA->amount,$acum_next7CP);
                    $acum_next8CP =self::defineAcumCP(Reportes::define_due_date("56", $date,"+"),$SOA_date_top,$SOA->amount,$acum_next8CP);
                    $acum_next9CP =self::defineAcumCP(Reportes::define_due_date("63", $date,"+"),$SOA_date_top,$SOA->amount,$acum_next9CP);
                    $acum_next10CP=self::defineAcumCP(Reportes::define_due_date("70", $date,"+"),$SOA_date_top,$SOA->amount,$acum_next10CP);
               }
               $tabla_recopa.="<tr>
                                <td colspan='3'></dt>
                               </tr>";
               $tabla_recopa.="<tr>
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
               $tabla_recopa.="<tr>
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
               $tabla_recopa.="<tr ". self::defineColorTD(null, "#049C47").">
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
                $tabla_recopa.="</table>";
            echo $tabla_recopa;
        }
        public static function defineTD($dateActual,$SOA_date_top,$amount)
        {
            if($SOA_date_top >= $dateActual && $SOA_date_top <= Reportes::define_due_date("6", $dateActual,"+")){
                return  self::defineColorTD($amount,null) .">".Yii::app()->format->format_decimal($amount);
            }else{
                return  self::defineColorTD(null, "white") .">";
            }
        }
        public static function defineAcumCC($dateActual,$SOA_date_top,$amount,$acumulado)
        {
            if($SOA_date_top >= $dateActual && $SOA_date_top <= Reportes::define_due_date("6", $dateActual,"+")){
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
        public static function defineAcumCP($dateActual,$SOA_date_top,$amount,$acumulado)
        {
            if($SOA_date_top >= $dateActual && $SOA_date_top <= Reportes::define_due_date("6", $dateActual,"+")){
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
        public static function defineColorTD($amount, $backColor)
        {
            if($amount>0){
                return "style='border:1px solid black;color:white;text-align:left;background:#3466B4;'";
            }elseif($amount<0){
                return "style='border:1px solid black;color:white;text-align:left;background:#E99241;'"; 
            }
            elseif($amount==null){
                $colorText="white";
                if($backColor=="white")$colorText="black";
                return "style='border:1px solid black;color:$colorText;text-align:left;background:$backColor;'";
            }
        }
    }
    ?>