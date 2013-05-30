<?php

// $_GET is not available - appJS runs php as CLI. now, lets parse get oruself.
$_GET=array();
parse_str($_ENV['QUERY_STRING'], $_GET);

/*
	Comes from:
	appleScript: thatURL
	nodeJS: process.argv[2]
	
	Represents the drgn:// scheme
*/
define("_DRGN",$_SERVER['argv'][2]);

// change the following paths if necessary
$yii=dirname(__FILE__).'/Frameworks/yii.framework/Resources/yii.php';
$config=dirname(__FILE__).'/Application/config/main.php';

defined("APPJS_ROOT") or define("APPJS_ROOT",dirname(__FILE__));
defined("APPJS_BASEDIR") or define("APPJS_BASEDIR","http://appjs/");

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
Yii::createWebApplication($config)->run();
