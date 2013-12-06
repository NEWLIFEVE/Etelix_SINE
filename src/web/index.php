<?php
date_default_timezone_set('America/Caracas');
// change the following paths if necessary

$yii='../../../yii/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
//defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
//defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
Yii::createWebApplication($config)->run();

//function format_decimal($num,$decimales=3)
//    {   if($num!=""){     
//        $english_format_number2 = number_format($num, 10, ',', '.');
//        $numtext=strval($english_format_number2);
//        $position = strpos($numtext, ',');
//        $numsub = substr($numtext,0,$position+$decimales); 
//        return $numsub;
//        }else{
//            return "";
//        }
//    }
//    
//    ECHO format_decimal(-11909.9206083333, 3);
