<?php

//Definimos nuestro servidor de produccion
define('SERVER_NAME_PROD','s1248-101');
//Definimos el directorio de desarrollo
define('DIRECTORY_NAME_PRE_PROD','dev_sine');
//Obtenemos el nombre del servidor actual
$server=gethostname();
if($server==SERVER_NAME_PROD)
{
	$server=dirname(__FILE__);
	$nuevo=explode(DIRECTORY_SEPARATOR,$server);
	$num=count($nuevo);
	if($nuevo[$num-3]==DIRECTORY_NAME_PRE_PROD)
	{
		defined('YII_DEBUG') or define('YII_DEBUG',true);
		defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
	}
	else
	{
		defined('YII_DEBUG') or define('YII_DEBUG',false);
		defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',0);
	}
}
else
{
	defined('YII_DEBUG') or define('YII_DEBUG',true);
	defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
}
$yii=dirname(__FILE__).'/../../../yii/framework/yii.php';
require_once($yii);

$main=require(dirname(__FILE__).'/protected/config/console.php');
$db=require(dirname(__FILE__).'/protected/config/db.php');

$config=CMap::mergeArray($main,$db);

Yii::createConsoleApplication($config)->run();
