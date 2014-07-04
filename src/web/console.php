<?php
date_default_timezone_set('America/Caracas');
//Definimos nuestro servidor de produccion
define('SERVER_NAME_PROD','s1248-101');
//Definimos el directorio de desarrollo
define('DIRECTORY_NAME_PRE_PROD','dev_sine');
//Obtenemos el nombre del servidor actual
$server=gethostname();
// change the following paths if necessary
$yii=dirname(__FILE__).'/../../../yii/framework/yii.php';

if($server==SERVER_NAME_PROD)
{
	$server=dirname(__FILE__);
	$nuevo=explode(DIRECTORY_SEPARATOR,$server);
	$num=count($nuevo);
	if($nuevo[$num-3]==DIRECTORY_NAME_PRE_PROD)
	{
		defined('YII_DEBUG') or define('YII_DEBUG',true);
		defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
		$config=dirname(__FILE__).'/protected/config/console-pre-prod.php';
	}
	else
	{
		defined('YII_DEBUG') or define('YII_DEBUG',false);
		defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',0);
		$config=dirname(__FILE__).'/protected/config/console-prod.php';
	}
}
else
{
	defined('YII_DEBUG') or define('YII_DEBUG',true);
	defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
	$config=dirname(__FILE__).'/protected/config/console-local.php';
}

require_once($yii);
Yii::createConsoleApplication($config)->run();
