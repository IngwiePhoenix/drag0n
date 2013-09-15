<!DOCTYPE html>
<html>
	<head>
		<title><?=CHtml::encode($this->pageTitle)?></title>
		<link rel="stylesheet" type="text/css" href="<?=Yii::app()->theme->baseUrl?>/css/NewDrag0n.css" />
		<?php Yii::app()->clientScript->registerMetaTag('text/html; charset=utf-8',null, 'Content-type'); ?>
		<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl."/js/classie.js",CClientScript::POS_HEAD); ?>
		<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl."/js/modernizr.custom.js",CClientScript::POS_HEAD); ?>
		<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl."/js/jquery.terminal-0.7.6.js",CClientScript::POS_HEAD); ?>
		<?php Yii::app()->clientScript->registerCoreScript("jquery"); ?>
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

		<script>
			function suckIt() {
				file = "php";
				args = ['-r', '"echo json_encode(array(\'type\'=>\'success\', \'who\'=>\'FooBar\', \'what\'=>\'IT WORKS.\'));"'];
				run(file, args);
			}
			function terminal() {
				$('#console').terminal(function(input, terminal) {
       				terminal.echo("Command: "+input);
    			}, {
        			greetings: 'drag0n console',
        			name: 'd0',
        			prompt: 'drag0n> '
        		});
			}
			function listeners() {
				console.debug("js/listeners","Setting up listeners.");
				$("#main").click(function(e){removeAll("x");});
				$(".AJAX").click(function(e){
					e.preventDefault();
					document.body.style.cursor = "wait";
					href=$(this).attr("href")+"&ajax=true";
					$.get(href,function(d){
						$main = $(d).find("#main");
						if($main && $main.length===1) { $v = $($main[0]).html(); }	else { $v = d; }			
						console.olog($v);
						$("#main").html($v);
						listeners();
						removeAll("x");
						document.body.style.cursor = "default";
					});
				});

				menuLeft = document.getElementById( 'cbp-spmenu-s1' );
				menuRight = document.getElementById( 'cbp-spmenu-s2' );
				menuTop = document.getElementById( 'cbp-spmenu-s3' );
				menuBottom = document.getElementById( 'console' );
				
				showLeftPush = document.getElementById( 'sb1' );
				showTopPush = document.getElementById( 'sb2' );
				showRightPush = document.getElementById( 'sb3' );
				showBottomPush = document.getElementById( 'showConsole' );
				
				body = document.body;
			
				removeAll = function (pos) {
					if(pos != "left") 	{ classie.remove( body, 'cbp-spmenu-push-toright' ); classie.remove( menuLeft, 'cbp-spmenu-open' ); }
					if(pos != "right") 	{ classie.remove( body, 'cbp-spmenu-push-toleft' ); classie.remove( menuRight, 'cbp-spmenu-open' ); }
					if(pos != "bottom") { classie.remove( body, 'cbp-spmenu-push-totop' ); classie.remove( menuBottom, 'cbp-spmenu-open' ); }
					if(pos != "top") 	{ classie.remove( body, 'cbp-spmenu-push-tobottom' );	classie.remove( menuTop, 'cbp-spmenu-open' ); }
				};

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
				showBottomPush.onclick = function() {
					removeAll("bottom");
					classie.toggle( this, 'active' );
					classie.toggle( body, 'cbp-spmenu-push-totop' );
					classie.toggle( menuBottom, 'cbp-spmenu-open' );
				};
			}
			jQuery(function(){
				terminal();
				if (typeof console  != "undefined") {
    				if (typeof console.log != 'undefined')
        				console.olog = console.log;
    				else
        				console.olog = function() {};
        		}

				console.insert = function(cs, message) {
    				console.olog(message);
					$('#console').terminal().echo("[[;;;"+cs+"]"+message+"]");
    				var objDiv = document.getElementById("console");
					objDiv.scrollTop = objDiv.scrollHeight;
				};
				console.log = function(who, msg) { console.insert("status", "["+who+"] "+msg); }
				console.debug = function(who, msg) { console.insert("debug", "["+who+"] "+msg); }
				console.error = function(who, msg) { console.insert("error", "["+who+"] "+msg); }
				console.success = function(who, msg) { console.insert("success", "["+who+"] "+msg); }
				console.cmd = function(msg) { console.insert("cmd", msg); }
				listeners(); 
			});
			function run(prog, args) { return runCmd(prog, args, self); }
		</script>
	</body>
</html>
