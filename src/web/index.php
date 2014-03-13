<?php
date_default_timezone_set('America/Caracas');
//Definimos nuestro servidor de produccion
define('SERVER_NAME_PROD','sine.sacet.com.ve');
//Definimos nuestro servidor de preproduccion
define('SERVER_NAME_PRE_PROD','devsine.sacet.com.ve');
//Definimos nuestro servidor de desarrollo
define('SERVER_NAME_DEV','sine.local');
//Obtenemos el nombre del servidor actual
$server=$_SERVER['SERVER_NAME'];
// change the following paths if necessary
$yii='../../../yii/framework/yii.php';
switch ($server)
{
	case 'SERVER_NAME_PROD':
		$config=dirname(__FILE__).'/protected/config/main.php';
		defined('YII_DEBUG') or define('YII_DEBUG',false);
		defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',0);
		break;
	case 'SERVER_NAME_PRE_PROD':
		$config=dirname(__FILE__).'/protected/config/main_pre.php';
		defined('YII_DEBUG') or define('YII_DEBUG',true);
		defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
		break;
	case 'SERVER_NAME_PRE_PROD':
	default:
		$config=dirname(__FILE__).'/protected/config/main_dev.php';
		defined('YII_DEBUG') or define('YII_DEBUG',true);
		defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
		break;
}

require_once($yii);
Yii::createWebApplication($config)->run();
