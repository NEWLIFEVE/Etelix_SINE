<?php
date_default_timezone_set('America/Caracas');
//Definimos nuestro servidor de produccion
define('SERVER_NAME_PROD','s1248-101');
//Obtenemos el nombre del servidor actual
$server=gethostname();
// change the following paths if necessary
$yii='../../../yii/framework/yii.php';
switch ($server)
{
	case 'SERVER_NAME_PROD':
		$config=dirname(__FILE__).'/protected/config/console.php';
		defined('YII_DEBUG') or define('YII_DEBUG',false);
		defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',0);
		break;
	default:
		$config=dirname(__FILE__).'/protected/config/console_dev.php';
		defined('YII_DEBUG') or define('YII_DEBUG',true);
		defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
		break;
}

require_once($yii);
Yii::createConsoleApplication($config)->run();