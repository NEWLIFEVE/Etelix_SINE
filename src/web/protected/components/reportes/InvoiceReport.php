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
        $style_basic="style='border:1px solid black;text-align:left;'";
        $style_basic_number="style='border:1px solid black;text-align:right;'";
        $style_provisiones="style='border:1px solid black;background:rgb(231, 148, 59);text-align:center;'";
        $style_sori="style='border:1px solid black;background:#96B6E6;text-align:center;'";
        $style_diference="style='border:1px solid black;background:#18B469;text-align:center;'";
        $style_totals="style='border:1px solid black;background:silver;text-align:center;'";
        $acumulado_factura=$acumulado_provisiones=$acumulado_diference=0;
        //Traigo las Provisiones de base de datos
        $provisiones=self::getProvisions($fecha_from,$fecha_to,$tipo_report);
        //Aumento el tiempo de ejecucion dependiendo de la cantidad de Provisiones que trae de base de datos
        $seg=count($provisiones);
        ini_set('max_execution_time', $seg);
        $reporte="<table>
                    <tr rowspan='2'>
                        <td colspan='10'></td>
                    </tr>";
        $reporte.="<tr>
                    <td ".$style_title." colspan='10'><b>".$tipo_report." ".Reportes::define_num_dias($fecha_from,$fecha_to)." ".str_replace("-","",$fecha_from)." - ".str_replace("-","",$fecha_to)." al ".str_replace("-","",$fecha)."</b></td>
                   </tr>";
        $reporte.="<tr>
                    <td colspan='10'></td>
                   </tr>
                   <tr>
                    <td ".$style_title."><b>TIPO DE FACTURACION</b></td>
                    <td ".$style_description." colspan='3'>".Reportes::define_num_dias($fecha_from,$fecha_to)."</td>
                    <td colspan='6'></td>
                    </tr>";
        $reporte.="<tr>
                    <td colspan='10'></td>
                   </tr>
                   <tr>
                    <td ".$style_title."><b>PERIODO</b></td>
                    <td ".$style_description." colspan='3'>".Utility::formatDateSINE($fecha_from,"F j")." - ".Utility::formatDateSINE($fecha_to,"F j")."</td>
                    <td colspan='6'></td>
                   </tr>";
        $reporte.="<tr>
                    <td colspan='10'></td>
                   </tr>
                   <tr>
                    <td colspan='3'" .$style_provisiones. "><b>CAPTURA</b></td>
                    <td colspan='4'" .$style_sori. "><b>FACTURACION SORI</b></td>
                    <td colspan='3'" .$style_diference. "><b>DIFERENCIAS</b></td>
                   </tr>";
        $reporte.="<tr>
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
        if($provisiones!=null)
        {
            foreach($provisiones as $key => $provision)
            {
                $facturas=self::getFacturas($provision,$tipo_report);
                if($facturas!=null)
                {
                    $facturas_minutes=Yii::app()->format->format_decimal($facturas->minutes,3);
                    $facturas_amount=Yii::app()->format->format_decimal($facturas->amount,3);
                    $dif_amount=$provision->amount-$facturas->amount;
                    $dif_minutes=$provision->minutes-$facturas->minutes;
                    $doc_number=$facturas->doc_number;
                    $acumulado_factura=Reportes::define_total_facturas($facturas,$acumulado_factura);
                }
                else
                {
                    $facturas_minutes="-";
                    $facturas_amount="-";
                    $dif_amount=$provision->amount;
                    $dif_minutes=$provision->minutes;
                    $acumulado_factura=0;
                    $doc_number="-";
                }
                $acumulado_provisiones=Reportes::define_total_provisiones($provision,$acumulado_provisiones);
                $acumulado_diference=Reportes::define_total_diference($dif_amount,$acumulado_diference);
                $reporte.="<tr>
                            <td {$style_basic} >".$provision->carrier."</td>
                            <td {$style_basic_number} >".Yii::app()->format->format_decimal($provision->minutes,3)."</td>
                            <td {$style_basic_number} >".Yii::app()->format->format_decimal($provision->amount,3)."</td>
                            <td {$style_basic} >".$provision->carrier."</td>
                            <td {$style_basic_number} >".$facturas_minutes."</td>
                            <td {$style_basic_number} >".$facturas_amount."</td>
                            <td {$style_basic} >".$doc_number."</td>
                            <td {$style_basic} >".$provision->carrier."</td>
                            <td {$style_basic_number} >".Yii::app()->format->format_decimal($dif_minutes,3)."</td>
                            <td {$style_basic_number} >".Yii::app()->format->format_decimal($dif_amount,3)."</td>
                           </tr>";
            }
        }
        $reporte.="<tr>
                    <td {$style_provisiones} ><b>TOTAL</b></td>
                    <td {$style_provisiones} ></td>
                    <td {$style_totals} ><b>".Yii::app()->format->format_decimal($acumulado_provisiones,3)."</b></td>
                    <td {$style_sori} ><b>TOTAL</b></td>
                    <td {$style_sori} ></td>
                    <td {$style_totals} ><b>".Yii::app()->format->format_decimal($acumulado_factura,3)."</b></td>
                    <td {$style_sori} ></td>
                    <td {$style_diference} ><b>TOTAL</b></td>
                    <td {$style_diference} ></td>
                    <td {$style_totals} ><b>" .Yii::app()->format->format_decimal($acumulado_diference,3)."</b></td>
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
    private static function getProvisions($fecha_from, $fecha_to,$tipo_report) 
    {
        if($tipo_report=="REFAC") $type_accounting_document="Provision Factura Enviada";
        else $type_accounting_document="Provision Factura Recibida";
            
        $sql="SELECT a.id, a.doc_number, a.from_date, a.to_date,a.amount, a.minutes, a.id_carrier, c.name AS carrier
              FROM accounting_document a, carrier c 
              WHERE a.id_carrier=c.id
                AND id_type_accounting_document=(SELECT id FROM type_accounting_document WHERE name='{$type_accounting_document}')
                AND from_date>='{$fecha_from}'
                AND to_date<='{$fecha_to}'
              ORDER BY from_date";
        return AccountingDocument::model()->findAllBySql($sql);           
    }

    /**
     * ejecuta la consulta a todos los datos de facturacion de sori, la unica particularidad es que dependiendo de la variable $tipo_report, cambia el id_type_accounting_document
     * trae el sql pricipal de sori
     * @access public
     * @static
     * @param date $fecha_from
     * @param date $fecha_to
     * @return array
     */
    private static function getFacturas($model,$tipo_report) 
    {
        if($tipo_report=="REFAC") $type_accounting_document="Factura Enviada";
        else $type_accounting_document="Factura Recibida";
        $sql="SELECT id, doc_number, from_date, to_date, amount, minutes, id_carrier
              FROM accounting_document 
              WHERE id_carrier= {$model->id_carrier}
                AND id_type_accounting_document=(SELECT id FROM type_accounting_document WHERE name='{$type_accounting_document}')
                AND from_date>='{$model->from_date}'
                AND to_date<='{$model->to_date}'
              ORDER BY from_date";
                  
            return AccountingDocument::model()->findBySql($sql);
        }
    }
?>