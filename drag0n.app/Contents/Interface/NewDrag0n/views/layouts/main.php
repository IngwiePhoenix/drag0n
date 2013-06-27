<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?=CHtml::encode($this->pageTitle)?></title>
		<link rel="stylesheet" type="text/css" href="<?=Yii::app()->theme->baseUrl?>/css/style.css" />
		<?php Yii::app()->clientScript->registerMetaTag('text/html; charset=utf-8',null, 'Content-type'); ?>
		<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl."/js/classie.js",CClientScript::POS_HEAD); ?>
		<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl."/js/modernizr.custom.js",CClientScript::POS_HEAD); ?>
		<?php Yii::app()->clientScript->registerCoreScript("jquery"); ?>
		<script type="text/javascript">
			function log(msg) {
				window.console.log(msg);
				window.terminal.log(msg);
			}
			jQuery(function($) {
				console.log("window",window);
				$("#title").html($("title").html());
				$("#AJAX").click(function(e){
					e.preventDefault();
					href=$(this).attr("href");
					$.get(href,function(d){
						$("#main").html(d);
						removeAll("x");
					});
				});
			});
		</script>
	</head>
	<body class="cbp-spmenu-push">
		<!-- left -->
		<nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-left" id="cbp-spmenu-s1">
			<h3>Search</h3>
			<input type="text" id="searchbox">
			<div id="searchResults"></div>
		</nav>

		<!-- right -->
		<nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-right" id="cbp-spmenu-s2">
			<h3>Resources</h3>
			<ul>
				<li>drag0n Installer Default Resource (DIDR)</li>
				<li>Example Resource</li>
			</ul>
		</nav>
		
		<!-- top -->
		<nav class="cbp-spmenu cbp-spmenu-horizontal cbp-spmenu-top" id="cbp-spmenu-s3">
			<a href="#">
				<img src="<?=Yii::app()->theme->baseUrl?>/icons/rss.png"><span>News</span>
			</a>
			<a href="<?=CHtml::normalizeUrl(array("site/install"))?>" id="AJAX">
				<img src="<?=Yii::app()->theme->baseUrl?>/icons/recent2.png"><span href="<?=CHtml::normalizeUrl(array("site/install"))?>" id="AJAX">Packages</span>
			</a>
			<a href="<?=CHtml::normalizeUrl(array("site/index"))?>" id="AJAX">
				<img src="<?=Yii::app()->theme->baseUrl?>/icons/green.png"><span>Home</span>
			</a>
			<a href="#">
				<img src="<?=Yii::app()->theme->baseUrl?>/icons/tools.png"><span>Settings</span>
			</a>
			<a onclick="process.kill(process.pid)">
				<img src="<?=Yii::app()->theme->baseUrl?>/icons/power.png"><span>Exit</span>
			</a>
		</nav>

		<!-- bottom -->
		<nav class="cbp-spmenu cbp-spmenu-horizontal cbp-spmenu-bottom" id="cbp-spmenu-s4">
			<h3>BOTTOM</h3>
			<a href="#">Celery seakale</a>
		</nav>

		<div id="container">
			<div id="selectBar">
				<span id="sb1"><img src="<?=Yii::app()->theme->baseUrl?>/icons/computer.png"></span>
				<span id="sb2"><img src="<?=Yii::app()->theme->baseUrl?>/icons/up.gif"></span>
				<span id="sb3"><img src="<?=Yii::app()->theme->baseUrl?>/icons/favs2.png"></span>
			</div>
			<div id="main">
				<?=$content?>
			</div>
		</div>

		<!-- Classie - class helper functions by @desandro https://github.com/desandro/classie -->
		<script>
			var 
				menuLeft = document.getElementById( 'cbp-spmenu-s1' ),
				menuRight = document.getElementById( 'cbp-spmenu-s2' ),
				menuTop = document.getElementById( 'cbp-spmenu-s3' ),
				menuBottom = document.getElementById( 'cbp-spmenu-s4' ),
				
				showLeftPush = document.getElementById( 'sb1' ),
				showTopPush = document.getElementById( 'sb2' ),
				showRightPush = document.getElementById( 'sb3' ),
				showBottomPush = document.getElementById( 'showBottomPush' ),
				
				body = document.body;
			
			function removeAll(pos) {
				if(pos != "left") {
					classie.remove( body, 'cbp-spmenu-push-toright' );
					classie.remove( menuLeft, 'cbp-spmenu-open' );
				}
				if(pos != "right") {
					classie.remove( body, 'cbp-spmenu-push-toleft' );
					classie.remove( menuRight, 'cbp-spmenu-open' );
				}
				if(pos != "bottom") {
					classie.remove( body, 'cbp-spmenu-push-totop' );
					classie.remove( menuBottom, 'cbp-spmenu-open' );
				}
				if(pos != "top") {
					classie.remove( body, 'cbp-spmenu-push-tobottom' );
					classie.remove( menuTop, 'cbp-spmenu-open' );
				}
			}

			showLeftPush.onclick = function() {
				removeAll("left");
				classie.toggle( this, 'active' );
				classie.toggle( body, 'cbp-spmenu-push-toright' );
				classie.toggle( menuLeft, 'cbp-spmenu-open' );
			};
			showRightPush.onclick = function() {
				removeAll("right");
				classie.toggle( this, 'active' );
				classie.toggle( body, 'cbp-spmenu-push-toleft' );
				classie.toggle( menuRight, 'cbp-spmenu-open' );
			};
			showTopPush.onclick = function() {
				removeAll("top");
				classie.toggle( this, 'active' );
				classie.toggle( body, 'cbp-spmenu-push-tobottom' );
				classie.toggle( menuTop, 'cbp-spmenu-open' );
			};
			function showDev() {
				removeAll("bottom");
				classie.toggle( body, 'cbp-spmenu-push-totop' );
				classie.toggle( menuBottom, 'cbp-spmenu-open' );
			}
		</script>
	</body>
</html>
