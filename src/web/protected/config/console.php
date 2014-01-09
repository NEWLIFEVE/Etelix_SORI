<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Consola SORI',

	// preloading 'log' component
	'preload'=>array('log'),
	'import'=>array(
        'application.models.*',
        'application.components.*',
        ),

	// application components
	'components'=>array(
		'transfer'=>array(
            'class'=>"application.components.Transfer",
        ),
		// uncomment the following to use a MySQL database
		'db'=>array(
			'connectionString'=>'pgsql:host=172.16.17.190;port=5432;dbname=test_sori',
            'emulatePrepare'=>true,
            'username'=>'postgres',
            'password'=>'123',
            'charset'=>'utf8',
		),
		'cloud'=>array(
			'class'=>'CDbConnection',
			'connectionString'=>'pgsql:host=67.215.160.89;port=5432;dbname=sori',
			'username'=>'postgres',
			'password'=>'Nsusfd8263',
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