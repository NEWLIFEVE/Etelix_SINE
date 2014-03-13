
<?php
/**
 * @package reportes
 */
class InvoiceReport extends Reportes
{
    /**
     * @access public
     * @static
     * @param date $fecha_from
     * @param date $fecha_to
     * @param string $tipo_report
     */
    public static function reporte($fecha_from,$fecha_to,$tipo_report,$paymentTerm=null)
    {
        //Fecha que va en el reporte
        $fecha=date('Y-m-d');
        //Estilos
        $style_title="style='background:#96B6E6;text-align:center;'";
        $style_description="style='background:silver;text-align:center;'";
        $style_provisiones="style='border:1px solid black;background:#E99241;text-align:center;'";
        $style_sori="style='border:1px solid black;background:#96B6E6;text-align:center;'";
        $style_diference="style='border:1px solid black;background:#18B469;text-align:center;'";
        $style_totals="style='border:1px solid black;background:silver;text-align:center;'";
        $acumulado_factura=$acumulado_provisiones=$acumulado_diference=0;
        //Traigo las Provisiones de base de datos
        $documents=self::getModel($fecha_from, $fecha_to,$tipo_report,$paymentTerm);
        
        $reporte="<table>
                    <tr rowspan='2'>
                        <td colspan='10'></td>
                    </tr>
                    <tr>
                        <td ".$style_title." colspan='10'><b>".$tipo_report." ".Reportes::define_num_dias($fecha_from,$fecha_to)." ".str_replace("-","",$fecha_from)." - ".str_replace("-","",$fecha_to)." al ".str_replace("-","",$fecha)."</b></td>
                    </tr>
                    <tr>
                        <td colspan='10'></td>
                    </tr>
                    <tr>
                        <td ".$style_title."><b>TIPO DE FACTURACION</b></td>
                        <td ".$style_description." colspan='3'>".Reportes::define_num_dias($fecha_from,$fecha_to)."</td>
                        <td colspan='6'></td>
                    </tr>
                    <tr>
                        <td colspan='10'></td>
                    </tr>
                    <tr>
                        <td ".$style_title."><b>PERIODO</b></td>
                        <td ".$style_description." colspan='3'>".Utility::formatDateSINE($fecha_from,"F j")." - ".Utility::formatDateSINE($fecha_to,"F j")."</td>
                        <td colspan='6'></td>
                    </tr>
                    <tr>
                        <td colspan='10'></td>
                    </tr>
                    <tr>
                        <td colspan='3'".$style_provisiones."><b>CAPTURA</b></td>
                        <td colspan='4'".$style_sori."><b>FACTURACION SORI</b></td>
                        <td colspan='3'".$style_diference."><b>DIFERENCIAS</b></td>
                    </tr>
                    <tr>
                        <td ".$style_provisiones."><b>OPERADOR</b></td>
                        <td ".$style_provisiones."><b>MINUTOS</b></td>
                        <td ".$style_provisiones."><b>MONTO $</b></td>
                        <td ".$style_sori."><b>OPERADOR</b></td>
                        <td ".$style_sori."><b>MINUTOS</b></td>
                        <td ".$style_sori."><b>MONTO</b></td>
                        <td ".$style_sori."><b>Num FACTURA</b></td>
                        <td ".$style_diference."><b>OPERADOR</b></td>
                        <td ".$style_diference."><b>MINUTOS</b></td>
                        <td ".$style_diference."><b>MONTO</b></td>
                    </tr>";
        if($documents!=null)
        {
            foreach($documents as $key => $document)
            {
                $style=self::style($document);

                $acumulado_factura+=$document->fac_amount;
                $acumulado_provisiones+=$document->amount;
                $acumulado_diference+=$document->monto_diference;
                $reporte.="<tr>
                            <td style='border:1px solid black;text-align:left;background:{$style}'>".$document->carrier."</td>
                            <td style='border:1px solid black;text-align:right;background:{$style}'>".Yii::app()->format->format_decimal($document->minutes,3)."</td>
                            <td style='border:1px solid black;text-align:right;background:{$style}'>".Yii::app()->format->format_decimal($document->amount,3)."</td>
                            <td style='border:1px solid black;text-align:left;background:{$style}'>".$document->carrier."</td>
                            <td style='border:1px solid black;text-align:right;background:{$style}'>".Yii::app()->format->format_decimal($document->fac_minutes,3)."</td>
                            <td style='border:1px solid black;text-align:right;background:{$style}'>".Yii::app()->format->format_decimal($document->fac_amount,3)."</td>
                            <td style='border:1px solid black;text-align:left;background:{$style}'>".$document->doc_number."</td>
                            <td style='border:1px solid black;text-align:left;background:{$style}'>".$document->carrier."</td>
                            <td style='border:1px solid black;text-align:right;background:{$style}'>".Yii::app()->format->format_decimal($document->min_diference,3)."</td>
                            <td style='border:1px solid black;text-align:right;background:{$style}'>".Yii::app()->format->format_decimal($document->monto_diference,3)."</td>
                           </tr>";
            }
        }
        $reporte.="<tr>
                    <td $style_provisiones ><b>TOTAL</b></td>
                    <td $style_provisiones ></td>
                    <td $style_totals ><b>".Yii::app()->format->format_decimal($acumulado_provisiones,3)."</b></td>
                    <td $style_sori ><b>TOTAL</b></td>
                    <td $style_sori ></td>
                    <td $style_totals ><b>".Yii::app()->format->format_decimal($acumulado_factura,3)."</b></td>
                    <td $style_sori ></td>
                    <td $style_diference ><b>TOTAL</b></td>
                    <td $style_diference ></td>
                    <td $style_totals ><b>".Yii::app()->format->format_decimal($acumulado_diference,3)."</b></td>
                   </tr>";
        $reporte.="</table>";
        return $reporte;
    }

    /**
     * ejecuta la consulta para traer el trafico de minutos y monto por cada carrier que pase por el foreach
     * @access public
     * @static
     * @param type $model
     * @param type $tipo_report
     * @return type
     */
    private static function getModel($startDate, $endDate,$tipo_report,$paymentTerm=null) 
    {
        if($paymentTerm==null) $paymentTerm=7;
        if($tipo_report=="REFAC")
        { 
            $provision="Provision Factura Enviada";
            $factura="Factura Enviada";
            $carriers="SELECT c.id
                       FROM carrier c, contrato con, contrato_termino_pago ctp, termino_pago tp
                       WHERE con.id_carrier=c.id AND con.end_date IS NULL AND ctp.id_contrato=con.id AND ctp.id_termino_pago=tp.id AND ctp.end_date IS NULL AND tp.period={$paymentTerm}";
        }
        else
        {
            $provision="Provision Factura Recibida";
            $factura="Factura Recibida";
            $carriers="SELECT c.id
                       FROM carrier c, contrato con, contrato_termino_pago_supplier ctps, termino_pago tp
                       WHERE con.id_carrier=c.id AND con.end_date IS NULL AND ctps.id_contrato=con.id AND ctps.id_termino_pago_supplier=tp.id AND ctps.end_date IS NULL AND tp.period={$paymentTerm}";
        }
        

        

        $sql="SELECT b.*, (b.fac_minutes-b.minutes) AS min_diference, (b.fac_amount-b.amount) AS monto_diference
              FROM (SELECT c.name AS carrier, ad.minutes AS minutes, ad.amount AS amount,
                           (SELECT minutes
                            FROM accounting_document
                            WHERE id_type_accounting_document=(SELECT id FROM type_accounting_document WHERE  name='{$factura}') AND ad.from_date=from_date AND ad.to_date=to_date AND c.id=id_carrier) AS fac_minutes,
                           (SELECT amount
                            FROM accounting_document
                            WHERE id_type_accounting_document=(SELECT id FROM type_accounting_document WHERE  name='{$factura}') AND ad.from_date=from_date AND ad.to_date=to_date AND c.id=id_carrier) AS fac_amount,
                           (SELECT doc_number
                            FROM accounting_document
                            WHERE id_type_accounting_document=(SELECT id FROM type_accounting_document WHERE  name='{$factura}') AND ad.from_date=from_date AND ad.to_date=to_date AND c.id=id_carrier) AS doc_number
                    FROM carrier c, accounting_document ad
                    WHERE c.id IN({$carriers}) AND ad.id_carrier=c.id AND ad.from_date>='{$startDate}' AND ad.to_date<='{$endDate}' AND ad.id_type_accounting_document=(SELECT id FROM type_accounting_document WHERE  name='{$provision}')
                    ORDER BY c.name ASC) b";
        return AccountingDocument::model()->findAllBySql($sql);
    }

    /**
     *
     */
    private static function style($document)
    {
        if($document->fac_amount==null) return '#E99241';
        if($document->monto_diference>=1||$document->monto_diference<=-1) return '#F8CB3C';
        return '#ffffff';
    }
}