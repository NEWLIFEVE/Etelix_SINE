    <?php

    /**
     * @package reportes
     */
    class SOA extends Reportes {

        public static function reporte($grupo, $fecha, $Si_disp,$grupoName) {
            $accounting_document = SOA::get_Model($grupo, $fecha, $Si_disp); //trae el sql pricipal
            if ($accounting_document != null) {
                $tabla_SOA = "<h1>SOA $grupoName-Etelix</h1>
                            <table style='background:#2E62B4;border:1px solid black;text-align:center;'>
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

                foreach ($accounting_document as $key => $document) {
//                    $tp=1;
                    $tp=Reportes::define_dias_TP($document->tp);
                    $due_date=Reportes::define_due_date($tp, $document->issue_date);
    //                                        

                    $tabla_SOA.="<tr style='background:white;color:black;border:1px solid black;'>
                                <td style='text-align: left;'>".Reportes::define_description($document)."</td>
                                <td style='text-align: center;'>" . $document->issue_date . "</td>
                                <td style='text-align: center;'>" . $due_date . "</td>
                                <td style='text-align: right;'>" . Reportes::define_pagos($document) . "</td>
                                <td style='text-align: right;'>" . Reportes::define_fact_rec($document) . "</td>
                                <td style='text-align: right;'>" . Reportes::define_cobros($document) . "</td>
                                <td style='text-align: right;'>" . Reportes::define_fact_env($document) . "</td>
                                <td style='text-align: right;'>" . $document->type . "</td>
                                
                               </tr>";
                }
            }
            //concatena los tr que contienen informacion con la cabecera y resto de la tabla
            $tabla_SOA.="</table>";

            return $tabla_SOA;
        }

        /**
         * sql para el reporte soa
         * @param type $grupo
         * @param type $fecha
         * @param type $Si_disp
         * @return type
         */
        private static function get_Model($grupo, $fecha, $Si_disp) {
            $sql = "select a.id,a.issue_date,a.id_type_accounting_document,g.name as group,c.name as carrier, tp.name as tp, t.name as type, a.from_date, a.to_date, a.doc_number, a.amount,s.name as currency 
                from accounting_document a, type_accounting_document t, carrier c, currency s, contrato x, contrato_termino_pago xtp, termino_pago tp, carrier_groups g
                where a.id_carrier IN(Select id from carrier where id_carrier_groups=$grupo) and a.id_type_accounting_document = t.id and a.id_carrier = c.id and a.id_currency = s.id 
                and a.id_carrier = x.id_carrier and x.id = xtp.id_contrato and xtp.id_termino_pago = tp.id and xtp.end_date IS NULL and c.id_carrier_groups = g.id and a.issue_date < '{$fecha}'
                $Si_disp
                order by issue_date";

            return AccountingDocument::model()->findAllBySql($sql);
        }

    }

    ?>
