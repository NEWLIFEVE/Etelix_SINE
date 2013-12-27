 <?php

    /**
     * @package reportes
     */
    class refac_refi_prov extends Reportes 
    {
        public static function reporte($fecha_from,$fecha_to,$tipo_report) 
        {
            $fecha=date('Y-m-d');
            $style_title="style='background:#96B6E6;text-align:center;'";$style_description="style='background:silver;text-align:center;'";$style_basic="style='border:1px solid black;text-align:left;'";
            $style_basic_number="style='border:1px solid black;text-align:right;'";$style_captura="style='border:1px solid black;background:rgb(231, 148, 59);text-align:center;'";$style_sori="style='border:1px solid black;background:#96B6E6;text-align:center;'";
            $style_diference="style='border:1px solid black;background:#18B469;text-align:center;'";$style_totals="style='border:1px solid black;background:silver;text-align:center;'";
            $acumulado_captura=0;
            $acumulado_sori=0;
            $acumulado_diference=0;
                       
            $facturas = refac_refi_prov::getFacturas($fecha_from, $fecha_to,$tipo_report); //trae el sql pricipal de sori
            $seg=count($facturas);
            ini_set('max_execution_time', $seg);
           $tabla_reporte="<table>";
           $tabla_reporte.="<tr rowspan='2'>
                             <td colspan='10'></td>
                            </tr>";
           $tabla_reporte.="<tr>
                             <td " . $style_title . " colspan='10'><b>". $tipo_report . " " . Reportes::define_num_dias($fecha_from, $fecha_to) . " " . str_replace("-","",$fecha_from) . " - " . str_replace("-","",$fecha_to) . " al " . str_replace("-","",$fecha) . "</b></td>
                            </tr>";
           $tabla_reporte.="<tr>
                            <td colspan='10'></td>
                            </tr>
                            <tr>
                             <td " . $style_title . "><b>TIPO DE FACTURACION</b></td>
                             <td " . $style_description . " colspan='3'>" . Reportes::define_num_dias($fecha_from, $fecha_to) . "</td>
                             <td colspan='6'></td>
                            </tr>";
           $tabla_reporte.="<tr>
                             <td colspan='10'></td>
                            </tr>
                            <tr>
                             <td " . $style_title . "><b>PERIODO</b></td>
                             <td " . $style_description . " colspan='3'>" . Utility::formatDateSINE($fecha_from,"F j") . " - " . Utility::formatDateSINE($fecha_to,"F j") . "</td>
                             <td colspan='6'></td>    
                            </tr>";
           $tabla_reporte.="<tr>
                             <td colspan='10'></td>
                            </tr>
                            <tr>
                             <td colspan='3'" .$style_captura. "><b>CAPTURA</b></td>
                             <td colspan='4'" .$style_sori. "><b>FACTURACION SORI</b></td>
                             <td colspan='3'" .$style_diference. "><b>DIFERENCIAS</b></td>
                            </tr>";
           $tabla_reporte.="<tr>
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
              $model_captura = refac_refi_prov::get_Model_balance($factura,$tipo_report); //trae el sql pricipal de captura
              $acumulado_captura=Reportes::define_total_captura($model_captura,$acumulado_captura); 
              $acumulado_sori=Reportes::define_total_sori($factura,$acumulado_sori);
              $dif_amount=$factura->amount - $model_captura->revenue;
              $dif_minutes=$factura->minutes - $model_captura->minutes;
              $acumulado_diference=Reportes::define_total_diference($dif_amount,$acumulado_diference);
              
                $tabla_reporte.="<tr>";
                 $tabla_reporte.="<td $style_basic >" .$factura->carrier. "</td>
                                  <td $style_basic_number >" .Yii::app()->format->format_decimal($model_captura->minutes,3). "</td>
                                  <td $style_basic_number >" .Yii::app()->format->format_decimal($model_captura->revenue,3). "</td>";

                 $tabla_reporte.="<td $style_basic >" . $factura->carrier. "</td>
                                  <td $style_basic_number >" .Yii::app()->format->format_decimal($factura->minutes,3). "</td>
                                  <td $style_basic_number >" .Yii::app()->format->format_decimal($factura->amount,3). "</td>
                                  <td $style_basic >" .$factura->doc_number. "</td>";

                 $tabla_reporte.="<td $style_basic >" .$factura->carrier. "</td>
                                  <td $style_basic_number >" .Yii::app()->format->format_decimal($dif_minutes,3). "</td>
                                  <td $style_basic_number >" .Yii::app()->format->format_decimal($dif_amount,3). "</td>";
                $tabla_reporte.="</tr>";
                $model_captura=null;
           }
           $tabla_reporte.="<tr>";
            $tabla_reporte.="<td $style_captura ><b>TOTAL</b></td>
                             <td $style_captura ></td>
                             <td $style_totals ><b>" .Yii::app()->format->format_decimal($acumulado_captura,3). "</b></td>";
           
            $tabla_reporte.="<td $style_sori ><b>TOTAL</b></td>
                             <td $style_sori ></td>
                             <td $style_totals ><b>" .Yii::app()->format->format_decimal($acumulado_sori,3). "</b></td>
                             <td $style_sori ></td>";
           
            $tabla_reporte.="<td $style_diference ><b>TOTAL</b></td>
                             <td $style_diference ></td>
                             <td $style_totals ><b>" .Yii::app()->format->format_decimal($acumulado_diference,3). "</b></td>";
           $tabla_reporte.="</tr>";
           
           $tabla_reporte.="</table>";
           
           return $tabla_reporte;
        }
        /** ejecuta la consulta a todos los datos de facturacion de sori, la unica particularidad es que dependiendo de la variable $tipo_report, cambia el id_type_accounting_document
         * trae el sql pricipal de sori
         * @param type $fecha_from
         * @param type $fecha_to
         * @return type
         */
        private static function getFacturas($fecha_from, $fecha_to,$tipo_report) 
        {
            if($tipo_report=="REFAC") $type_accounting_document="Factura Enviada";
            else $type_accounting_document="Factura Recibida";

            $sql="SELECT a.id, a.doc_number, a.from_date, a.to_date,a.amount, a.minutes, a.id_carrier, c.name AS carrier
                  FROM accounting_document a, carrier c
                  WHERE a.id_carrier=c.id
                    AND id_type_accounting_document=(SELECT id FROM type_accounting_document WHERE name='{$type_accounting_document}')
                    AND from_date>='{$fecha_from}'
                    AND to_date<='{$fecha_to}'
                  ORDER BY from_date";
            return AccountingDocument::model()->findAllBySql($sql);
        }
        /**
         * ejecuta la consulta para traer el trafico de minutos y monto por cada carrier que pase por el foreach
         * @param type $model
         * @param type $tipo_report
         * @return type
         */
        private static function get_Model_balance($model,$tipo_report) 
        {
            if($tipo_report=="REFAC")                                                                            //complemento del sql para generar el reporte refac, determinado por la variable $tipo_report
                   $where_and="AND id_carrier_customer={$model->id_carrier}
                               AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier')";  //complemento sql para generar el reporte refi_prov, determinado por la variable $tipo_report
             
              else $where_and="AND id_carrier_supplier={$model->id_carrier}";                                    //sql destinado a obtener la data de trafico por captura...
           
            $sql="SELECT SUM(minutes) AS  minutes, SUM(revenue) as revenue 
                  FROM balance
                  WHERE date_balance>='{$model->from_date}' AND date_balance<='{$model->to_date}'
                    AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
                    AND id_destination IS NULL
                    $where_and "; 
            
            return Balance::model()->findBySql($sql);
        }
    }
    ?>