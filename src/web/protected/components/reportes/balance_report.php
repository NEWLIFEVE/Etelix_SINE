<?php

    /**
     * @package reportes
     */
    class balance_report extends Reportes 
    {
        public static function reporte($grupo, $fecha, $no_disp) 
        {
            $acumulado = 0;
            $acumuladoPago = 0;
            $acumuladoCobro = 0;
            $acumuladoFacEnv = 0;
            $acumuladoFacRec = 0;
            $accounting_document = balance_report::get_Model($grupo, $fecha,$no_disp,"1"); //trae el sql pricipal
            $acc_doc_detal=balance_report::get_Model($grupo, $fecha ,$no_disp,"2");//trae el sql para consultas de elementos o atributos puntuales
            
            $seg=count($accounting_document)*2;
            ini_set('max_execution_time', $seg);
            
            $tabla="";
            if ($accounting_document != null) {
                $tabla.= "<h1>BALANCE $grupo-Etelix <h3>(".$fecha." - ".date("g:i a").")</h3></h1>";
                $tabla.= "<h3 style='margin-top:-5%;text-align:right'>All amounts are expresed in ".$acc_doc_detal->currency."</h3>
                              <table style='background:#3466B4;text-align:center;color:white'>
                              <tr style='border:1px solid silver; color: #FFF;  font-weight: bold; height:70px;text-align:center; vertical-align: middle;'>
                              <td style='width:250px;'>Description</td>
                              <td style='width:100px;'>Issue Date</td>
                              <td style='width:100px;'>Due Date</td>
                              <td style='width:100px;'>Payments on account <br>(Etelix to $grupo)</td>
                              <td style='width:100px;'>Received invoices</td>
                              <td style='width:100px;'>Payments on account <br>($grupo to Etelix)</td>
                              <td style='width:100px;'>Invoices to collect</td>
                              <td style='width:100px;'>Due Balance</td>
                              </tr>";
                foreach ($accounting_document as $key => $document) 
                    {
                        $due_date=Reportes::DueDate($document,CarrierGroups::getID($grupo));
                        $acumulado=Reportes::define_balance_amount($document,$acumulado);
                        $acumuladoPago=Reportes::define_total_pago($document,$acumuladoPago);
                        $acumuladoCobro =Reportes::define_total_cobro($document,$acumuladoCobro);
                        $acumuladoFacEnv =Reportes::define_total_fac_env($document,$acumuladoFacEnv);
                        $acumuladoFacRec =Reportes::define_total_fac_rec($document,$acumuladoFacRec);
                        
                        $tabla.="<tr " . Reportes::define_estilos($document) . ">";
                        $tabla.="<td style='text-align: left;'>" . Reportes::define_description($document)."</td>";
                        $tabla.="<td style='text-align: center;'>" . Utility::formatDateSINE( $document->issue_date,"d-M-y") . "</td>";
                        $tabla.="<td style='text-align: center;'>" . Reportes::define_to_date($document,$due_date) . "</td>";
                        $tabla.="<td style='text-align: right;'>" . Reportes::define_pagos($document) . "</td>";
                        $tabla.="<td style='text-align: right;'>" . Reportes::define_fact_rec($document) . "</td>";
                        $tabla.="<td style='text-align: right;'>" . Reportes::define_cobros($document) . "</td>";
                        $tabla.="<td style='text-align: right;'>" . Reportes::define_fact_env($document) . "</td>";
                        $tabla.="<td style='text-align: right;'>" . Yii::app()->format->format_decimal($acumulado,3)."</td>";
                        $tabla.="</tr>";            
                    }
                $tabla.="<tr " . Reportes::define_estilos_null() . "><td></td><td></td><td></td>
                             <td " . Reportes::define_estilos_totals() . ">". Yii::app()->format->format_decimal($acumuladoPago,3). "</td>
                             <td " . Reportes::define_estilos_totals() . ">". Yii::app()->format->format_decimal($acumuladoFacRec,3). "</td>
                             <td " . Reportes::define_estilos_totals() . ">". Yii::app()->format->format_decimal($acumuladoCobro,3). "</td>
                             <td " . Reportes::define_estilos_totals() . ">". Yii::app()->format->format_decimal($acumuladoFacEnv,3). "</td>
                             <td></td>
                             </tr>";
                $tabla.="</table>";
                $tabla.="<br><table align='right'>
                             <tr><td></td><td></td><td></td><td></td><td></td>
                             <td colspan='2' style='background:#3466B4;border:1px solid silver;text-align:center;'><h3><font color='white'>" .Reportes::define_a_favor($acc_doc_detal,$acumulado). "</font></h3></td>
                             <td style='background:#3466B4;border:1px solid silver;text-align:center;width:90px;'><h3><font color='white'>"  . Yii::app()->format->format_decimal(Reportes::define_a_favor_monto($acumulado),3). "</font></h3></td>
                             </tr>
                             </table>";
                return $tabla;
            }else{
                return 'No hay data, o puede que falte datos  en las condiciones comerciales de carrier pertenecientes al grupo';
            }
        }

        /**
         * sql para el reporte soa
         * @param type $grupo
         * @param type $fecha
         * @param type $no_disp
         * @param type $no_prov
         * @param type $tipoSql
         * @return type
         */
        private static function get_Model($grupo, $fecha, $no_disp,$tipoSql) 
        {
           $grupo=Reportes::define_grupo($grupo);
           $sql="SELECT a.id, minutes, a.issue_date, a.valid_received_date, a.id_type_accounting_document, g.name AS group, c.name AS carrier, tp.name AS tp, t.name AS type, a.from_date, a.to_date, a.doc_number,a.amount,s.name AS currency 
                 FROM accounting_document a, type_accounting_document t, carrier c, currency s, contrato x, contrato_termino_pago xtp, termino_pago tp, carrier_groups g
                 WHERE a.id_carrier IN(SELECT id FROM carrier WHERE $grupo) AND a.id_type_accounting_document = t.id AND a.id_carrier = c.id AND a.id_currency = s.id AND a.id_carrier = x.id_carrier AND x.id = xtp.id_contrato AND xtp.id_termino_pago = tp.id AND xtp.end_date IS NULL AND c.id_carrier_groups = g.id AND a.issue_date <= '{$fecha}' AND a.id_type_accounting_document NOT IN(5,6,10,11,12,13)
                 $no_disp                
                 UNION
                 SELECT a.id, minutes, MAX(a.issue_date), MAX(a.valid_received_date), a.id_type_accounting_document, g.name AS group, c.name AS carrier, tp.name AS tp, t.name AS type, MAX(a.from_date), MAX(a.to_date), a.doc_number, SUM(a.amount) AS suma, s.name AS currency 
                 FROM accounting_document a, type_accounting_document t, carrier c, currency s, contrato x, contrato_termino_pago xtp, termino_pago tp, carrier_groups g
                 WHERE a.id_carrier IN(SELECT id FROM carrier WHERE $grupo) AND a.id_type_accounting_document = t.id AND a.id_carrier = c.id AND a.id_currency = s.id AND a.id_carrier = x.id_carrier AND x.id = xtp.id_contrato AND xtp.id_termino_pago = tp.id AND xtp.end_date IS NULL AND c.id_carrier_groups = g.id AND a.issue_date <= '{$fecha}' AND a.id_type_accounting_document IN(10) AND a.id_accounting_document IS NULL
                 GROUP BY a.id, minutes, a.id_type_accounting_document, g.name, c.name, tp.name, t.name, a.doc_number, s.name
                 UNION
                 SELECT a.id, minutes, MAX(a.issue_date), MAX(a.valid_received_date), a.id_type_accounting_document, g.name AS group, c.name AS carrier, tp.name AS tp, t.name AS type, MAX(a.from_date), MAX(a.to_date), a.doc_number, SUM(a.amount) AS suma, s.name AS currency 
                 FROM accounting_document a, type_accounting_document t, carrier c, currency s, contrato x, contrato_termino_pago xtp, termino_pago tp, carrier_groups g
                 WHERE a.id_carrier IN(SELECT id FROM carrier WHERE $grupo) AND a.id_type_accounting_document = t.id AND a.id_carrier = c.id AND a.id_currency = s.id AND a.id_carrier = x.id_carrier AND x.id = xtp.id_contrato AND xtp.id_termino_pago = tp.id AND xtp.end_date IS NULL AND c.id_carrier_groups = g.id AND a.issue_date <= '{$fecha}' AND a.id_type_accounting_document IN(11) AND a.id_accounting_document IS NULL
                 GROUP BY a.id, minutes, a.id_type_accounting_document, g.name, c.name, tp.name, t.name, a.doc_number, s.name
                 UNION 
                 SELECT a.id, minutes, a.issue_date, a.valid_received_date, a.id_type_accounting_document, g.name AS group, c.name AS carrier, tp.name AS tp, t.name AS type, a.from_date, a.to_date, a.doc_number,a.amount,s.name AS currency
                 FROM accounting_document a, type_accounting_document t, carrier c, currency s, contrato x, contrato_termino_pago xtp, termino_pago tp, carrier_groups g
                 WHERE a.id_carrier IN(SELECT id FROM carrier WHERE $grupo) AND a.id_type_accounting_document = t.id AND a.id_carrier = c.id AND a.id_currency = s.id AND a.id_carrier = x.id_carrier AND x.id = xtp.id_contrato AND xtp.id_termino_pago = tp.id AND xtp.end_date IS NULL AND c.id_carrier_groups = g.id AND a.issue_date <= '{$fecha}' AND a.id_type_accounting_document IN(12,13) AND a.id_accounting_document IS NULL
                 ORDER BY issue_date, from_date ";
                               
            if($tipoSql=="1")return AccountingDocument::model()->findAllBySql($sql);
               else          return AccountingDocument::model()->findBySql($sql);
        }
    }
    ?>

