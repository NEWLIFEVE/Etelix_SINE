<?php
$yii=dirname(__FILE__).'/../../../yii/framework/yii.php';
require_once($yii);
//Definimos nuestro servidor de produccion
define('SERVER_NAME_PROD','sine.sacet.com.ve');
//Definimos nuestro servidor de preproduccion
define('SERVER_NAME_PRE_PROD','devsine.sacet.com.ve');
//Definimos nuestro servidor de desarrollo
define('SERVER_NAME_DEV','sine.local');
//Obtenemos el nombre del servidor actual
$server=$_SERVER['SERVER_NAME'];

switch ($server)
{
	case SERVER_NAME_PROD:
		defined('YII_DEBUG') or define('YII_DEBUG',false);
		defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',0);
		break;
	case SERVER_NAME_PRE_PROD:
		defined('YII_DEBUG') or define('YII_DEBUG',true);
		defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
		break;
	case SERVER_NAME_DEV:
	default:
		defined('YII_DEBUG') or define('YII_DEBUG',true);
		defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
		break;
}

$main=require(dirname(__FILE__).'/protected/config/main.php');
$db=require(dirname(__FILE__).'/protected/config/db.php');

$config=CMap::mergeArray($main,$db);

Yii::createWebApplication($config)->run();



