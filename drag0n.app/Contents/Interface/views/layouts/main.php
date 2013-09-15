<!DOCTYPE html>
<html>
	<head>
		<title><?=CHtml::encode($this->pageTitle)?></title>
		<link rel="stylesheet" type="text/css" href="<?=Yii::app()->theme->baseUrl?>/css/NewDrag0n.css" />
		<?php Yii::app()->clientScript->registerMetaTag('text/html; charset=utf-8',null, 'Content-type'); ?>
		<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl."/js/classie.js", CClientScript::POS_HEAD); ?>
		<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl."/js/modernizr.custom.js", CClientScript::POS_HEAD); ?>
		<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl."/js/jquery.terminal-0.7.6.js", CClientScript::POS_HEAD); ?>
		<?php Yii::app()->clientScript->registerCoreScript("jquery"); ?>
		<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl."/js/backend.js", CClientScript::POS_HEAD); ?>
	</head>
	<body class="cbp-spmenu-push" oncontextmenu="return false;">
		<!-- left -->
		<nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-left" id="cbp-spmenu-s1">
			<h3>Search</h3>
			<input type="text" id="searchbox">
			<div id="searchResults"></div>
		</nav>

		<!-- right -->
		<nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-right" id="cbp-spmenu-s2">
			<h3>Information</h3>
			<span><?=Yii::app()->params['version']?></span>
			<hr>
			<h3>Resources</h3>
			<ul>
				<li>drag0n Installer Default Resource (DIDR)</li>
				<li>Example Resource</li>
			</ul>
		</nav>
		
		<!-- top -->
		<nav class="cbp-spmenu cbp-spmenu-horizontal cbp-spmenu-top" id="cbp-spmenu-s3">
			<a href="#" onclick="suckIt()">
				<img src="<?=Yii::app()->theme->baseUrl?>/icons/rss.png"><span>News</span>
			</a>
			<a href="<?=CHtml::normalizeUrl(array("site/install"))?>" class="AJAX">
				<img src="<?=Yii::app()->theme->baseUrl?>/icons/recent2.png"><span>Packages</span>
			</a>
			<a href="<?=CHtml::normalizeUrl(array("site/index"))?>" class="AJAX">
				<img src="<?=Yii::app()->theme->baseUrl?>/icons/green.png"><span>Home</span>
			</a>
			<a href="#" onclick="document.location.reload(true)">
				<img src="<?=Yii::app()->theme->baseUrl?>/icons/tools.png"><span>Settings</span>
			</a>
			<a onclick="process.kill(process.pid)">
				<img src="<?=Yii::app()->theme->baseUrl?>/icons/power.png"><span>Exit</span>
			</a>
		</nav>

		<!-- bottom -->
		<nav class="cbp-spmenu cbp-spmenu-horizontal cbp-spmenu-bottom" id="console"></nav>

		<div id="container">
			<div id="selectBar">
				<span id="sb1"><img src="<?=Yii::app()->theme->baseUrl?>/icons/search.png"></span>
				<span id="sb2"><img src="<?=Yii::app()->theme->baseUrl?>/icons/up.gif"></span>
				<span id="sb3"><img src="<?=Yii::app()->theme->baseUrl?>/icons/favs2.png"></span>
			</div>
			<div id="main"><?=$content?></div>
		</div>
		
		<div id="activity">
			<img src="<?=Yii::app()->theme->baseUrl?>/icons/process3.gif" id="progIndic" style="display:none; ">
			<div id="progInfo">No running activity</div>
			<img src="<?=Yii::app()->theme->baseUrl?>/icons/console.png" id="showConsole">
		</div>
	</body>
</html>