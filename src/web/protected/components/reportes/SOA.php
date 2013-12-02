<?php
/**
* @package reportes
*/
class SOA extends Reportes
{
	public static function reporte($grupo,$fecha,$Si_disp,$Si_prov)
	{
                                                                                                    if($Si_prov!=NULL)
                                                                                                    $tablaProv="<h3>provisiones</h3><table style='background:#83380E; color:white;border-bottom: 4px solid silver;text-align:center;'>
                                                                                                                 <tr>
                                                                                                                  <td>grupo</td><td>fecha</td><td>Prov</td>
                                                                                                                 </tr>
                                                                                                                 <tr style='background:white;color:#2E62B4;'>
                                                                                                                  <td>$grupo</td><td>".Utility::formatDateSINE($fecha,"F j - Y")."".Utility::formatDateSINE($fecha,"m - F - y")."</td><td>$Si_prov</td>
                                                                                                                 </tr>
                                                                                                                </table>";else $tablaProv=NULL;

            $model=SOA::get_Model($grupo,$fecha,$Si_disp);//trae el sql pricipal

            //forma los tr de la tabla, donde va la informacion
            $cuerpo_tabla="<tr style='background:white;color:#2E62B4;border:1px solid black;'>
                            <td>".$model->doc_number." (".Utility::formatDateSINE($model->from_date,"M-").Utility::formatDateSINE($model->from_date,"d-").Utility::formatDateSINE($model->to_date,"d").")</td>
                            <td>".$model->issue_date."</td><td>".$model->from_date."</td><td>".$model->amount."</td><td>".$model->amount."</td><td>".$model->amount."</td><td>".$model->amount."</td><td>$model->amount</td>
                           </tr>";
            //concatena los tr que contienen informacion con la cabecera y resto de la tabla
            $tabla_SOA="<h1>tabla de prueba SOA</h1>
                        <table style='background:rgb(188, 245, 253);border:1px solid black;text-align:center;'>
                         <tr style='border:1px solid black;'>
                          <td>Descripcion</td><td>issue_date</td><td>Due date</td><td>Payments (Etx to IDT)</td><td>Invoices receved</td><td>Payments (IDT to Etx)</td><td>Invoices collect</td><td>Due balance</td>
                          </tr>
                          $cuerpo_tabla
                        </table>";

            $reporte=$tabla_SOA."<br>".$tablaProv;

            return $reporte;
        }
        /**
         * sql para el reporte soa
         * @param type $grupo
         * @param type $fecha
         * @param type $Si_disp
         * @return type
         */
        private static function get_Model($grupo,$fecha,$Si_disp)
        {
            $sql="select a.id as idDocument,a.issue_date,g.name as Group,c.name as Carrier, tp.name as TP, t.name as Type, a.from_date, a.to_date, a.doc_number, a.amount,s.name as currency 
            from accounting_document a, type_accounting_document t, carrier c, currency s, contrato x, contrato_termino_pago xtp, termino_pago tp, carrier_groups g
            where a.id_carrier IN(Select id from carrier where id_carrier_groups=$grupo) and a.id_type_accounting_document = t.id and a.id_carrier = c.id and a.id_currency = s.id 
            and a.id_carrier = x.id_carrier and x.id = xtp.id_contrato and xtp.id_termino_pago = tp.id and xtp.end_date IS NULL and c.id_carrier_groups = g.id and a.issue_date < '{$fecha}'
            $Si_disp
            order by issue_date";
            
            return AccountingDocument::model()->findBySql($sql);
        }

        /**
         * define si la consulta traera las disputas o no
         * si es diferente de null, el sql es standar, es decir, traera las disputas, sino, entonces el sql no traera las disputas, puesto que le esta indicando la condicion de "NOT IN (5,6)"
         * @param type $Si_disp
         * @return string
         */

}
?>