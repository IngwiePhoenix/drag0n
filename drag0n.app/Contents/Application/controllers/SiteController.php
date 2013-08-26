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
		if($_GET['ajax']) $this->renderPartial("index");
		else $this->render('index');
	}
	
	public function actionInstall($id="d0.test-package") {
		$info = SpycObject(APPJS_ROOT."/examples/".$id.".d0i");
		$this->renderPartial("pkg",array("model"=>$info));
	}

	public function actionList() {
		$this->renderPartial("list");
	}


}