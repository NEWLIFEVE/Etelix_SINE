
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
    public static function reporte($fecha_from,$fecha_to,$tipo_report)
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
        $models=self::getModel($fecha_from, $fecha_to,$tipo_report);
        
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
        if($models!=null)
        {
            foreach($models as $key => $model)
            {
                if($model->fac_doc_number==null){
                   $style_basic=Reportes::estilos_basic(Reportes::diferenceInvoiceReport($model->fac_amount,$model->amount),"background:#E99241;");
                   $style_basic_number=Reportes::estilos_num(Reportes::diferenceInvoiceReport($model->fac_amount,$model->amount),"background:#E99241;");
                   $diference_amount=$model->amount;
                   $diference_minutes=$model->minutes;
                }else{
                   $style_basic=Reportes::estilos_basic(Reportes::diferenceInvoiceReport($model->fac_amount,$model->amount),"background:#F8CB3C;");
                   $style_basic_number=Reportes::estilos_num(Reportes::diferenceInvoiceReport($model->fac_amount,$model->amount),"background:#F8CB3C;"); 
                   $diference_amount=Reportes::diferenceInvoiceReport($model->fac_amount,$model->amount);
                   $diference_minutes=Reportes::diferenceInvoiceReport($model->fac_minutes,$model->minutes);
                }
                
                $acumulado_factura=Reportes::define_total_facturas($model,$acumulado_factura);
                $acumulado_provisiones=Reportes::define_total_provisiones($model,$acumulado_provisiones);
                $acumulado_diference=Reportes::define_total_diference($diference_amount,$acumulado_diference);
                $reporte.="<tr>
                            <td $style_basic >".$model->carrier."</td>
                            <td $style_basic_number >".Yii::app()->format->format_decimal($model->minutes,3)."</td>
                            <td $style_basic_number >".Yii::app()->format->format_decimal($model->amount,3)."</td>
                            <td $style_basic >".$model->carrier."</td>
                            <td $style_basic_number >".Yii::app()->format->format_decimal($model->fac_minutes,3)."</td>
                            <td $style_basic_number >".Yii::app()->format->format_decimal($model->fac_amount,3)."</td>
                            <td $style_basic >".$model->fac_doc_number."</td>
                            <td $style_basic >".$model->carrier."</td>
                            <td $style_basic_number >".Yii::app()->format->format_decimal($diference_minutes,3)."</td>
                            <td $style_basic_number >".Yii::app()->format->format_decimal($diference_amount,3)."</td>
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
    private static function getModel($fecha_from, $fecha_to,$tipo_report) 
    {
        if($tipo_report=="REFAC"){ 
            $provision="Provision Factura Enviada";
            $factura="Factura Enviada";
        }else {
            $provision="Provision Factura Recibida";
            $factura="Factura Recibida";
        }
        $num=DateManagement::howManyDaysBetween($fecha_from,$fecha_to);
        $from=">=";
        $to="<=";
        if($num>7) $from=$to="=";

        $sql="SELECT a.id, a.from_date, a.to_date,a.amount, a.minutes, a.id_carrier, c.name AS carrier , 
                    (SELECT amount
                          FROM accounting_document 
                          WHERE id_carrier=c.id AND id_type_accounting_document=(SELECT id FROM type_accounting_document WHERE name='{$factura}') AND from_date{$from}a.from_date AND to_date{$to}a.to_date
                          ORDER BY from_date) AS fac_amount,
                    (SELECT minutes
                          FROM accounting_document 
                          WHERE id_carrier=c.id AND id_type_accounting_document=(SELECT id FROM type_accounting_document WHERE name='{$factura}') AND from_date{$from}a.from_date AND to_date{$to}a.to_date
                          ORDER BY from_date) AS fac_minutes,
                    (SELECT doc_number
                          FROM accounting_document 
                          WHERE id_carrier=c.id AND id_type_accounting_document=(SELECT id FROM type_accounting_document WHERE name='{$factura}') AND from_date{$from}a.from_date AND to_date{$to}a.to_date
                          ORDER BY from_date) AS fac_doc_number
                FROM accounting_document a, carrier c
                WHERE a.id_carrier=c.id AND a.id_type_accounting_document=(SELECT id FROM type_accounting_document WHERE  name='{$provision}') AND from_date{$from}'{$fecha_from}' AND to_date{$to}'{$fecha_to}'
                ORDER BY  c.name ASC, a.from_date ASC";
       
        return AccountingDocument::model()->findAllBySql($sql);
    }
}