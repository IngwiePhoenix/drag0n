<?php #print_r($_SERVER);
	$apps = SpycObject(Yii::app()->Apple->getAppsString());
	echo "<pre>";
	print_r($apps->Applications);
	echo "</pre>";