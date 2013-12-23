 <?php

    /**
     * @package reportes
     */
    class refac extends Reportes 
    {
        public static function reporte($fecha_from,$fecha_to) 
        {
            
            $fecha=date('Y-m-d');
            $style_title="style='background:#96B6E6;text-align:center;'";$style_description="style='background:silver;text-align:center;'";$style_basic="style='border:1px solid black;text-align:left;'";
            $style_captura="style='border:1px solid black;background:rgb(231, 148, 59);text-align:center;'";$style_sori="style='border:1px solid black;background:#96B6E6;text-align:center;'";
            $style_diference="style='border:1px solid black;background:#18B469;text-align:center;'";$style_totals="style='border:1px solid black;background:silver;text-align:center;'";
            $acumulado_captura=0;
            $acumulado_sori=0;
            $acumulado_diference=0;
                       
            $facturas = refac::getFacturas($fecha_from, $fecha_to); //trae el sql pricipal de sori
            $seg=count($facturas);
            ini_set('max_execution_time', $seg);
            $tabla_refac="<table>";
            $tabla_refac.="<tr rowspan='2'>
                            <td colspan='10'></td>
                           </tr>";
           $tabla_refac.="<tr>
                          <td " . $style_title . " colspan='10'><b>REFAC " . Reportes::define_num_dias($fecha_from, $fecha_to) . " " . str_replace("-","",$fecha_from) . " - " . str_replace("-","",$fecha_to) . " al " . str_replace("-","",$fecha) . "</b></td>
                          </tr>";
           $tabla_refac.="<tr>
                            <td colspan='10'></td>
                          </tr>
                          <tr>
                            <td " . $style_title . "><b>TIPO DE FACTURACION</b></td>
                            <td " . $style_description . " colspan='3'>" . Reportes::define_num_dias($fecha_from, $fecha_to) . "</td>
                            <td colspan='6'></td>
                          </tr>";
           $tabla_refac.="<tr>
                            <td colspan='10'></td>
                          </tr>
                            <tr>
                            <td " . $style_title . "><b>PERIODO</b></td>
                            <td " . $style_description . " colspan='3'>" . Utility::formatDateSINE($fecha_from,"F j") . " - " . Utility::formatDateSINE($fecha_to,"F j") . "</td>
                            <td colspan='6'></td>    
                          </tr>";
           $tabla_refac.="<tr>
                            <td colspan='10'></td>
                          </tr>
                          <tr>
                            <td colspan='3'" .$style_captura. "><b>CAPTURA</b></td>
                            <td colspan='4'" .$style_sori. "><b>FACTURACION SORI</b></td>
                            <td colspan='3'" .$style_diference. "><b>DIFERENCIAS</b></td>
                          </tr>";
           $tabla_refac.="<tr>
                          <td " .$style_captura. "><b>OPERADOR</b></td>
                          <td " .$style_captura. "><b>MINUTOS</b></td>
                          <td " .$style_captura. "><b>MONTO $</b></td>
                      
                          <td " .$style_sori. "><b>OPERADOR</b></td>
                          <td " .$style_sori. "><b>MINUTOS</b></td>
                          <td " .$style_sori. "><b>MONTO</b></td>
                          <td " .$style_sori. "><b>Num FACTURA</b></td>
          
                          <td " .$style_diference. "><b>OPERADOR</b></td>
                          <td " .$style_diference. "><b>MINUTOS</b></td>
                          <td " .$style_diference. "><b>MONTO</b></td>
                          </tr>";
           foreach ($facturas as $key => $factura)
           {
              $model_captura = refac::get_Model_balance($factura); //trae el sql pricipal de captura
              $acumulado_captura=Reportes::define_total_captura($factura,$acumulado_captura); 
              $acumulado_sori=Reportes::define_total_sori($factura,$acumulado_sori);
              $dif_amount=$factura->amount - $model_captura->revenue;
              $dif_minutes=$factura->minutes - $model_captura->minutes;
              $acumulado_diference=Reportes::define_total_diference($dif_amount,$acumulado_diference);
              
                $tabla_refac.="<tr>";
                $tabla_refac.="<td " . $style_basic . ">" .$factura->carrier. "</td>
                               <td " . $style_basic . ">" .Yii::app()->format->format_decimal($model_captura->minutes)."</td>
                               <td " . $style_basic . ">" .Yii::app()->format->format_decimal($model_captura->revenue,3). "</td>";

                $tabla_refac.="<td " . $style_basic . ">" .$factura->carrier. "</td>
                               <td " . $style_basic . ">" .$factura->minutes. "</td>
                               <td " . $style_basic . ">" .Yii::app()->format->format_decimal($factura->amount,3). "</td>
                               <td " . $style_basic . ">" .$factura->doc_number. "</td>";

                $tabla_refac.="<td " . $style_basic . ">" .$factura->carrier. "</td>
                               <td " . $style_basic . ">" .Yii::app()->format->format_decimal($dif_minutes). "</td>
                               <td " . $style_basic . ">" .Yii::app()->format->format_decimal($dif_amount,3). "</td>";
                $tabla_refac.="</tr>";
                $model_captura=null;
           }
           
           $tabla_refac.="<tr>";
           $tabla_refac.="<td " .$style_captura. "><b>TOTAL</b></td>
                          <td " .$style_captura. "></td>
                          <td " .$style_totals. "><b>" .Yii::app()->format->format_decimal($acumulado_captura,3). "</b></td>";
           
           $tabla_refac.="<td " .$style_sori. "><b>TOTAL</b></td>
                          <td " .$style_sori. "></td>
                          <td " .$style_totals. "><b>" .Yii::app()->format->format_decimal($acumulado_sori,3). "</b></td>
                          <td " .$style_sori. "></td>";
           
           $tabla_refac.="<td " .$style_diference. "><b>TOTAL</b></td>
                          <td " .$style_diference. "></td>
                          <td " .$style_totals. "><b>" .Yii::app()->format->format_decimal($acumulado_diference,3). "</b></td>";
           $tabla_refac.="</tr>";
           
           $tabla_refac.="</table>";
           
           return $tabla_refac;
        }
        /**
         * trae el sql pricipal de sori
         * @param type $fecha_from
         * @param type $fecha_to
         * @return type
         */
        private static function getFacturas($fecha_from, $fecha_to) 
        {
            $sql="SELECT a.id, a.doc_number, a.from_date, a.to_date,a.amount, a.minutes, a.id_carrier, c.name AS carrier
                  FROM accounting_document a, carrier c
                  WHERE a.id_carrier=c.id
                    AND id_type_accounting_document=(SELECT id FROM type_accounting_document WHERE name='Factura Enviada')
                    AND from_date>='{$fecha_from}'
                    AND to_date<='{$fecha_to}'
                  ORDER BY from_date";
            return AccountingDocument::model()->findAllBySql($sql);
        }
        
        private static function get_Model_balance($model) 
        {
            $sql="SELECT SUM(minutes) AS  minutes, SUM(revenue) as revenue
                  FROM balance
                  WHERE date_balance>='{$model->from_date}' AND date_balance<='{$model->to_date}'
                    AND id_carrier_customer={$model->id_carrier}
                    AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier')
                    AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
                    AND id_destination IS NULL"; 
            return Balance::model()->findBySql($sql);
        }
    }
    ?>


