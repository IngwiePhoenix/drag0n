<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?=CHtml::encode($this->pageTitle)?></title>
		<link rel="stylesheet" type="text/css" href="<?=Yii::app()->theme->baseUrl?>/css/drag0n.css" />
		<?php Yii::app()->clientScript->registerMetaTag('text/html; charset=utf-8',null, 'Content-type'); ?>
		<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl."/js/bar.js",CClientScript::POS_HEAD); ?>
		<?php Yii::app()->clientScript->registerCoreScript("jquery"); ?>
		<script type="text/javascript">
			function log(msg) {
				window.console.log(msg);
				window.terminal.log(msg);
			}
			jQuery(function($) {
				jQuery("#draggable").mousedown(function(){
					window.frame.drag();
				});
				$("#title").html($("title").html());
				$("#AJAX").click(function(e){
					e.preventDefault();
					href=$(this).attr("href");
					$.get(href,function(d){
						$("#container").html(d);
					});
				});
			});
		</script>
	</head>
	<body>
		<div id="OS">
			<a href="#" onclick="process.kill(process.pid)"><img src="<?=Yii::app()->theme->baseUrl?>/images/deleteButton@2x.png" class="button"></a>
			<!--<a href="#" class="button mini" onclick="frame.minimize()">_</a>-->
			<!--<a href="#" class="button max" onclick="frame.maximize()">[]</a>-->
			<!--<a href="#" class="button fs" onclick="frame.fullscreen()"><></a>-->
			<a href="#" style="color:red;" onclick="log('reloading');location.reload()">Refresh</a>
		</div>
		<div id="TITLE">
			<div id="draggable">
				<div id="title"></div>
			</div>
			<div id="bar">
				<ul>
					<li><a href="#" onclick="log('o.o');">Test</a></li>
					<li><a href="<?=CHtml::normalizeUrl(array("site/install"))?>" id="AJAX">pkg page</a></li>
				</ul>
			</div>
		</div>
		<div id="WINDOW">
			<div id="top">
			</div>

			<div id="container"><!-- layout content div -->
  		   		<?php echo $content; ?>
  			</div>
		</div>
	</body>
</html>
