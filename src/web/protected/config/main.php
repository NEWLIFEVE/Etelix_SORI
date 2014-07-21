<?php
// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'S O R I',
    'language'=>'es',
    'timeZone'=>'America/Caracas',
    'charset'=>'utf-8',
    'theme'=>'designa',
    'preload'=>array(
        'log'
        ),
    'import'=>array(
        'application.models.*',
        'application.components.*',
        ),
    'modules'=>array(),
    'components'=>array(
        'user'=>array(
            'class'=>'WebUser',
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
        'errorHandler'=>array(
            'errorAction'=>'site/error',
            ),
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                array(
                    'class'=>'CFileLogRoute',
                    'levels'=>'error, warning',
                    ),
                // array(
                //     'class'=>'CWebLogRoute',
                //     ),
                ),
            ),
        'enviarEmail'=>array(
            'class'=>'application.components.EnviarEmail',
            ),
        ),
    'params'=>array(
        'adminEmail'=>'manuelz@sacet.biz',
        ),
    );
