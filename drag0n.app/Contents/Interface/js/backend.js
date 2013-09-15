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
				console.log = function(who, msg) { console.insert("status", "{"+who+"} "+msg); }
				console.debug = function(who, msg) { console.insert("debug", "{"+who+"} "+msg); }
				console.error = function(who, msg) { console.insert("error", "{"+who+"} "+msg); }
				console.success = function(who, msg) { console.insert("success", "{"+who+"} "+msg); }
				console.cmd = function(msg) { console.insert("cmd", msg); }
				listeners(); 
			});
			function run(prog, args) { return runCmd(prog, args, self); }
