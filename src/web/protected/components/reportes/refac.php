 <?php

    /**
     * @package reportes
     */
    class refac extends Reportes 
    {
        public static function reporte($fecha_from,$fecha_to,$fecha) 
        {
            $style_title="style='background:#96B6E6;text-align:center;'";$style_description="style='background:silver;text-align:center;'";$style_basic="style='border:1px solid black;text-align:left;'";
            $style_captura="style='border:1px solid black;background:rgb(231, 148, 59);text-align:center;'";$style_sori="style='border:1px solid black;background:#96B6E6;text-align:center;'";
            $style_diference="style='border:1px solid black;background:#18B469;text-align:center;'";$style_totals="style='border:1px solid black;background:silver;text-align:center;'";$style_corrige="style='margin-top:-48px'";
            $acumulado_captura=0;
            $acumulado_sori=0;
            $acumulado_diference=0;
                       
            $model = refac::get_Model_sori($fecha_from, $fecha_to); //trae el sql pricipal de sori
            $tabla_refac="";
            $tabla_refac.="<table><tr><td></td></tr><tr><td></td></tr><tr><td></td></tr></table>";
           
           $tabla_refac.="<table>
                          <tr>
                          <td " . $style_title . " colspan='3'></td>
                          <td " . $style_title . " colspan='4'><b>REFAC " . Reportes::define_num_dias($fecha_from, $fecha_to) . " " . str_replace("-","",$fecha_from) . " - " . str_replace("-","",$fecha_to) . " al " . str_replace("-","",$fecha) . "</b></td><td " . $style_title . " colspan='3'></td>
                          </tr>
                          </table>";
           $tabla_refac.="<br><table>
                          <tr>
                          <td " . $style_title . "><b>TIPO DE FACTURACION</b></td>
                          <td " . $style_description . " colspan='3'>" . Reportes::define_num_dias($fecha_from, $fecha_to) . "</td>
                          </tr>
                          </table>";
           $tabla_refac.="<br><table>
                          <tr>
                          <td " . $style_title . "><b>PERIODO</b></td>
                          <td " . $style_description . " colspan='3'>" . Utility::formatDateSINE($fecha_from,"F j") . " - " . Utility::formatDateSINE($fecha_to,"F j") . "</td>
                          </tr>
                          </table>";
           $tabla_refac.="<br>
                          <table " . $style_basic . ">
                          <tr>
                          <td colspan='3'" .$style_captura. "><b>CAPTURA</b></td>
                          <td colspan='4'" .$style_sori. "><b>FACTURACION SORI</b></td>
                          <td colspan='3'" .$style_diference. "><b>DIFERENCIAS</b></td>
                          </tr>";
           
                              //           *************CAPTURA*************
           $tabla_refac.="<tr>
                          <td colspan='3'>
                          <table>
                          <tr>
                          <td " .$style_captura. "><b>OPERADOR</b></td>
                          <td " .$style_captura. "><b>MINUTOS</b></td>
                          <td " .$style_captura. "><b>MONTO $</b></td>
                          </tr>";
         foreach ($model as $key => $captura) 
          {
            $model_captura = refac::get_Model_balance($captura,$fecha_from, $fecha_to); //trae el sql pricipal de captura
            $acumulado_captura=Reportes::define_total_captura($captura,$acumulado_captura);
            
           $tabla_refac.="<tr> 
                          <td " . $style_basic . ">" .$captura->carrier. "</td>
                          <td " . $style_basic . ">" .$model_captura->minutes. "</td>
                          <td " . $style_basic . ">" .Yii::app()->format->format_decimal($model_captura->revenue,3). "</td>
                          </tr>";   
          }
           $tabla_refac.="<tr>
                          <td " .$style_captura. "><b>TOTAL</b></td>
                          <td " .$style_captura. "></td>
                          <td " .$style_totals. "><b>" .Yii::app()->format->format_decimal($acumulado_captura,3). "</b></td>
                          </tr>";
           
                                  //           *************SORI*************
           $tabla_refac.="</table>
                          </td>
                          <td colspan='4'>
                          <table " .$style_corrige. ">
                          <tr> 
                          <td " .$style_sori. "><b>OPERADOR</b></td>
                          <td " .$style_sori. "><b>MINUTOS</b></td>
                          <td " .$style_sori. "><b>MONTO</b></td>
                          <td " .$style_sori. "><b>Num FACTURA</b></td>
                          </tr>";
         foreach ($model as $key => $sori) 
          {
             $acumulado_sori=Reportes::define_total_sori($sori,$acumulado_sori);
            
           $tabla_refac.="<tr>
                          <td " . $style_basic . ">" .$sori->carrier. "</td>
                          <td " . $style_basic . ">" .$sori->minutes. "</td>
                          <td " . $style_basic . ">" .Yii::app()->format->format_decimal($sori->amount,3). "</td>
                          <td " . $style_basic . ">" .$sori->doc_number. "</td>
                          </tr>";   
          }
           $tabla_refac.="<tr>
                          <td " .$style_sori. "><b>TOTAL</b></td>
                          <td " .$style_sori. "></td>
                          <td " .$style_totals. "><b>" .Yii::app()->format->format_decimal($acumulado_sori,3). "</b></td>
                          <td " .$style_sori. "></td>
                          </tr>";
                                 //           *************DIFERENCIA*************
           $tabla_refac.="</table>
                          </td>
                          <td colspan='3'>
                          <table " .$style_corrige. ">
                          <tr>
                          <td " .$style_diference. "><b>OPERADOR</b></td>
                          <td " .$style_diference. "><b>MINUTOS</b></td>
                          <td " .$style_diference. "><b>MONTO</b></td>
                          </tr>";
           
         foreach ($model as $key => $diference) 
          {
             $model_captura = refac::get_Model_balance($diference,$fecha_from, $fecha_to); //trae el sql pricipal de captura
             $dif_amount=$diference->amount - $model_captura->revenue;
             $dif_minutes=$diference->minutes - $model_captura->minutes;
             $acumulado_diference=Reportes::define_total_diference($dif_amount,$acumulado_diference);
             
           $tabla_refac.="<tr>
                          <td " . $style_basic . ">" .$diference->carrier. "</td>
                          <td " . $style_basic . ">" .$dif_minutes. "</td>
                          <td " . $style_basic . ">" .Yii::app()->format->format_decimal($dif_amount,3). "</td>
                          </tr>";   
          }
           $tabla_refac.="<tr>
                          <td " .$style_diference. "><b>TOTAL</b></td>
                          <td " .$style_diference. "></td>
                          <td " .$style_totals. "><b>" .Yii::app()->format->format_decimal($acumulado_diference,3). "</b></td>
                          </tr>";
           $tabla_refac.="</table>
                          </td>
                          </tr>";
           
           $tabla_refac.="</table>";
           
           return $tabla_refac;
        }
        /**
         * trae el sql pricipal de sori
         * @param type $fecha_from
         * @param type $fecha_to
         * @return type
         */
        private static function get_Model_sori($fecha_from, $fecha_to) 
        {
            $sql = "SELECT a.id, a.doc_number, a.amount, a.minutes, a.id_carrier, c.name AS carrier
                    FROM accounting_document a, carrier c WHERE a.id_carrier=c.id AND id_type_accounting_document=2 AND from_date>='{$fecha_from}' AND to_date<='{$fecha_to}'ORDER BY from_date ";
            return AccountingDocument::model()->findAllBySql($sql);
        }
        private static function get_Model_balance($model,$fecha_from, $fecha_to) 
        {
            $sql = "SELECT SUM(minutes) AS  minutes, SUM(revenue) as revenue
                    FROM balance
                    WHERE date_balance between '{$fecha_from}' AND '{$fecha_to}' AND id_carrier_customer={$model->id_carrier} and id_destination IS NULL"; 
            return Balance::model()->findBySql($sql);
        }
    }
    ?>


