<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="stylesheet" type="text/css" media="screen" href="css/d0.css" />
        <script src="js/jquery.js" type="text/javascript"></script>
        <script src="js/system.js" type="text/javascript"></script>
        <script type="text/javascript">
        	$(function(){
        		$('#titlebar').mousedown(function(){window.frame.drag();} );
        		$('#title').html( $('#tDiv').html() );
        		window.frame.center();
        	});
        	window.unload=function(){terminal.log("onunload");}
        </script>
    </head>

    <body>
    	<div id="titlebar">
    		<span id="title"></span>
    	</div>
    	
   		<div id="contentContainer">
			<div id="sidebar">
				<p>Blub blub blub</p>
			</div>    	
			<?=$content?>
			</div>
    	</div>
	</body>
</html>