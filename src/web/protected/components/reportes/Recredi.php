 <?php

    /**
     * @package reportes
     */
    class Recredi extends Reportes 
    {
        public static function reporte($fecha) 
        {
            $style_basic="style='border:1px solid black;text-align:center;'";
            $style_carrier_head="style='border:0px solid black;background:silver;text-align:center;color:white;'";
            $style_soa_head="style='border:0px solid black;background:#3466B4;text-align:center;color:white;'";
            $style_prov_fact_head="style='border:1px solid black;background:#E99241;text-align:center;color:white;'";
            $style_prov_traf_head="style='border:1px solid black;background:#248CB4;text-align:center;color:white;'";
            $style_prov_disp_head="style='border:1px solid black;background:#C37881;text-align:center;color:white;'";
            $style_balance_head="style='border:0px solid black;background:#2E62B4;text-align:center;color:white;'";
            
            $acumulado_soa=$acumulado_prov_fac_env=$acumulado_prov_fac_rec=$acumulado_prov_traf_env=$acumulado_prov_traf_rec=$acumulado_disp_rec=$acumulado_disp_env=$acumulado_balance=0;

            $carrierGroups=self::getAllGroups();
            $seg=count($carrierGroups)*3;
            ini_set('max_execution_time', $seg);
            
            $reporte="<table>";
            $reporte.="<tr>
                           <td colspan='2'><h1>RECREDI</h1></td>
                           <td colspan='8'>  AL  $fecha </td>
                       <tr>
                           <td colspan='10'></td>
                       </tr>
                     </table>";
            $reporte.="<table $style_basic >
                       <tr>
                           <td $style_carrier_head >  </td>
                           <td $style_soa_head >  </td>
                           <td $style_soa_head >  </td>
                           <td $style_prov_fact_head colspan='2'> PROVISION FACT </td>
                           <td $style_prov_traf_head colspan='2'> PROVISION TRAFICO </td>
                           <td $style_prov_disp_head colspan='2'> DISPUTAS </td>
                           <td $style_balance_head >  </td>
                       </tr>";
            $reporte.="<tr>
                           <td $style_carrier_head > CARRIER </td>
                           <td $style_soa_head > SOA </td>
                           <td $style_soa_head > DUE DATE </td>
                           <td $style_prov_fact_head > CLIENTES REVENUE </td>
                           <td $style_prov_fact_head > PROVEEDORES COST </td>
                           <td $style_prov_traf_head > CLIENTES REVENUE </td>
                           <td $style_prov_traf_head > PROVEEDORES COST </td>
                           <td $style_prov_disp_head > CLIENTES RECIBIDAS </td>
                           <td $style_prov_disp_head > PROVEEDORES ENVIADAS </td>
                           <td $style_balance_head > BALANCE </td>
                       </tr>";
            foreach ($carrierGroups as $key => $group)
            {
                $SOA=self::getSoaCarrier($group->id,$fecha);
                $SOA_due_date=Reportes::getDueDate($group->id);
                $prov_fac_env=self::getProvisionsFact($group->id,$fecha,TRUE);
                $prov_fac_rec=self::getProvisionsFact($group->id,$fecha,FALSE);
                $prov_traf_env=self::getProvisionsTraf($group->id,$fecha,TRUE);
                $prov_traf_rec=self::getProvisionsTraf($group->id,$fecha,FALSE);
                $disp_rec=self::getDisp($group->id,$fecha,TRUE);
                $disp_env=self::getDisp($group->id,$fecha,FALSE);
                $balance=self::getBalanceCarrier($group->id,$fecha);
                    $reporte.="<tr $style_basic >
                                    <td $style_basic > $group->name </td>
                                    <td $style_basic >". Yii::app()->format->format_decimal($SOA->amount). "</td>
                                    <td $style_basic >". self::llenaIfNull($SOA_due_date, "Nota 1") ."</td>
                                    <td $style_basic >". Yii::app()->format->format_decimal($prov_fac_env->amount). "</td>
                                    <td $style_basic >". Yii::app()->format->format_decimal($prov_fac_rec->amount). "</td>
                                    <td $style_basic >". Yii::app()->format->format_decimal($prov_traf_env->amount). "</td>
                                    <td $style_basic >". Yii::app()->format->format_decimal($prov_traf_rec->amount). "</td>
                                    <td $style_basic >". Yii::app()->format->format_decimal($disp_rec->amount). "</td>
                                    <td $style_basic >". Yii::app()->format->format_decimal($disp_env->amount). "</td>
                                    <td $style_basic >". Yii::app()->format->format_decimal($balance->amount). "</td>
                               </tr>";
                $acumulado_soa= self::acumulado($acumulado_soa,$SOA->amount);
                $acumulado_prov_fac_env= self::acumulado($acumulado_prov_fac_env,$prov_fac_env->amount);
                $acumulado_prov_fac_rec= self::acumulado($acumulado_prov_fac_rec,$prov_fac_rec->amount);
                $acumulado_prov_traf_env= self::acumulado($acumulado_prov_traf_env,$prov_traf_env->amount);
                $acumulado_prov_traf_rec= self::acumulado($acumulado_prov_traf_rec,$prov_traf_rec->amount);
                $acumulado_disp_rec= self::acumulado($acumulado_disp_rec,$disp_rec->amount);
                $acumulado_disp_env= self::acumulado($acumulado_disp_env,$disp_env->amount);
                $acumulado_balance= self::acumulado($acumulado_balance,$balance->amount);
            }
            $reporte.="<tr>
                            <td $style_carrier_head > TOTALES </td>
                            <td colspan='2' $style_soa_head >".Yii::app()->format->format_decimal($acumulado_soa). "</td>
                            <td $style_prov_fact_head >". Yii::app()->format->format_decimal($acumulado_prov_fac_env). "</td>
                            <td $style_prov_fact_head >". Yii::app()->format->format_decimal($acumulado_prov_fac_rec). "</td>
                            <td $style_prov_traf_head >". Yii::app()->format->format_decimal($acumulado_prov_traf_env). "</td>
                            <td $style_prov_traf_head >". Yii::app()->format->format_decimal($acumulado_prov_traf_rec). "</td>
                            <td $style_prov_disp_head >". Yii::app()->format->format_decimal($acumulado_disp_rec). "</td>
                            <td $style_prov_disp_head >". Yii::app()->format->format_decimal($acumulado_disp_env). "</td>
                            <td $style_balance_head >".   Yii::app()->format->format_decimal($acumulado_balance). "</td>
                      </tr>";
            $reporte.="</table>";
            $reporte.="<br>Nota 1: No presenta movimiento a la fecha, esto deberá ser agregado al final del documento";
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
            $sql="SELECT (i.amount+(p.amount-n.amount)) AS amount
                  FROM
                    (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document where id_type_accounting_document=9 and id_carrier IN(Select id from carrier where id_carrier_groups = {$id})) i,
                    (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(1,3,8,15) AND id_carrier IN(Select id from carrier where id_carrier_groups = {$id}) AND issue_date<='{$date}') p,
                    (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(2,4,7,14) AND id_carrier IN(Select id from carrier where id_carrier_groups = {$id}) AND issue_date<='{$date}') n";
            return AccountingDocument::model()->findBySql($sql);
        }
        public static function getBalanceCarrier($id,$date)
        {
            $sql="SELECT (i.amount+(p.amount-n.amount)) AS amount
                  FROM
                    (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document where id_type_accounting_document=9 and id_carrier IN(Select id from carrier where id_carrier_groups = {$id})) i,

                    (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(1,3,6,8,10,12,15) AND id_carrier IN(Select id from carrier where id_carrier_groups = {$id}) AND issue_date<='{$date}' AND confirm != -1) p,
                    (SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM accounting_document WHERE id_type_accounting_document IN(2,4,5,7,11,13,14) AND id_carrier IN(Select id from carrier where id_carrier_groups = {$id}) AND issue_date<='{$date}' AND confirm != -1) n";

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
            
            $sql="SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount 
                  FROM accounting_document 
                  WHERE id_type_accounting_document ={$type_id} AND id_carrier IN(Select id from carrier where id_carrier_groups = {$id}) AND issue_date<='{$date}' AND confirm != -1";
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
            if($type) $type_id="10";
            else      $type_id="11";
            $sql="SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount 
                  FROM accounting_document 
                  WHERE id_type_accounting_document ={$type_id} AND id_carrier IN(Select id from carrier where id_carrier_groups = {$id}) AND issue_date<='{$date}' AND confirm != -1";
            return AccountingDocument::model()->findBySql($sql);
        }
        /**
         * 
         * @param type $id
         * @param type $date
         * @param type $type
         * @return type
         */
        public static function getDisp($id,$date,$type=TRUE)
        {
            if($type) $type_id="5";
            else      $type_id="6";
            $sql="SELECT CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount 
                  FROM accounting_document 
                  WHERE id_type_accounting_document ={$type_id} AND id_carrier IN(Select id from carrier where id_carrier_groups = {$id}) AND issue_date<='{$date}'";
            return AccountingDocument::model()->findBySql($sql);
        } 
        public static function acumulado($acumulado,$amount)
        {
            if($amount!=null||$amount!=0)
              return $acumulado+$amount;
            else
                return $acumulado;
        }
        public static function llenaIfNull($var,$if_null)
        {
            if($var==null)
                return $if_null;
              else
                return $var;
        }
        
    }
    ?>