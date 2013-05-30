<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController {
	public $layout='//layouts/column1';
	public $menu=array();
	public $breadcrumbs=array();
	
	public function init() {
		parent::init();
		Yii::app()->getRequest()->setScriptUrl($_ENV['SCRIPT_NAME']);
		
		$dir = scandir(APPJS_ROOT."/tmp");
		foreach($dir as $k=>$file) {
			if($file != "." && $file != "..") unlink(APPJS_ROOT."/tmp/".$file);
		}
	}
}