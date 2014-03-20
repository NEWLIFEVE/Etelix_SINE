<?php
date_default_timezone_set('America/Caracas');
// change the following paths if necessary
//Produccion
define('SERVER_NAME_PROD','sine.sacet.com.ve');
//Preproduccion
define('SERVER_NAME_PRE_PROD','devsine.sacet.com.ve');
//Desarrollo
define('SERVER_NAME_DEV','sine.local');
//Obtenemos el nombre del servidor actual
$server=$_SERVER['SERVER_NAME'];

$yii='../../../yii/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

switch ($server)
{
	case SERVER_NAME_PROD:
		// remove the following lines when in production mode
		defined('YII_DEBUG') or define('YII_DEBUG',false);
		// specify how many levels of call stack should be shown in each log message
		defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',0);
		break;
	case SERVER_NAME_PRE_PROD:
		// remove the following lines when in production mode
		defined('YII_DEBUG') or define('YII_DEBUG',true);
		// specify how many levels of call stack should be shown in each log message
		defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
		break;
	case SERVER_NAME_DEV:
	default:
		// remove the following lines when in production mode
		defined('YII_DEBUG') or define('YII_DEBUG',true);
		// specify how many levels of call stack should be shown in each log message
		defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
		break;
}

require_once($yii);
Yii::createWebApplication($config)->run();
