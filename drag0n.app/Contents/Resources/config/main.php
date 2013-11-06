<?php 
$_vYii = Yii::getVersion();
$me = dirname(__FILE__)."/../..";

// defines
define('D0_ROOT',$me);

// Aliases
Yii::setPathOfAlias("Library",D0_ROOT."/Library");
Yii::setPathOfAlias("Core",D0_ROOT."/Core");

// includes
include_once Yii::getPathOfAlias('Core').'/d0.php';

return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'drag0n Installer',

	// preloading 'log' component
	'preload'=>array('log', 'd0'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.components.Spyc.*',
		'Core.*',
		'Core.Interfaces.*',
		'Core.OS.*',
		'Core.Package.*',
		'Core.Installer.*',
		'Core.Platform.*'
	),

	'modules'=>array(),

	// application components
	'components'=>array(
		'errorHandler'=>array( 'errorAction'=>'site/error' ),
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
		#'version'=>'drag0n Installer: '.Drag0nInstaller::version()
		#		  .'<br>drag0n GUI: '.$_ENV['D0G_VERSION'].'<br>'
		#		  .'Bin: node.js/'.$_ENV['NJS_VERSION'].", php/".phpversion()."<br/>"
		#		  .'Mod: appJS/'.$_ENV['AJS_VERSION'].', Yii/'.$_vYii."<br/>"
		#		  ."Ext: pthreads/".(new ReflectionExtension("pthreads"))->getVersion(),
		'version'=>'Needs fixing',
	),
);