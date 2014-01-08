 <?php

    /**
     * @package reportes
     */
    class Recredi extends Reportes 
    {
        public static function reporte($fecha) 
        {
            $style_basic="style='border:1px solid black;text-align:center;'";
            $style_basic_head="style='border:1px solid black;background:#38BEE9;text-align:center;'";
            
            $carrierGroups=self::getAllGroups();
            
            $reporte="<table><tr>";
            $reporte.="<td>RECREDI</td>";
            $reporte.="<td colspan='9'>  AL  $fecha </td>";
            $reporte.="</tr>";
            
            $reporte.="<tr $style_basic_head >";
            $reporte.="<td $style_basic_head >  </td>";
            $reporte.="<td $style_basic_head >  </td>";
            $reporte.="<td $style_basic_head > FECHA </td>";
            $reporte.="<td $style_basic_head colspan='2'> PROVISION FACT </td>";
            $reporte.="<td $style_basic_head colspan='2'> PROVISION TRAFICO </td>";
            $reporte.="<td $style_basic_head colspan='2'> DISPUTAS </td>";
            $reporte.="<td $style_basic_head >  </td>";
            $reporte.="</tr>";
  
            $reporte.="<tr $style_basic_head >";
            $reporte.="<td $style_basic_head > CARRIER </td>";
            $reporte.="<td $style_basic_head > SOA </td>";
            $reporte.="<td $style_basic_head > ULTIMO REGISTRO SOA </td>";
            $reporte.="<td $style_basic_head > CLIENTES REVENUE </td>";
            $reporte.="<td $style_basic_head > PROVEEDORES COST </td>";
            $reporte.="<td $style_basic_head > CLIENTES REVENUE </td>";
            $reporte.="<td $style_basic_head > PROVEEDORES COST </td>";
            $reporte.="<td $style_basic_head > CLIENTES RECIBIDAS </td>";
            $reporte.="<td $style_basic_head > PROVEEDORES ENVIADAS </td>";
            $reporte.="<td $style_basic_head > BALANCE </td>";
            $reporte.="</tr>";
            foreach ($carrierGroups as $key => $group)
            {
                    $reporte.="<tr $style_basic >";
                    $reporte.="<td $style_basic > $group->name </td>";
                    $reporte.="<td $style_basic >" .self::getSoaCarrier($group->id,$fecha). "</td>";
                    $reporte.="<td $style_basic > ULTIMO REGISTRO SOA </td>";
                    $reporte.="<td $style_basic > CLIENTES " .self::getProvisionsFact($group->id,$fecha,TRUE). " </td>";
                    $reporte.="<td $style_basic > PROVEEDORES " .self::getProvisionsFact($group->id,$fecha,FALSE). " </td>";
                    $reporte.="<td $style_basic > CLIENTES REVENUE " .self::getProvisionsTraf($group->id,$fecha,TRUE). "</td>";
                    $reporte.="<td $style_basic > PROVEEDORES COST " .self::getProvisionsTraf($group->id,$fecha,TRUE). "</td>";
                    $reporte.="<td $style_basic > CLIENTES RECIBIDAS </td>";
                    $reporte.="<td $style_basic > PROVEEDORES ENVIADAS </td>";
                    $reporte.="<td $style_basic > BALANCE </td>";
                    $reporte.="</tr>";
                }
            $reporte.="</table>";
            return $reporte;
        }
        /**
         * @return type
         */
        public static function getAllGroups()
        {
            return CarrierGroups::model()->findAll();
        }
        /**
         * 
         * @param type $id
         * @param type $date
         * @return type
         */
        public static function getSoaCarrier($id,$date)
        {
            $sql="SELECT (p.amount-n.amount) AS amount
                  FROM(SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(1,3) AND id_carrier={$id} AND issue_date<='{$date}') p,
                      (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(2,4) AND id_carrier={$id} AND issue_date<='{$date}') n";
            return AccountingDocument::model()->findBySql($sql);
        }
        /**
         * 
         * @param type $id
         * @param type $date
         * @param type $type
         * @return type
         */
        public static function getProvisionsFact($id,$date,$type=TRUE)
        {
            if($type) $condicion="12";
            else      $condicion="13";
            
            $sql="SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document ={$type} AND id_carrier={$id} AND issue_date<='{$date}'";
            return AccountingDocument::model()->findBySql($sql);
        }
        /**
         * 
         * @param type $id
         * @param type $date
         * @param type $type
         * @return type
         */
        public static function getProvisionsTraf($id,$date,$type=TRUE)
        {
            if($type) $condicion="12";
            else      $condicion="13";
            $sql="SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document ={$type} AND id_carrier={$id} AND issue_date<='{$date}'";
            return AccountingDocument::model()->findBySql($sql);
        }
            
    }
    ?>