 <?php

/**
 * @package reportes
 * @version 1.0
 */
class reteco extends Reportes 
{
    /**
     * Encargada de armar el html del reporte
     * @return string
     * @access public
     */
    public static function report($carActived,$typePaymentTerm,$paymentTerm)
    {
        $styleRowsNumb="style='background:#83898F;color:white;border:1px solid black;text-align:center;'";
        $styleCarriers="style='background:silver;color:white;border:1px solid black;text-align:center;'";
        $styleActived="style='background:#F0950C;color:white;border:1px solid black;text-align:center;'";
        $styleContrato="style='background:#06ACFA;color:white;border:1px solid black;text-align:center;'";
        $styleTPCustom="style='background:#049C47;color:white;border:1px solid black;text-align:center;'";
        $styleTPSupplr="style='background:#F89289;color:white;border:1px solid black;text-align:center;'";
        $styleRowBasic="style='color:black;border:1px solid black;text-align:left;'";
        $styleRowActiv="style='color:red;border:1px solid black;text-align:center;font-size: x-large;padding-bottom: 0.5%;'";
        $documents=  self::getData($carActived,$typePaymentTerm,$paymentTerm);
        $body="<table>
          <tr>
              <td colspan='3'>
                  <h1>RETECO - ".Reportes::defineNameExtra($paymentTerm,$typePaymentTerm)."</h1>
              </td>
              <td colspan='10'>  AL ".date("Y-m-d")." </td>
          <tr>
              <td colspan='10'></td>
          </tr>
         </table>
         <table style='width: 100%;border:1px solid black;'>
          <tr>
              <td {$styleRowsNumb} > N° </td>
              <td {$styleCarriers} > CARRIER </td>
              <td {$styleCarriers} > GROUP </td>
              <td {$styleActived} > INACTIVE </td>
              <td {$styleContrato} > SIGN DATE </td>
              <td {$styleContrato} > PRODUCTION DATE </td>
              <td {$styleTPCustom} > SIGN DATE TPC </td>
              <td {$styleTPCustom} > TP CUSTOMER </td>
              <td {$styleTPSupplr} > SIGN DATE TPS </td>
              <td {$styleTPSupplr} > TP SUPPLIER </td>
              <td {$styleRowsNumb} > N° </td>
          </tr>";
        foreach ($documents as $key => $document)
        {
            $pos=$key+1;
            $body.="<tr>
                        <td {$styleRowsNumb} > {$pos} </td>
                        <td {$styleRowBasic} > ".$document->carrier." </td>
                        <td ".Reportes::defineStyleNeed($document->group)."> ".$document->group." </td>
                        <td {$styleRowActiv}> ".Reportes::defineActive($document->active)." </td>
                        <td ".Reportes::defineStyleNeed($document->sign_date)."> ".Utility::formatDateSINE($document->sign_date,"Y-m-d")." </td>
                        <td ".Reportes::defineStyleNeed($document->production_date)."> ".Utility::formatDateSINE($document->production_date,"Y-m-d")." </td>
                        <td ".Reportes::defineStyleNeed($document->sign_date_tp)."> ".Utility::formatDateSINE($document->sign_date_tp,"Y-m-d")." </td>
                        <td ".Reportes::defineStyleNeed($document->payment_term)."> ".$document->payment_term." </td>
                        <td ".Reportes::defineStyleNeed($document->sign_date_tps)."> ".Utility::formatDateSINE($document->sign_date_tps,"Y-m-d")." </td>
                        <td ".Reportes::defineStyleNeed($document->payment_term_s)."> ".$document->payment_term_s." </td>
                        <td {$styleRowsNumb} > {$pos} </td>
                    </tr>";
        }
        return $body;
    }

    public static function getData($carActived=TRUE,$typePaymentTerm,$paymentTerm)
    {
        if($carActived)
            $carActived="";
          else
            $carActived="AND cm.id_managers!=16";
          
        if($paymentTerm=="todos") 
            $filterPaymentTerm="1,2,3,4,5,6,7,8,9,10,12,13";
          else
            $filterPaymentTerm="$paymentTerm";
          
        if($typePaymentTerm===NULL){
            $tableNext="";
            $wherePaymentTerm="";
        }
        if($typePaymentTerm===FALSE){
            $tableNext=", contrato con,  contrato_termino_pago ctp, termino_pago tp";
            $wherePaymentTerm="AND con.end_date IS NULL
                               AND con.id_carrier=car.id
                               AND ctp.end_date IS NULL
                               AND con.id=ctp.id_contrato
                               AND ctp.id_termino_pago=tp.id
                               AND tp.id IN({$filterPaymentTerm})";
        }
        if($typePaymentTerm===TRUE){
            $tableNext=", contrato con,  contrato_termino_pago_supplier ctp, termino_pago tp";
            $wherePaymentTerm="AND con.end_date IS NULL
                               AND con.id_carrier=car.id
                               AND ctp.end_date IS NULL
                               AND con.id=ctp.id_contrato
                               AND ctp.id_termino_pago_supplier=tp.id
                               AND tp.id IN({$filterPaymentTerm})";
        }

        $sql="SELECT /*carrier name*/
                car.name AS carrier, 
                /*group name*/
                (SELECT name AS group
                 FROM carrier_groups
                 WHERE id=car.id_carrier_groups) AS group,
                /*activo o inactivo*/
                (SELECT id_managers 
                 FROM carrier_managers 
                 WHERE id_carrier=car.id
                   AND end_date IS NULL) AS active,
               /*sign_date*/
               (SELECT sign_date
                FROM contrato con
                WHERE end_date IS NULL
                  AND id_carrier=car.id) AS sign_date,
               /*production_date*/
               (SELECT production_date
                FROM contrato con
                WHERE end_date IS NULL
                  AND id_carrier=car.id) AS production_date,
                /*sign_date_tp*/
                (SELECT ctp.start_date AS sign_date_tp 
                 FROM contrato con,  contrato_termino_pago ctp, termino_pago tp
                 WHERE con.end_date IS NULL
                   AND con.id_carrier=car.id
                   AND ctp.end_date IS NULL
                   AND con.id=ctp.id_contrato
                   AND ctp.id_termino_pago=tp.id) AS sign_date_tp,
                /*payment_term*/
                (SELECT tp.name AS payment_term
                 FROM contrato con,  contrato_termino_pago ctp, termino_pago tp
                 WHERE con.end_date IS NULL
                   AND con.id_carrier=car.id
                   AND ctp.end_date IS NULL
                   AND con.id=ctp.id_contrato
                   AND ctp.id_termino_pago=tp.id) AS payment_term,
                /*sign_date_tps*/
                (SELECT ctps.start_date AS sign_date_tps 
                 FROM contrato con,  contrato_termino_pago_supplier ctps, termino_pago tp
                 WHERE con.end_date IS NULL
                   AND con.id_carrier=car.id
                   AND ctps.end_date IS NULL
                   AND con.id=ctps.id_contrato
                   AND ctps.id_termino_pago_supplier=tp.id) AS sign_date_tps,
                /*payment_term_s*/
                (SELECT tp.name AS payment_term_s
                 FROM contrato con,  contrato_termino_pago_supplier ctps, termino_pago tp
                 WHERE con.end_date IS NULL
                   AND con.id_carrier=car.id
                   AND ctps.end_date IS NULL
                   AND con.id=ctps.id_contrato
                   AND ctps.id_termino_pago_supplier=tp.id) AS payment_term_s
            FROM carrier car, carrier_managers cm {$tableNext}
            WHERE cm.id_carrier=car.id
              AND car.id NOT IN(select id from carrier where name='Unknown_Carrier')
              AND cm.end_date IS NULL {$carActived} AND cm.id_carrier IS NOT NULL
                  {$wherePaymentTerm}
            ORDER BY carrier, payment_term ASC";
    
        return Contrato::model()->findAllBySql($sql);
    }
}
?>
