 <?php

    /**
     * @package reportes
     */
    class recopa extends Reportes 
    {
        public static function reporte($fecha) 
        {
            $style_basic="style='border:1px solid black;text-align:center;'";
            $style_oper_head="style='border:1px solid black;background:silver;text-align:center;color:white;'";
            $style_before_head="style='border:1px solid black;background:#F89289;text-align:center;color:white;'";
            $style_now_head="style='border:1px solid black;background:#06ACFA;text-align:center;color:white;'";
            $style_next_head="style='border:1px solid black;background:#049C47;text-align:center;color:white;'";
            
            $carrierGroups=Recredi::getAllGroups();
            $seg=count($carrierGroups)*2;
            ini_set('max_execution_time', $seg);
            
            $tabla_recopa= "<h1>RECOPA <h3>(".$fecha." - ".date("g:i a").")</h3></h1>";
            $tabla_recopa.="<table>
                             <tr>
                              <td $style_oper_head> Operadores </td>
                              <td $style_before_head>". Reportes::define_due_date("7", $fecha,"-") ."</td>
                              <td $style_now_head> $fecha </td>
                              <td $style_next_head>". Reportes::define_due_date("7", $fecha,"+") ."</td>
                              <td $style_next_head>". Reportes::define_due_date("14", $fecha,"+") ."</td>
                              <td $style_next_head>". Reportes::define_due_date("21", $fecha,"+") ."</td>
                              <td $style_next_head>". Reportes::define_due_date("28", $fecha,"+") ."</td>
                              <td $style_next_head>". Reportes::define_due_date("35", $fecha,"+") ."</td>
                              <td $style_next_head>". Reportes::define_due_date("42", $fecha,"+") ."</td>
                              <td $style_next_head>". Reportes::define_due_date("49", $fecha,"+") ."</td>
                              <td $style_next_head>". Reportes::define_due_date("56", $fecha,"+") ."</td>
                              <td $style_next_head>". Reportes::define_due_date("63", $fecha,"+") ."</td>
                              <td $style_next_head>". Reportes::define_due_date("70", $fecha,"+") ."</td>
                             </tr>";
            foreach ($carrierGroups as $key => $group)
            {
                $SOA=Recredi::getSoaCarrier($group->id,$fecha);

                $tabla_recopa.=" <tr>
                                  <td $style_basic> $group->name </td>
                                  <td $style_basic>". Yii::app()->format->format_decimal($SOA->amount). "</td>
                                  <td $style_basic></td>
                                  <td $style_basic></td>
                                  <td $style_basic></td>
                                  <td $style_basic></td>
                                  <td $style_basic></td>
                                  <td $style_basic></td>
                                  <td $style_basic></td>
                                  <td $style_basic></td>
                                  <td $style_basic></td>
                                  <td $style_basic></td>
                                  <td $style_basic></td>
                                 </tr>";
           }
            echo $tabla_recopa;
        }
    }
    ?>

