
<?php
// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// 
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
Yii::setPathOfAlias('bootstrap', dirname(__FILE__) . '/../extensions/bootstrap');
return array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'SINE',
    'language'=>'es',
    // preloading 'log' component
    'theme' => 'metroui',
    'preload'=>array('log','bootstrap'),
    // autoloading model and component classes
    'import'=>array(
        'application.models.*',
        'application.components.*',
        'application.components.reportes.*',
        'application.components.phpexcel.*',
    ),
    'modules'=>array(
        // uncomment the following to enable the Gii tool
        'gii'=>array(
            'class'=>'system.gii.GiiModule',
			'password'=>'123',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array(
                '127.0.0.1',
                '::1'
                ),
            'generatorPaths'=>array(
                'bootstrap.gii',
                ),
            ),
        ),
    // application components
    'components'=>array(
        'reportes'=>array(
            'class'=>"application.components.reportes",
        ),
        'mail'=>array(
            'class'=>"application.components.EnviarEmail",
        ),
        'format'=>array(
            'class'=>"application.components.Formatter",
        ),
        'bootstrap'=>array(
            'class'=>'application.extensions.bootstrap.components.Bootstrap', // assuming you extracted bootstrap under extensions
        ),
        'user'=>array(
            // enable cookie-based authentication
            'allowAutoLogin'=>true,
        ),
        'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
        'db'=>array(
              'connectionString'=>'pgsql:host=67.215.160.89;port=5432;dbname=sori',
//            'connectionString'=>'pgsql:host=172.16.17.190;port=5432;dbname=test_sori',
			'emulatePrepare'=>true,
			'username'=>'postgres',
//            'password'=>'123
            'password'=>'Nsusfd8263',
            'charset'=>'utf8',
            ),
        'errorHandler'=>array(
            // use 'site/error' action to display errors
            'errorAction'=>'site/error',
            ),
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                array(
                    'class'=>'CFileLogRoute',
                    'levels'=>'error, warning',
                    ),
                // uncomment the following to show log messages on web pages
                /*
                array(
                    'class'=>'CWebLogRoute',
                    ),
                */
                ),
            ),
        ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params'=>array(
        // this is used in contact page
        'adminEmail'=>'manuel@newlifeve.com',
        ),
    );
