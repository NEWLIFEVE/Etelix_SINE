    <?php

    /**
     * @package reportes
     */
    class SOA extends Reportes 
    {
        public static function reporte($grupo, $fecha, $no_disp,$grupoName) 
        {
            $acumulado = 0;
            $accounting_document = SOA::get_Model($grupo, $fecha, $no_disp,"1"); //trae el sql pricipal
            $acc_doc_detal=SOA::get_Model($grupo, $fecha, $no_disp,"2");         //trae el sql para consultas de elementos o atributos puntuales
            $tabla_SOA="";
            if ($accounting_document != null) {
                $tabla_SOA.= "<h1>SOA $grupoName-Etelix <h3>(".$fecha." - ".date("g:i a").")</h3></h1>";
//                $tabla_SOA.="<h3 style='margin-top:-5%;text-align:right'>".$fecha." - ".date("g:i a")."</h3>";
                $tabla_SOA.= "<h3 style='margin-top:-5%;text-align:right'>All amounts are expresed in ".$acc_doc_detal->currency."</h3>
                              <table style='background:#3466B4;border:1px solid black;text-align:center;'>
                              <tr style='border:1px solid black; color: #FFF;  font-weight: bold; height:70px;text-align:center; vertical-align: middle;'>
                              <td style='width:250px;'>Description</td>
                              <td style='width:100px;'>Issue Date</td>
                              <td style='width:100px;'>Due Date</td>
                              <td style='width:100px;'>Payments on account (Etelix to $grupoName)</td>
                              <td style='width:100px;'>Received invoices</td>
                              <td style='width:100px;'>Payments on account ($grupoName to Etelix)</td>
                              <td style='width:100px;'>Invoices to collect</td>
                              <td style='width:100px;'>Due Balance</td>
                              </tr>";
                foreach ($accounting_document as $key => $document) 
                    {
                        $tp=Reportes::define_dias_TP($document->tp);
                        $due_date=Reportes::define_due_date($tp, $document->issue_date);
                        $acumulado=Reportes::define_balance_amount($document,$acumulado);
                        
                        $tabla_SOA.="<tr " . Reportes::define_estilos($document) . ">";
                        $tabla_SOA.="<td style='text-align: left;'>" . Reportes::define_description($document)."</td>";
                        $tabla_SOA.="<td style='text-align: center;'>" . Utility::formatDateSINE( $document->issue_date,"d-M-y") . "</td>";
                        $tabla_SOA.="<td style='text-align: center;'>" . Reportes::define_to_date($document,$due_date) . "</td>";
                        $tabla_SOA.="<td style='text-align: right;'>" . Reportes::define_pagos($document) . "</td>";
                        $tabla_SOA.="<td style='text-align: right;'>" . Reportes::define_fact_rec($document) . "</td>";
                        $tabla_SOA.="<td style='text-align: right;'>" . Reportes::define_cobros($document) . "</td>";
                        $tabla_SOA.="<td style='text-align: right;'>" . Reportes::define_fact_env($document) . "</td>";
                        $tabla_SOA.="<td style='text-align: right;'>" . Yii::app()->format->format_decimal($acumulado,3)."</td>";
                        $tabla_SOA.="</tr>";            
                    }
                $tabla_SOA.="</table>";
                $tabla_SOA.="<br><table align='right'>
                             <tr><td></td><td></td><td></td><td></td><td></td><td></td>
                             <td style='background:#3466B4;border:1px solid black;text-align:center;color:white'><h3>Balance in favor of $grupoName</h3></td>
                             <td style='background:#3466B4;border:1px solid black;text-align:center;color:white;width:90px;'><h3>(not yet)</h3></td>
                             </tr>
                             </table>";
                return $tabla_SOA;
            }else{
                return 'No hay data o algo fallo';
            }
        }
        /**
         * sql para el reporte soa
         * @param type $grupo
         * @param type $fecha
         * @param type $no_disp
         * @return type
         */
        private static function get_Model($grupo, $fecha, $no_disp,$tipoSql) {
            $sql = "select a.id,a.issue_date,a.id_type_accounting_document,g.name as group,c.name as carrier, tp.name as tp, t.name as type, a.from_date, a.to_date, a.doc_number, a.amount,s.name as currency 
                from accounting_document a, type_accounting_document t, carrier c, currency s, contrato x, contrato_termino_pago xtp, termino_pago tp, carrier_groups g
                where a.id_carrier IN(Select id from carrier where id_carrier_groups=$grupo) and a.id_type_accounting_document = t.id and a.id_carrier = c.id and a.id_currency = s.id 
                and a.id_carrier = x.id_carrier and x.id = xtp.id_contrato and xtp.id_termino_pago = tp.id and xtp.end_date IS NULL and c.id_carrier_groups = g.id and a.issue_date < '{$fecha}'
                $no_disp
                order by issue_date";
                
            if($tipoSql=="1")return AccountingDocument::model()->findAllBySql($sql);
               else        return AccountingDocument::model()->findBySql($sql);
        }
    }

    ?>
