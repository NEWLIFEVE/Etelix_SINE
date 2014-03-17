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
              <td colspan='9'>  AL {$date} </td>
          <tr>
              <td colspan='9'></td>
          </tr>
         </table>
         <table style='width: 100%;border:1px solid black;'>
          <tr>
              <td {$styleRowsNumb} > N° </td>
              <td {$styleCarriers} > CARRIER </td>
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
                        <td ".self::defineStyleNeed($document->sign_date)."> ".$document->sign_date." </td>
                        <td ".self::defineStyleNeed($document->production_date)."> ".$document->production_date." </td>
                        <td ".self::defineStyleNeed($document->sign_date_tp)."> ".$document->sign_date_tp." </td>
                        <td ".self::defineStyleNeed($document->payment_term)."> ".$document->payment_term." </td>
                        <td ".self::defineStyleNeed($document->sign_date_tps)."> ".$document->sign_date_tps." </td>
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
        $sql="SELECT  c.name AS carrier, con.sign_date, con.production_date, ctp.start_date AS sign_date_tp, tp.name AS payment_term, ctps.start_date AS sign_date_tps, tps.name AS payment_term_s
                FROM contrato con, carrier c, contrato_termino_pago ctp, termino_pago tp, contrato_termino_pago_supplier ctps, termino_pago tps
              WHERE con.end_date IS NULL
                    AND con.id_carrier=c.id
                    AND ctp.end_date IS NULL
                    AND con.id=ctp.id_contrato
                    AND ctp.id_termino_pago=tp.id
                    AND ctps.end_date IS NULL
                    AND con.id=ctps.id_contrato
                    AND ctps.id_termino_pago_supplier=tps.id
              ORDER BY c.name, tp.name, tps.name ASC";
    
        return Contrato::model()->findAllBySql($sql);
    }
}
?>
