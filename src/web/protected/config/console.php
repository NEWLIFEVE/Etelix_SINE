<?php
// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'SINE-consola',
	'timeZone'=>'America/Caracas',
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