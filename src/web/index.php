<?php
//Definimos nuestro servidor de produccion
define('SERVER_NAME_PROD','sine.sacet.com.ve');
//Obtenemos el nombre del servidor actual
$server=$_SERVER['SERVER_NAME'];

switch ($server)
{
	case SERVER_NAME_PROD:
		defined('YII_DEBUG') or define('YII_DEBUG',false);
		defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',0);
		break;
	default:
		defined('YII_DEBUG') or define('YII_DEBUG',true);
		defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
		break;
}
$yii=dirname(__FILE__).'/../../../yii/framework/yii.php';
require_once($yii);

$main=require(dirname(__FILE__).'/protected/config/main.php');
$db=require(dirname(__FILE__).'/protected/config/db.php');
$gii=require(dirname(__FILE__).'/protected/config/gii.php');

$config=CMap::mergeArray($main,$db,$gii);

Yii::createWebApplication($config)->run();



