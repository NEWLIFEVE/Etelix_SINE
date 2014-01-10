<?php

    /**
     * @package reportes
     */
    class balance_report extends Reportes 
    {
        public static function reporte($grupo, $fecha, $no_disp,$grupoName) 
        {
            $acumulado = 0;
            $acumuladoPago = 0;
            $acumuladoCobro = 0;
            $acumuladoFacEnv = 0;
            $acumuladoFacRec = 0;
            $accounting_document = balance_report::get_Model($grupo, $fecha,$no_disp,"1"); //trae el sql pricipal
            $acc_doc_detal=balance_report::get_Model($grupo, $fecha ,$no_disp,"2");//trae el sql para consultas de elementos o atributos puntuales
            
            $tabla="";
            if ($accounting_document != null) {
                $tabla.= "<h1>BALANCE $grupoName-Etelix <h3>(".$fecha." - ".date("g:i a").")</h3></h1>";
                $tabla.= "<h3 style='margin-top:-5%;text-align:right'>All amounts are expresed in ".$acc_doc_detal->currency."</h3>
                              <table style='background:#3466B4;text-align:center;color:white'>
                              <tr style='border:1px solid black; color: #FFF;  font-weight: bold; height:70px;text-align:center; vertical-align: middle;'>
                              <td style='width:250px;'>Description</td>
                              <td style='width:100px;'>Issue Date</td>
                              <td style='width:100px;'>Due Date</td>
                              <td style='width:100px;'>Payments on account <br>(Etelix to $grupoName)</td>
                              <td style='width:100px;'>Received invoices</td>
                              <td style='width:100px;'>Payments on account <br>($grupoName to Etelix)</td>
                              <td style='width:100px;'>Invoices to collect</td>
                              <td style='width:100px;'>Due Balance</td>
                              </tr>";
                foreach ($accounting_document as $key => $document) 
                    {
                        $tp=self::define_tp($document->tp)["vencimiento"];
                        $due_date=Reportes::define_due_date($tp, $document->issue_date,"+");
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
                             <td colspan='2' style='background:#3466B4;border:1px solid black;text-align:center;color:white'><h3>".Reportes::define_a_favor($acc_doc_detal,$acumulado)."</h3></td>
                             <td style='background:#3466B4;border:1px solid black;text-align:center;color:white;width:90px;'><h3>"  . Yii::app()->format->format_decimal(Reportes::define_a_favor_monto($acumulado),3). "</h3></td>
                             </tr>
                             </table>";
                return $tabla;
            }else{
                return 'No hay data, o por favor consulte en la distribucion comercial los carrier pertenecientes al grupo';
            }
        }
        
        /**
         * 
         * @param type $grupo
         * @return string
         */
        public static function define_grupo($grupo)
        {    
               if($grupo=="CABINAS PERU")  
                   return "id_carrier_groups=301 OR id_carrier_groups=443";
               else   
                   return "id_carrier_groups=".CarrierGroups::getID($grupo)."";
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

           $sql="select a.issue_date,a.id_type_accounting_document,g.name as group,c.name as carrier, tp.name as tp, t.name as type, a.from_date, a.to_date, a.doc_number,a.amount,s.name as currency 
                 from accounting_document a, type_accounting_document t, carrier c, currency s, contrato x, contrato_termino_pago xtp, termino_pago tp, carrier_groups g
                 where a.id_carrier IN(Select id from carrier where $grupo) and a.id_type_accounting_document = t.id and a.id_carrier = c.id and a.id_currency = s.id 
                 and a.id_carrier = x.id_carrier and x.id = xtp.id_contrato and xtp.id_termino_pago = tp.id and xtp.end_date IS NULL and c.id_carrier_groups = g.id and a.issue_date <= '{$fecha}'
                 and a.id_type_accounting_document NOT IN (5,6,10,11,12,13)
                 $no_disp                
                 UNION
                 select max(a.issue_date),a.id_type_accounting_document,g.name as group,c.name as carrier, tp.name as tp, t.name as type, max(a.from_date), max(a.to_date), a.doc_number, sum(a.amount) as suma,s.name as currency 
                 from accounting_document a, type_accounting_document t, carrier c, currency s, contrato x, contrato_termino_pago xtp, termino_pago tp, carrier_groups g
                 where a.id_carrier IN(Select id from carrier where $grupo) and a.id_type_accounting_document = t.id and a.id_carrier = c.id and a.id_currency = s.id 
                 and a.id_carrier = x.id_carrier and x.id = xtp.id_contrato and xtp.id_termino_pago = tp.id and xtp.end_date IS NULL and c.id_carrier_groups = g.id and a.issue_date <= '{$fecha}'
		 and a.id_type_accounting_document IN (10,11) and a.confirm != -1
		 group by a.id_type_accounting_document,g.name, c.name,tp.name,t.name, a.doc_number,s.name
                 UNION 
                 select a.issue_date,a.id_type_accounting_document,g.name as group,c.name as carrier, tp.name as tp, t.name as type, a.from_date, a.to_date, a.doc_number,a.amount,s.name as currency 
                 from accounting_document a, type_accounting_document t, carrier c, currency s, contrato x, contrato_termino_pago xtp, termino_pago tp, carrier_groups g
                 where a.id_carrier IN(Select id from carrier where $grupo) and a.id_type_accounting_document = t.id and a.id_carrier = c.id and a.id_currency = s.id 
                 and a.id_carrier = x.id_carrier and x.id = xtp.id_contrato and xtp.id_termino_pago = tp.id and xtp.end_date IS NULL and c.id_carrier_groups = g.id and a.issue_date <= '{$fecha}'
                 and a.id_type_accounting_document IN (12,13) and a.confirm != -1
                 order by issue_date,from_date ";
                               
            if($tipoSql=="1")return AccountingDocument::model()->findAllBySql($sql);
               else          return AccountingDocument::model()->findBySql($sql);
        }
    }
    ?>

