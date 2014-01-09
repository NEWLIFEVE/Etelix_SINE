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
            $seg=count($carrierGroups);
            ini_set('max_execution_time', $seg);
            
            $reporte="<table>";
            $reporte.="<tr>
                           <td colspan='2'><h1>RECREDI</h1></td>
                           <td colspan='8'>  AL  $fecha </td>
                       </tr>";
            
            $reporte.="<tr $style_basic_head >
                           <td $style_basic_head >  </td>
                           <td $style_basic_head >  </td>
                           <td $style_basic_head > FECHA </td>
                           <td $style_basic_head colspan='2'> PROVISION FACT </td>
                           <td $style_basic_head colspan='2'> PROVISION TRAFICO </td>
                           <td $style_basic_head colspan='2'> DISPUTAS </td>
                           <td $style_basic_head >  </td>
                       </tr>";
  
            $reporte.="<tr $style_basic_head >
                           <td $style_basic_head > CARRIER </td>
                           <td $style_basic_head > SOA </td>
                           <td $style_basic_head > ULTIMO REGISTRO SOA </td>
                           <td $style_basic_head > CLIENTES REVENUE </td>
                           <td $style_basic_head > PROVEEDORES COST </td>
                           <td $style_basic_head > CLIENTES REVENUE </td>
                           <td $style_basic_head > PROVEEDORES COST </td>
                           <td $style_basic_head > CLIENTES RECIBIDAS </td>
                           <td $style_basic_head > PROVEEDORES ENVIADAS </td>
                           <td $style_basic_head > BALANCE </td>
                       </tr>";
            foreach ($carrierGroups as $key => $group)
            {
                $SOA=self::getSoaCarrier($group->id,$fecha);
                $prov_fac_env=self::getProvisionsFact($group->id,$fecha,TRUE);
                $prov_fac_rec=self::getProvisionsFact($group->id,$fecha,FALSE);
                $prov_traf_env=self::getProvisionsTraf($group->id,$fecha,TRUE);
                $prov_traf_rec=self::getProvisionsTraf($group->id,$fecha,FALSE);
                $prov_disp_rec=self::getDisp($group->id,$fecha,TRUE);
                $prov_disp_env=self::getDisp($group->id,$fecha,FALSE);
                $balance=self::getBalanceCarrier($group->id,$fecha);
                    $reporte.="<tr $style_basic >
                                    <td $style_basic > $group->name </td>
                                    <td $style_basic >". Yii::app()->format->format_decimal($SOA->amount). "</td>
                                    <td $style_basic > ULTIMO REGISTRO SOA </td>
                                    <td $style_basic >". Yii::app()->format->format_decimal($prov_fac_env->amount). "</td>
                                    <td $style_basic >". Yii::app()->format->format_decimal($prov_fac_rec->amount). "</td>
                                    <td $style_basic >". Yii::app()->format->format_decimal($prov_traf_env->amount). "</td>
                                    <td $style_basic >". Yii::app()->format->format_decimal($prov_traf_rec->amount). "</td>
                                    <td $style_basic >". Yii::app()->format->format_decimal($prov_disp_rec->amount). "</td>
                                    <td $style_basic >". Yii::app()->format->format_decimal($prov_disp_env->amount). "</td>
                                    <td $style_basic >". Yii::app()->format->format_decimal($balance->amount). "</td>
                               </tr>";
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
                  FROM(SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(1,3) AND id_carrier ={$id} AND issue_date<='{$date}') p,
                      (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(2,4) AND id_carrier ={$id} AND issue_date<='{$date}') n";
            return AccountingDocument::model()->findBySql($sql);
        }
        public static function getBalanceCarrier($id,$date)
        {
            $sql="SELECT (p.amount-n.amount) AS amount
                  FROM(SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(1,3,6,7,10,12) AND id_carrier ={$id} AND issue_date<='{$date}') p,
                      (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(2,4,5,8,11,13) AND id_carrier ={$id} AND issue_date<='{$date}') n";
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
            if($type) $type_id="12";
            else      $type_id="13";
            
            $sql="SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document ={$type_id} AND id_carrier={$id} AND issue_date<='{$date}'";
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
            if($type) $type_id="12";
            else      $type_id="13";
            $sql="SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document ={$type_id} AND id_carrier={$id} AND issue_date<='{$date}'";
            return AccountingDocument::model()->findBySql($sql);
        }
        public static function getDisp($id,$date,$type=TRUE)
        {
            if($type) $type_id="5";
            else      $type_id="6";
            $sql="SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document ={$type_id} AND id_carrier={$id} AND issue_date<='{$date}'";
            return AccountingDocument::model()->findBySql($sql);
        }
            
    }
    ?>