<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>drag0n installer</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
        <link rel="stylesheet" type="text/css" media="screen" href="css/layout.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="css/style.css" />
        <script src="js/jquery.js" type="text/javascript"></script>
        <script src="js/system.js" type="text/javascript"></script>
        <script type="text/javascript">
        	$(function(){
        		$('#drag').on('mousedown', function(event){window.frame.drag();} );
        	});
        </script>
    </head>

    <body>
        <div id="wrap">
            <div id="sidebar">
                <div id="drag"><div class="header">
                    <p class="title">Menu</p>
                </div></div>
                <div class="content">
                    <ul class="nav">
                        <li><a href="?"><span class="ico msg d0"></span>Drag0n</a></li>
                        <li><a href="?"><span class="ico msg d0"></span>New</a></li>
                        <li><a href="?"><span class="ico msg d0"></span>Updates</a></li>
                        <li><a href="?"><span class="ico msg d0"></span>Resources</a></li>
                        <li><a href="?"><span class="ico msg d0"></span>Installed</a></li>
                        <li><a href="?"><span class="ico msg d0"></span>Help</a></li>
                        <li><a href="?"><span class="ico msg d0"></span>Settings</a></li>
                    </ul>
                    <p id="sideText">
                    	No process running.
                    </p>
                </div>
            </div>

            <div id="main"> 
                <div class="header">
                    <a class="left" href="?">About</a>
                    <h1 class="title">Main</h1>
                    <a class="right" href="?">Action!</a>
                </div>
                <div class="content" id="MAINCONTENT">
                	<?=$content?>
                </div>
            </div>
        </div>
    </body>
</html>