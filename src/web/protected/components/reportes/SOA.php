 <?php

    /**
     * @package reportes
     */
    class SOA extends Reportes 
    {
        public static function reporte($group, $date, $dispute,$provision) 
        {
            $accumulated=$accumulatedPayment=$accumulatedCollection=$accumulatedInvoiceSend=$accumulatedInvoiceRec = 0;
            $accumulatedPaymentNext=$accumulatedCollectionNext=$accumulatedInvoiceSendNext=$accumulatedInvoiceRecNext = 0;
            $last_due_date_next=$last_due_date_due="";
            
            $accounting_document = SOA::get_Model($group, $date, $dispute,$provision,"1"); //trae el sql pricipal
            $acc_doc_detal=SOA::get_Model($group, $date, $dispute,$provision,"2");//trae el sql para consultas de elementos o atributos puntuales

            $body="";
            if ($accounting_document != null) {
                $body.= "<h1>SOA $group-Etelix <h3>(".$date." - ".date("g:i a").")</h3></h1>";
                $body.= "<h3 style='margin-top:-5%;text-align:right'>All amounts are expresed in ".$acc_doc_detal->currency."</h3>
                         <table style='background:#3466B4;text-align:center;color:white'>
                              <tr style='border:1px solid silver; color: #FFF;  font-weight: bold; height:70px;text-align:center; vertical-align: middle;'>
                                 <td style='width:250px;'>Description</td>
                                 <td style='width:100px;'>Issue Date</td>
                                 <td style='width:100px;'>Due Date</td>
                                 <td style='width:100px;'>Payments on account <br>(Etelix to $group)</td>
                                 <td style='width:100px;'>Received invoices</td>
                                 <td style='width:100px;'>Payments on account <br>($group to Etelix)</td>
                                 <td style='width:100px;'>Invoices to collect</td>
                                 <td style='width:100px;'>Due Balance</td>
                              </tr>";
                foreach ($accounting_document as $key => $document) 
                    {
                        if(Reportes::dueOrNext($document)<=$date)
                        {  
                            $accumulated=Reportes::define_balance_amount($document,$accumulated);
                            $accumulatedPayment=Reportes::define_total_pago($document,$accumulatedPayment);
                            $accumulatedCollection =Reportes::define_total_cobro($document,$accumulatedCollection);
                            $accumulatedInvoiceSend =Reportes::define_total_fac_env($document,$accumulatedInvoiceSend);
                            $accumulatedInvoiceRec =Reportes::define_total_fac_rec($document,$accumulatedInvoiceRec);

                            $body.="<tr " . Reportes::define_estilos($document) . ">";
                            $body.="<td style='text-align: left;'>" . Reportes::define_description($document)."</td>";
                            $body.="<td style='text-align: center;'>" . Utility::formatDateSINE( $document->issue_date,"d-M-y") . "</td>";
                            $body.="<td style='text-align: center;'>" . Reportes::define_to_date($document,null) . "</td>";//NULL es provisional//
                            $body.="<td style='text-align: right;'>" . Reportes::define_pagos($document) . "</td>";
                            $body.="<td style='text-align: right;'>" . Reportes::define_fact_rec($document) . "</td>";
                            $body.="<td style='text-align: right;'>" . Reportes::define_cobros($document) . "</td>";
                            $body.="<td style='text-align: right;'>" . Reportes::define_fact_env($document) . "</td>";
                            $body.="<td style='text-align: right;'>" . Yii::app()->format->format_decimal($accumulated,3)."</td>";
                            $body.="</tr>";
                            $last_due_date_due=Reportes:: defineDueDateHigher($document, $last_due_date_due);

                        }
                    }
                $body.="<tr " . Reportes::define_estilos_null() . "><td></td><td></td><td></td>
                           <td " . Reportes::define_estilos_totals() . ">". Yii::app()->format->format_decimal($accumulatedPayment,3). "</td>
                           <td " . Reportes::define_estilos_totals() . ">". Yii::app()->format->format_decimal($accumulatedInvoiceRec,3). "</td>
                           <td " . Reportes::define_estilos_totals() . ">". Yii::app()->format->format_decimal($accumulatedCollection,3). "</td>
                           <td " . Reportes::define_estilos_totals() . ">". Yii::app()->format->format_decimal($accumulatedInvoiceSend,3). "</td>
                           <td></td>
                        </tr>";
                $body.="</table><br>";
                $body.="<table align='right'>
                         <tr>
                            <td></td>
                            <td colspan='2' style='background:#3466B4;border:1px solid silver;text-align:center;'><h3><font color='white'>SOA  (DUE)</td>
                            <td style='background:#3466B4;border:1px solid silver;text-align:center;'><h3><font color='white'>DUE: {$last_due_date_due}</td>
                            <td style='background:#3466B4;border:1px solid silver;text-align:center;'><h3><font color='white'>".DateManagement::howManyDaysBetween($last_due_date_due, $date)." days due</td>
                            <td colspan='2' style='background:#3466B4;border:1px solid silver;text-align:center;'><h3><font color='white'>" .Reportes::define_a_favor($acc_doc_detal,$accumulated). "</font></h3></td>
                            <td style='background:#3466B4;border:1px solid silver;text-align:center;width:90px;'><h3><font color='white'>"  . Yii::app()->format->format_decimal(Reportes::define_a_favor_monto($accumulated),3). "</font></h3></td>
                          </tr>
                        </table><br><br>";
                $body.="<table style='background:#3466B4;text-align:center;color:white;margin-top: 70px;'>
                         <tr style='border:1px solid silver; color: #FFF;  font-weight: bold; height:70px;text-align:center; vertical-align: middle;'>
                            <td style='width:250px;'>Description</td>
                            <td style='width:100px;'>Issue Date</td>
                            <td style='width:100px;'>Due Date</td>
                            <td style='width:100px;'>Payments on account <br>(Etelix to $group)</td>
                            <td style='width:100px;'>Received invoices</td>
                            <td style='width:100px;'>Payments on account <br>($group to Etelix)</td>
                            <td style='width:100px;'>Invoices to collect</td>
                            <td style='width:100px;'>Due Balance</td>
                         </tr>";
                foreach ($accounting_document as $key => $document) 
                {
                    if(Reportes::dueOrNext($document)>$date)
                    { 
                        $accumulated=Reportes::define_balance_amount($document,$accumulated);
                        $accumulatedPaymentNext=Reportes::define_total_pago($document,$accumulatedPaymentNext);
                        $accumulatedCollectionNext =Reportes::define_total_cobro($document,$accumulatedCollectionNext);
                        $accumulatedInvoiceSendNext =Reportes::define_total_fac_env($document,$accumulatedInvoiceSendNext);
                        $accumulatedInvoiceRecNext =Reportes::define_total_fac_rec($document,$accumulatedInvoiceRecNext);

                        $body.="<tr " . Reportes::define_estilos($document) . ">";
                        $body.="<td style='text-align: left;'>" . Reportes::define_description($document)."</td>";
                        $body.="<td style='text-align: center;'>" . Utility::formatDateSINE( $document->issue_date,"d-M-y") . "</td>";
                        $body.="<td style='text-align: center;'>" . Reportes::define_to_date($document,NULL) . "</td>";//NULL es provisional//
                        $body.="<td style='text-align: right;'>" . Reportes::define_pagos($document) . "</td>";
                        $body.="<td style='text-align: right;'>" . Reportes::define_fact_rec($document) . "</td>";
                        $body.="<td style='text-align: right;'>" . Reportes::define_cobros($document) . "</td>";
                        $body.="<td style='text-align: right;'>" . Reportes::define_fact_env($document) . "</td>";
                        $body.="<td style='text-align: right;'>" . Yii::app()->format->format_decimal($accumulated,3)."</td>";
                        $body.="</tr>"; 
                        $last_due_date_next=Reportes:: defineDueDateHigher($document, $last_due_date_next);
                    }         
                }
                if($last_due_date_next=="") { 
                    $nextDate="0";
                } else{
                    $nextDate=abs(DateManagement::howManyDaysBetween($last_due_date_next, $date));
                }
                $body.="<tr " . Reportes::define_estilos_null() . "><td colspan='3'></td>
                           <td " . Reportes::define_estilos_totals() . ">". Yii::app()->format->format_decimal($accumulatedPaymentNext,3). "</td>
                           <td " . Reportes::define_estilos_totals() . ">". Yii::app()->format->format_decimal($accumulatedInvoiceRecNext,3). "</td>
                           <td " . Reportes::define_estilos_totals() . ">". Yii::app()->format->format_decimal($accumulatedCollectionNext,3). "</td>
                           <td " . Reportes::define_estilos_totals() . ">". Yii::app()->format->format_decimal($accumulatedInvoiceSendNext,3). "</td>
                           <td></td>
                        </tr>";
                $body.="</table><br>
                        <table align='right'>
                         <tr>
                            <td></td>
                            <td colspan='2'style='background:#3466B4;border:1px solid silver;text-align:center;'><h3><font color='white'>SOA (NEXT)</td>
                            <td style='background:#3466B4;border:1px solid silver;text-align:center;'><h3><font color='white'>NEXT: {$last_due_date_next}</td>
                            <td style='background:#3466B4;border:1px solid silver;text-align:center;'><h3><font color='white'>{$nextDate} day next</td>
                            <td colspan='2' style='background:#3466B4;border:1px solid silver;text-align:center;'><h3><font color='white'>" .Reportes::define_a_favor($acc_doc_detal,$accumulated). "</font></h3></td>
                            <td style='background:#3466B4;border:1px solid silver;text-align:center;width:90px;'><h3><font color='white'>"  . Yii::app()->format->format_decimal(Reportes::define_a_favor_monto($accumulated),3). "</font></h3></td>
                         </tr>
                        </table>";
                return $body;
            }else{
                return 'No hay data, o puede que falte datos  en las condiciones comerciales de carrier pertenecientes al grupo';
            }
        }

        /**
         * 
         * @param type $group
         * @param type $date
         * @param type $dispute
         * @param type $provisions
         * @param type $tipoSql
         * @return type
         */
        public static function get_Model($group, $date, $dispute,$provisions,$tipoSql) 
        {
            $group=Reportes::define_grupo($group);
            $sql="SELECT *
                    FROM(/*me traigo todos los documentos, menos facturas*/
                    SELECT a.id, issue_date, valid_received_date, doc_number, from_date, to_date, minutes, g.name AS group,
                           CAST(NULL AS date) AS due_date, amount, id_type_accounting_document,s.name AS currency, c.name AS carrier
                      FROM accounting_document a, type_accounting_document tad, currency s, carrier c, carrier_groups g
                      WHERE a.id_carrier IN(Select id from carrier where $group)
                          AND tad.name IN('Pago','Cobro','Nota de Credito Recibida','Nota de Credito Enviada','Bank Fee Cobro','Bank Fee Pago','Saldo Inicial') 
                          AND a.id_type_accounting_document=tad.id
                          AND a.id_carrier=c.id
                          AND a.id_currency=s.id
                          AND c.id_carrier_groups = g.id
               {$provisions}
               {$dispute}     
               UNION
                    /*me traigo facturas enviadas*/
                SELECT a.id, issue_date, valid_received_date, doc_number, from_date, to_date, minutes, g.name AS group,
                           CASE WHEN (SELECT tp.expiration
                         FROM carrier c,
                            (SELECT id, sign_date, production_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_carrier, id_company, up, bank_fee
                             FROM contrato
                             WHERE sign_date<='{$date}') con,
                            (SELECT id, start_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_contrato, id_termino_pago
                             FROM contrato_termino_pago
                             WHERE start_date<='{$date}') ctp,
                            termino_pago tp
                         WHERE con.id_carrier=c.id AND ctp.id_contrato=con.id AND ctp.id_termino_pago=tp.id AND con.end_date>='{$date}' AND con.sign_date IS NOT NULL AND ctp.end_date>='{$date}' AND c.id=a.id_carrier)=0 THEN issue_date
                 WHEN (SELECT tp.expiration
                         FROM carrier c,
                            (SELECT id, sign_date, production_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_carrier, id_company, up, bank_fee
                             FROM contrato
                             WHERE sign_date<='{$date}') con,
                            (SELECT id, start_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_contrato, id_termino_pago
                             FROM contrato_termino_pago
                             WHERE start_date<='{$date}') ctp,
                            termino_pago tp
                         WHERE con.id_carrier=c.id AND ctp.id_contrato=con.id AND ctp.id_termino_pago=tp.id AND con.end_date>='{$date}' AND con.sign_date IS NOT NULL AND ctp.end_date>='{$date}' AND c.id=a.id_carrier)=3 THEN CAST(issue_date + interval '3 days' AS date)
                 WHEN (SELECT tp.expiration
                        FROM carrier c,
                            (SELECT id, sign_date, production_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_carrier, id_company, up, bank_fee
                             FROM contrato
                             WHERE sign_date<='{$date}') con,
                            (SELECT id, start_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_contrato, id_termino_pago
                             FROM contrato_termino_pago
                             WHERE start_date<='{$date}') ctp,
                            termino_pago tp
                        WHERE con.id_carrier=c.id AND ctp.id_contrato=con.id AND ctp.id_termino_pago=tp.id AND con.end_date>='{$date}' AND con.sign_date IS NOT NULL AND ctp.end_date>='{$date}' AND c.id=a.id_carrier)=5 THEN CAST(issue_date + interval '5 days' AS date)
                 WHEN (SELECT tp.expiration
                        FROM carrier c,
                             (SELECT id, sign_date, production_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_carrier, id_company, up, bank_fee
                              FROM contrato
                              WHERE sign_date<='{$date}') con,
                             (SELECT id, start_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_contrato, id_termino_pago
                              FROM contrato_termino_pago
                              WHERE start_date<='{$date}') ctp,
                             termino_pago tp
                        WHERE con.id_carrier=c.id AND ctp.id_contrato=con.id AND ctp.id_termino_pago=tp.id AND con.end_date>='{$date}' AND con.sign_date IS NOT NULL AND ctp.end_date>='{$date}' AND c.id=a.id_carrier)=7 THEN CAST(issue_date + interval '7 days' AS date)
                 WHEN (SELECT tp.expiration
                        FROM carrier c,
                             (SELECT id, sign_date, production_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_carrier, id_company, up, bank_fee
                              FROM contrato
                              WHERE sign_date<='{$date}') con,
                             (SELECT id, start_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_contrato, id_termino_pago
                              FROM contrato_termino_pago
                              WHERE start_date<='{$date}') ctp,
                             termino_pago tp
                        WHERE con.id_carrier=c.id AND ctp.id_contrato=con.id AND ctp.id_termino_pago=tp.id AND con.end_date>='{$date}' AND con.sign_date IS NOT NULL AND ctp.end_date>='{$date}' AND c.id=a.id_carrier)=15 THEN CAST(issue_date + interval '15 days' AS date)
                 WHEN (SELECT tp.expiration
		        FROM carrier c,
                            (SELECT id, sign_date, production_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_carrier, id_company, up, bank_fee
                             FROM contrato
                             WHERE sign_date<='{$date}') con,
                            (SELECT id, start_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_contrato, id_termino_pago
                             FROM contrato_termino_pago
                             WHERE start_date<='{$date}') ctp,
                            termino_pago tp
		        WHERE con.id_carrier=c.id AND ctp.id_contrato=con.id AND ctp.id_termino_pago=tp.id AND con.end_date>='{$date}' AND con.sign_date IS NOT NULL AND ctp.end_date>='{$date}' AND c.id=a.id_carrier)=30 THEN CAST(issue_date + interval '30 days' AS date) END AS due_date,
		amount, 
		id_type_accounting_document, s.name AS currency, c.name AS carrier
                FROM accounting_document a, type_accounting_document tad, currency s, carrier c, carrier_groups g
                WHERE a.id_carrier IN(Select id from carrier where $group)
                  AND tad.name IN('Factura Enviada') 
                  AND a.id_type_accounting_document=tad.id
                  AND a.id_carrier=c.id
                  AND a.id_currency=s.id
                  AND c.id_carrier_groups=g.id

             UNION/*me traigo facturas recibidas*/
             
                SELECT a.id, issue_date, valid_received_date, doc_number, from_date, to_date, minutes, g.name AS group,
                      CASE WHEN (SELECT tp.expiration
                        FROM carrier c, 
                             (SELECT id, sign_date, production_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_carrier, id_company, up, bank_fee
                              FROM contrato
                              WHERE sign_date<='{$date}') con, 
                             (SELECT id, start_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_contrato, id_termino_pago_supplier
                              FROM contrato_termino_pago_supplier
                              WHERE start_date<='{$date}') ctps, 
                             termino_pago tp
                        WHERE con.id_carrier=c.id AND ctps.id_contrato=con.id AND ctps.id_termino_pago_supplier=tp.id AND con.end_date>='{$date}' AND con.sign_date IS NOT NULL AND ctps.end_date>='{$date}' AND c.id=a.id_carrier)=0 THEN valid_received_date
                WHEN (SELECT tp.expiration
                        FROM carrier c, 
                             (SELECT id, sign_date, production_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_carrier, id_company, up, bank_fee
                              FROM contrato
                              WHERE sign_date<='{$date}') con, 
                             (SELECT id, start_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_contrato, id_termino_pago_supplier
                              FROM contrato_termino_pago_supplier
                              WHERE start_date<='{$date}') ctps, 
                             termino_pago tp
                        WHERE con.id_carrier=c.id AND ctps.id_contrato=con.id AND ctps.id_termino_pago_supplier=tp.id AND con.end_date>='{$date}' AND con.sign_date IS NOT NULL AND ctps.end_date>='{$date}' AND c.id=a.id_carrier)=3 THEN CAST(valid_received_date + interval '3 days' AS date)
                WHEN (SELECT tp.expiration
                        FROM carrier c, 
                             (SELECT id, sign_date, production_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_carrier, id_company, up, bank_fee
                              FROM contrato
                              WHERE sign_date<='{$date}') con, 
                             (SELECT id, start_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_contrato, id_termino_pago_supplier
                              FROM contrato_termino_pago_supplier
                              WHERE start_date<='{$date}') ctps, 
                             termino_pago tp
                        WHERE con.id_carrier=c.id AND ctps.id_contrato=con.id AND ctps.id_termino_pago_supplier=tp.id AND con.end_date>='{$date}' AND con.sign_date IS NOT NULL AND ctps.end_date>='{$date}' AND c.id=a.id_carrier)=5 THEN CAST(valid_received_date + interval '5 days' AS date)
                WHEN (SELECT tp.expiration
                        FROM carrier c, 
                             (SELECT id, sign_date, production_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_carrier, id_company, up, bank_fee
                              FROM contrato
                              WHERE sign_date<='{$date}') con, 
                             (SELECT id, start_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_contrato, id_termino_pago_supplier
                              FROM contrato_termino_pago_supplier
                              WHERE start_date<='{$date}') ctps, 
                             termino_pago tp
                        WHERE con.id_carrier=c.id AND ctps.id_contrato=con.id AND ctps.id_termino_pago_supplier=tp.id AND con.end_date>='{$date}' AND con.sign_date IS NOT NULL AND ctps.end_date>='{$date}' AND c.id=a.id_carrier)=7 THEN CAST(valid_received_date + interval '7 days' AS date)
                WHEN (SELECT tp.expiration
                        FROM carrier c, 
                             (SELECT id, sign_date, production_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_carrier, id_company, up, bank_fee
                              FROM contrato
                              WHERE sign_date<='{$date}') con, 
                             (SELECT id, start_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_contrato, id_termino_pago_supplier
                              FROM contrato_termino_pago_supplier
                              WHERE start_date<='{$date}') ctps, 
                             termino_pago tp
                        WHERE con.id_carrier=c.id AND ctps.id_contrato=con.id AND ctps.id_termino_pago_supplier=tp.id AND con.end_date>='{$date}' AND con.sign_date IS NOT NULL AND ctps.end_date>='{$date}' AND c.id=a.id_carrier)=15 THEN CAST(valid_received_date + interval '15 days' AS date)
                WHEN (SELECT tp.expiration
                        FROM carrier c, 
                             (SELECT id, sign_date, production_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_carrier, id_company, up, bank_fee
                              FROM contrato
                              WHERE sign_date<='{$date}') con, 
                             (SELECT id, start_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_contrato, id_termino_pago_supplier
                              FROM contrato_termino_pago_supplier
                              WHERE start_date<='{$date}') ctps, 
                             termino_pago tp
                        WHERE con.id_carrier=c.id AND ctps.id_contrato=con.id AND ctps.id_termino_pago_supplier=tp.id AND con.end_date>='{$date}' AND con.sign_date IS NOT NULL AND ctps.end_date>='{$date}' AND c.id=a.id_carrier)=30 THEN CAST(valid_received_date + interval '30 days' AS date) END AS due_date,
                            amount, 
                            id_type_accounting_document,
                            s.name AS currency, c.name AS carrier
            FROM accounting_document a, type_accounting_document tad, currency s, carrier c, carrier_groups g
            WHERE a.id_carrier IN(Select id from carrier where $group)
                AND tad.name IN('Factura Recibida') 
                AND a.id_type_accounting_document=tad.id
                AND a.id_carrier=c.id
                AND a.id_currency=s.id
                AND c.id_carrier_groups=g.id) d
                ORDER BY issue_date";

            if($tipoSql=="1")return AccountingDocument::model()->findAllBySql($sql);
               else        return AccountingDocument::model()->findBySql($sql);
        }
    }
    ?>