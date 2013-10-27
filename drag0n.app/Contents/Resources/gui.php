<?php
	// Prepair our workspace
	$dir = realpath( dirname(__FILE__)."/.." );
	include_once($dir."/Library/macos/Apple.php");

	// prepair the Data folder
	@mkdir($dir."/System/etc/interface.d");
	@mkdir($dir."/System/tmp");
	@mkdir($dir."/Application/runtime");

	// change the following paths if necessary
	$yii=$dir.'/Frameworks/yii.framework/Resources/yii.php';
	$config=$dir.'/Resources/config/main.php';

	// We NEED Yii.
	require_once($yii);

	// remove the following lines when in production mode
	defined('YII_DEBUG') or define('YII_DEBUG',true);
	// specify how many levels of call stack should be shown in each log message
	defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

	Yii::createWebApplication($config)->run();
	