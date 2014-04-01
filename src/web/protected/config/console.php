<?php
//Obtenemos el nombre del servidor
$server=gethostname();
if($server==SERVER_NAME_PROD)
{
	$server=dirname(__FILE__);
	$nuevo=explode(DIRECTORY_SEPARATOR,$server);
	$num=count($nuevo);
	if($nuevo[$num-3]==DIRECTORY_NAME_PRE_PROD)
	{
		$server_db='localhost';
        $sine_db='sori';
        $user_db='postgres';
        $pass_db='Nsusfd8263';
	}
	else
	{
		$server_db='localhost';
        $sine_db='dev_sori';
        $user_db='postgres';
        $pass_db='Nsusfd8263';
	}
}
else
{
	$server_db='localhost';
    $sine_db='sori';
    $user_db='postgres';
    $pass_db='123';
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
            'connectionString'=>'pgsql:host='.$server_db.';port=5432;dbname='.$sine_db,
			'emulatePrepare'=>true,
			'username'=>$user_db,
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