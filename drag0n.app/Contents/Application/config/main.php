<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Drag0n Installer',
	'theme'=>'NewDrag0n',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		#'webroot.bin.*',
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
			'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'../../Data',
			'baseUrl'=>'/Data/'
		),
		'themeManager' =>array(
			'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'../../Interface',
			'baseUrl'=>'/Interface/'
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'ingwie2000@googlemail.com',
		'version'=>'0.1b (appJS:0.20, node.js:0.9)',
		'tBasePath'=>APPJS_BASEDIR."themes/drag0n",
		'basePath'=>APPJS_BASEDIR,
	),
);