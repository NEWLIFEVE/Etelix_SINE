<?php
$server=gethostname();
switch ($server)
{
	case SERVER_NAME_PROD:
		$server_db='localhost';
        $sori_db='sori';
        $pass_db='Nsusfd8263';
		break;
	default:
		$server_db='localhost';
        $sori_db='sori';
        $pass_db='123';
		break;
}
// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'SINE-consola',

	// preloading 'log' component
	'preload'=>array('log'),
	'import'=>array(
		'application.models.*',
		'application.components.*'
		),
	// application components
	'components'=>array(
		'provisions'=>array(
            'class'=>"application.components.Provisions",
        ),
        'mail'=>array(
            'class'=>"application.components.EnviarEmail",
        ),		
		'db'=>array(
            'connectionString'=>'pgsql:host='.$server_db.';port=5432;dbname='$sori_db,
			'emulatePrepare'=>true,
			'username'=>'postgres',
            'password'=>$pass_db,
			'charset'=>'utf8',
            ),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),
	),
);