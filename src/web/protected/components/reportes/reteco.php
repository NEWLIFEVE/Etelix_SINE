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
    public static function report($date)
    {
        $styleRowsNumb="style='background:#83898F;color:white;border:1px solid black;text-align:center;'";
        $styleCarriers="style='background:silver;color:white;border:1px solid black;text-align:center;'";
        $styleContrato="style='background:#06ACFA;color:white;border:1px solid black;text-align:center;'";
        $styleTPCustom="style='background:#049C47;color:white;border:1px solid black;text-align:center;'";
        $styleTPSupplr="style='background:#F89289;color:white;border:1px solid black;text-align:center;'";
        $styleRowBasic="style='color:black;border:1px solid black;text-align:left;'";
        $documents=  self::getData();
        $body="<table>
          <tr>
              <td colspan='2'>
                  <h1>RETECO</h1>
              </td>
              <td colspan='10'>  AL {$date} </td>
          <tr>
              <td colspan='10'></td>
          </tr>
         </table>
         <table style='width: 100%;border:1px solid black;'>
          <tr>
              <td {$styleRowsNumb} > N° </td>
              <td {$styleCarriers} > CARRIER </td>
              <td {$styleCarriers} > GROUP </td>
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
                        <td ".self::defineStyleNeed($document->group)."> ".$document->group." </td>
                        <td ".self::defineStyleNeed($document->sign_date)."> ".Utility::formatDateSINE($document->sign_date,"Y-m-d")." </td>
                        <td ".self::defineStyleNeed($document->production_date)."> ".Utility::formatDateSINE($document->production_date,"Y-m-d")." </td>
                        <td ".self::defineStyleNeed($document->sign_date_tp)."> ".Utility::formatDateSINE($document->sign_date_tp,"Y-m-d")." </td>
                        <td ".self::defineStyleNeed($document->payment_term)."> ".$document->payment_term." </td>
                        <td ".self::defineStyleNeed($document->sign_date_tps)."> ".Utility::formatDateSINE($document->sign_date_tps,"Y-m-d")." </td>
                        <td ".self::defineStyleNeed($document->payment_term_s)."> ".$document->payment_term_s." </td>
                        <td {$styleRowsNumb} > {$pos} </td>
                    </tr>";
                
        }
          return $body;
    }
    public static function defineStyleNeed($var)
    {
        if($var==NULL||$var=="Sin estatus")
            return "style='background:#E99241;color:white;border:1px solid black;text-align:left;'";
        else 
            return "style='background:white;color:black;border:1px solid black;text-align:left;'";
    }
    public static function getData()
    {
        $sql="SELECT /*carrier name*/
                    car.name AS carrier, 
                    /*group name*/
                    (SELECT name AS group
                    FROM carrier_groups
                    WHERE id=car.id_carrier_groups) AS group,
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
                FROM carrier car
                ORDER BY carrier, payment_term, payment_term_s ASC";
    
        return Contrato::model()->findAllBySql($sql);
    }
}
?>
