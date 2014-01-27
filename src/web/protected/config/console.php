<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'My Console Application',

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
		'db'=>array(
            'connectionString'=>'pgsql:host=172.16.17.190;port=5432;dbname=sori',
			'emulatePrepare'=>true,
			'username'=>'postgres',
            'password'=>'123',
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