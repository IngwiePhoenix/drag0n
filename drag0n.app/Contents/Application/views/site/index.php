<?php $this->pageTitle=Yii::app()->name; ?>
<script>
	function sendMail(to) {
		return exec("open mailto:"+to);
	}
</script>
<img src="<?=Yii::app()->theme->baseUrl?>/icons/green.png" class="centerpic">
<div class="row large" style="text-align:left;">
	Welcome to drag0n Installer - the yet only out-of-the-box solution for installing all sorts of custom code, if from source of direct binaries.<br>
	<br>
	Explore the features you see here and have a lot of fun with them. Further updates will include better and new options.
	<hr>
	<pre><?php print_r(SpycObject(Yii::app()->Apple->getAppsString())); ?></pre>
</div>