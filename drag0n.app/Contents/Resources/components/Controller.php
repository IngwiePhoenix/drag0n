<?php class Controller extends CController {
	public $layout='//layouts/column1';
	public $menu=array();
	public $breadcrumbs=array();
	
	public function init() {
		parent::init();
		
		Yii::app()->theme='Interface';
		
		$dir = glob(D0_ROOT."/System/tmp/*");
		foreach($dir as $k=>$file) {
			if($file != "." && $file != "..") unlink($file);
		}
		include_once "d0.php";
		d0(d0::QUIET);
	}
} ?>