<?php global $_vYii, $_vNodeJS, $_vAppJS;
$me = dirname(__FILE__)."/../..";
include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'../components/d0.php';

// Aliases
Yii::setPathOfAlias("Library",APPJS_ROOT."/Library");

return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'drag0n Installer',
	'theme'=>'Interface',

	// preloading 'log' component
	'preload'=>array('log','Apple'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'Library.macos.Apple',
	),

	'modules'=>array(),

	// application components
	'components'=>array(
		'errorHandler'=>array( 'errorAction'=>'site/error', ),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),
		'assetManager' =>array(
			'basePath'=>"$me/System/etc/interface.d",
			'baseUrl'=>'/System/etc/interface.d'
		),
		'themeManager' =>array(
			'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'../../',
			'baseUrl'=>'/'
		),
		'Apple'=>array( "class"=>"Apple" ),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'ingwie2000@googlemail.com',
		'version'=>'drag0n Installer: '.Drag0nInstaller::version()
				  .'<br>drag0n GUI: '.$_ENV['D0G_VERSION'].'<br>'
				  .'<i>appJS:'.$_vAppJS.', node.js:'.$_vNodeJS.', Yii:'.$_vYii.'</i>',
		'basePath'=>APPJS_BASEDIR,
	),
);