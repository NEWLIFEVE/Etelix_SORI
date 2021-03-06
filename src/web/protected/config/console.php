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