 <?php

    /**
     * @package reportes
     */
    class refac extends Reportes 
    {
        public static function reporte($fecha_from,$fecha_to,$fecha) 
        {
            $style_title="style='background:#4A8DB9;text-align:center;'";$style_description="style='background:silver;text-align:center;'";$style_basic="style='border:1px solid black;text-align:left;'";
            $style_captura="style='border:1px solid black;background:orange;text-align:center;'";$style_sori="style='border:1px solid black;background:#4A8DB9;text-align:center;'";
            $style_diference="style='border:1px solid black;background:#058F4D;text-align:center;'";$style_totals="style='border:1px solid black;background:silver;text-align:center;'";$style_corrige="style='margin-top:-48px'";
            $acumulado_captura=0;
            $acumulado_sori=0;
            $acumulado_diference=0;
                       
            $model = refac::get_Model($fecha_from, $fecha_to); //trae el sql pricipal de sori
            $tabla_refac="";
            $tabla_refac.="<table><tr><td></td></tr><tr><td></td></tr><tr><td></td></tr></table>";
           
           $tabla_refac.="<table>
                          <tr>
                          <td " . $style_title . " colspan='3'></td>
                          <td " . $style_title . " colspan='4'><b>REFAC " . Reportes::define_num_dias($fecha_from, $fecha_to) . " " . str_replace("-","",$fecha_from) . "-" . str_replace("-","",$fecha_to) . " al " . str_replace("-","",$fecha) . "</b></td><td " . $style_title . " colspan='3'></td>
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
                          <td " . $style_basic . ">" .$captura->minutes. "</td>
                          <td " . $style_basic . ">" .$captura->amount. "</td>
                          </tr>";   
          }
           $tabla_refac.="<tr>
                          <td " .$style_captura. "><b>TOTAL</b></td>
                          <td " .$style_captura. "></td>
                          <td " .$style_totals. "><b>" .$acumulado_captura. "</b></td>
                          </tr>";
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
                          <td " . $style_basic . ">" .$sori->minutes. "</td>
                          <td " . $style_basic . ">" .$sori->amount. "</td>
                          <td " . $style_basic . ">" .$sori->doc_number. "</td>
                          </tr>";   
          }
           $tabla_refac.="<tr>
                          <td " .$style_sori. "><b>TOTAL</b></td>
                          <td " .$style_sori. "></td>
                          <td " .$style_totals. "><b>" .$acumulado_sori. "</b></td>
                          <td " .$style_sori. "></td>
                          </tr>";
           $tabla_refac.="</table>
                          </td>
                          <td colspan='3'>
                          <table " .$style_corrige. ">
                          <tr>
                          <td " .$style_diference. "><b>OPERADOR</b></td>
                          <td " .$style_diference. "><b>MINUTOS</b></td>
                          <td " .$style_diference. "><b>MONTO</b></td>
                          </tr>";
           
         foreach ($model as $key => $sori) 
          {
//             $acumulado_diference=Reportes::define_total_diference($sori,$acumulado_sori_captura);
           $tabla_refac.="<tr>
                          <td " . $style_basic . ">" .$sori->carrier. "</td>
                          <td " . $style_basic . ">" .refac::define_diferencias_minut($sori, 100). "</td>
                          <td " . $style_basic . ">" .refac::define_diferencias_mont($sori, 100). "</td>
                          </tr>";   
          }
           $tabla_refac.="<tr>
                          <td " .$style_diference. "><b>TOTAL</b></td>
                          <td " .$style_diference. "></td>
                          <td " .$style_totals. "><b>$1500</b></td>
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
            $sql = "SELECT a.id, a.doc_number, a.amount, a.minutes, a.id_carrier, c.name AS carrier
                    FROM accounting_document a, carrier c WHERE a.id_carrier=c.id AND id_type_accounting_document=1 AND from_date>='{$fecha_from}' AND to_date<='{$fecha_to}'ORDER BY from_date ";
            return AccountingDocument::model()->findAllBySql($sql);
//    SELECT 
//      a.id, a.doc_number, a.amount, a.minutes, 
//      b.id AS id_prueba, b.doc_number AS doc_number_prueba, b.amount AS amount_prueba, b.minutes AS minutes_prueba,
//      c.name AS carrier, (a.amount - b.amount) AS amount_diference, (a.minutes - b.minutes) AS minutes_diference
//    FROM accounting_document a, carrier c, accounting_document b
//    WHERE a.id_carrier=c.id AND b.id_carrier=c.id AND a.id_type_accounting_document=1 AND b.id_type_accounting_document=1 AND a.from_date>='2013-11-15' AND a.to_date<='2013-11-25' AND b.from_date>='2013-11-15' AND b.to_date<='2013-11-25'ORDER BY a.from_date , b.from_date 

        }
        /**
         * 
         * @param type $sori
         * @param type $captura
         * @return type
         */
        public static function define_diferencias_minut($sori,$captura)
        {
            return $sori->minutes - $captura;
        }
        /**
         * 
         * @param type $sori
         * @param type $captura
         * @return type
         */
        public static function define_diferencias_mont($sori,$captura)
        {
            return $sori->amount - $captura;
        }
    }

    ?>

