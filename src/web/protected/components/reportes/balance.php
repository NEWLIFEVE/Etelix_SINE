<?php
/**
* @package reportes
*/
class balance extends Reportes
{
	public static function reporte($grupo,$fecha,$Si_prov,$Si_disp)
	{
        if($Si_prov!=NULL)
            $tablaProv="<h3>provisiones</h3><table style='background:#83380E; color:white;border-bottom: 4px solid silver;text-align:center;'>
                        <tr>
                           <td>grupo</td><td>fecha</td><td>Prov</td>
                        </tr>
                        <tr style='background:white;color:#2E62B4;'>
                           <td>$grupo</td><td>".Utility::formatDateSINE($fecha,"F j - Y")."</td><td>$Si_prov</td>
                        </tr>
                        </table>";else $tablaProv=NULL;
                        
        if($Si_disp!=NULL)
            $tablaDisp="<h3>Disputas</h3><table style='background:black; color:white;border-bottom: 4px solid silver;text-align:center;'>
                        <tr>
                           <td>grupo</td><td>fecha</td><td>Disp</td>
                        </tr>
                        <tr style='background:white;color:#2E62B4;'>
                           <td>$grupo</td><td>".Utility::formatDateSINE($fecha,"m-F-y")."</td><td>$Si_disp</td>
                        </tr>
                        </table>";else $tablaDisp=NULL;
                        
        $model=balance::get_Model();
        $saldo=($model->amount*3);
    
        $cuerpo_tabla="<tr style='background:white;color:#2E62B4;border:1px solid black;'>
                        <td>".$model->doc_number." (".Utility::formatDateSINE($model->from_date,"M-").Utility::formatDateSINE($model->from_date,"d-").Utility::formatDateSINE($model->to_date,"d").")</td>
                        <td>".$model->issue_date."</td><td>".$model->from_date."</td><td>".$model->amount."</td><td>".$model->amount."</td><td>".$model->amount."</td><td>".$model->amount."</td><td>$saldo</td>
                       </tr>";

        $tabla_balance="<h1>tabla de prueba balance</h1>
                        <table style='background:rgb(188, 245, 253);border:1px solid black;text-align:center;'>
                         <tr style='border:1px solid black;'>
                          <td>Descripcion</td><td>issue_date</td><td>Due date</td><td>Payments (Etx to IDT)</td><td>Invoices receved</td><td>Payments (IDT to Etx)</td><td>Invoices collect</td><td>Due balance</td>
                         </tr>
                         $cuerpo_tabla
                        </table>";
        
        $reporte= $tabla_balance."<br>".$tablaDisp."<br>".$tablaProv;
            
            return $reporte;
        }
        
        private static function get_Model()
        {
            $sql="SELECT doc_number, from_date, to_date,issue_date, to_date,amount FROM accounting_document";
            return AccountingDocument::model()->findBySql($sql);
        }
}
?>