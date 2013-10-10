<?php class Controller extends CController {
	public $layout='//layouts/column1';
	public $menu=array();
	public $breadcrumbs=array();
	
	public function init() {
		parent::init();
		#Yii::app()->getRequest()->setScriptUrl($_ENV['SCRIPT_NAME']);
		
		$dir = scandir(APPJS_ROOT."/System/tmp");
		foreach($dir as $k=>$file) {
			if($file != "." && $file != "..") unlink(APPJS_ROOT."/System/tmp/".$file);
		}
		
		// Init the d0 singleton correctly:
		include_once "d0.php";
		d0(Yii::app()->Apple->workDir);
	}
} ?>