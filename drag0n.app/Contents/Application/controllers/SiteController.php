<?php class SiteController extends Controller {

	public function actionError() {
		if($error=Yii::app()->errorHandler->error) {
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}
	
	public function actionIndex() {
		$this->render('index');
	}
	
	public function actionInstall($id="d0.test-package") {
		$plist = new plistParser;
		$info = $plist->parseFile(APPJS_ROOT."/".$id.".d0i")->toDrag0n();
		$this->renderPartial("pkg",array("model"=>$info));
	}

	public function actionList() {
		$this->renderPartial("list");
	}


}