 <?php

    /**
     * @package reportes
     */
    class refi_prov extends Reportes 
    {
        public static function reporte($fecha_from,$fecha_to,$fecha) 
        {
            $style_title="style='background:#96B6E6;text-align:center;'";$style_description="style='background:silver;text-align:center;'";$style_basic="style='border:1px solid black;text-align:left;'";
            $style_captura="style='border:1px solid black;background:rgb(231, 148, 59);text-align:center;'";$style_sori="style='border:1px solid black;background:#96B6E6;text-align:center;'";
            $style_diference="style='border:1px solid black;background:#18B469;text-align:center;'";$style_totals="style='border:1px solid black;background:silver;text-align:center;'";$style_corrige="style='margin-top:-48px'";
            $acumulado_captura=0;
            $acumulado_sori=0;
            $acumulado_diference=0;
                       
            $model = refi_prov::get_Model($fecha_from, $fecha_to); //trae el sql pricipal de sori
            $tabla_refac="";
            $tabla_refac.="<table><tr><td></td></tr><tr><td></td></tr><tr><td></td></tr></table>";
           
           $tabla_refac.="<table>
                          <tr>
                          <td " . $style_title . " colspan='3'></td>
                          <td " . $style_title . " colspan='4'><b>REFI PROV " . Reportes::define_num_dias($fecha_from, $fecha_to) . " " . str_replace("-","",$fecha_from) . " - " . str_replace("-","",$fecha_to) . " al " . str_replace("-","",$fecha) . "</b></td><td " . $style_title . " colspan='3'></td>
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
            $acumulado_captura=Reportes::define_total_captura($captura,$acumulado_captura);
            
           $tabla_refac.="<tr> 
                          <td " . $style_basic . ">" .$captura->carrier. "</td>
                          <td " . $style_basic . ">" .$captura->minutos_balance. "</td>
                          <td " . $style_basic . ">" .Yii::app()->format->format_decimal($captura->monto_balance,3). "</td>
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
                          <td " .$style_sori. "><b>N° FACTURA</b></td>
                          </tr>";
         foreach ($model as $key => $sori) 
          {
             $acumulado_sori=Reportes::define_total_sori($sori,$acumulado_sori);
            
           $tabla_refac.="<tr>
                          <td " . $style_basic . ">" .$sori->carrier. "</td>
                          <td " . $style_basic . ">" .$sori->minutos_fac. "</td>
                          <td " . $style_basic . ">" .Yii::app()->format->format_decimal($sori->monto_fac,3). "</td>
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
           
         foreach ($model as $key => $sori_captura) 
          {
             $acumulado_diference=Reportes::define_total_diference($sori_captura,$acumulado_diference);
           $tabla_refac.="<tr>
                          <td " . $style_basic . ">" .$sori_captura->carrier. "</td>
                          <td " . $style_basic . ">" .$sori_captura->min_diference. "</td>
                          <td " . $style_basic . ">" .Yii::app()->format->format_decimal($sori_captura->monto_diference,3). "</td>
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
        private static function get_Model($fecha_from, $fecha_to) 
        {
            $sql = "SELECT c.name AS carrier, a.doc_number, a.amount AS monto_fac, b.revenue AS monto_balance, a.minutes AS minutos_fac, b.minutes AS minutos_balance, (b.revenue - a.amount) AS monto_diference, (b.minutes - a.minutes) AS min_diference
                    FROM carrier c, accounting_document a, (SELECT id_carrier_supplier, SUM(revenue) AS revenue, SUM(minutes) AS minutes
                    FROM balance
                    WHERE date_balance BETWEEN '{$fecha_from}' AND '{$fecha_to}'
                    AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') 
                    AND id_destination<>(SELECT id FROM destination WHERE name='Unknown_Destination')
                    AND id_destination_int IS NULL
                    GROUP BY id_carrier_supplier) b
                    WHERE a.id_carrier=c.id 
                      AND a.id_type_accounting_document=2
                      AND a.id_carrier=b.id_carrier_supplier
                      AND a.from_date>='{$fecha_from}' AND a.to_date<='{$fecha_to}'";
            return AccountingDocument::model()->findAllBySql($sql);
        }
    }

    ?>

