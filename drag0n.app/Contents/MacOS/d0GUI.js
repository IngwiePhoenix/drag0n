// The information:
var Drag0nGUI_Version = "0.2b";
var NJS_Version = process.version;
var AJS_Version = require(__dirname+"/../System/lib/node/appjs/package.json").version;

// The GUI code
var
	cwd 		=	process.cwd()+"/Library/node_modules",
	spawn 		= 	require('child_process').spawn,
	app 		= 	module.exports = require('appjs'),
	fs			=	require("fs"),
	path 		= 	require('path'),
	fs 			= 	require('fs'),
	mime 		= 	require('mime'),
	MAIN 		= 	path.resolve(__dirname,'../')+"/",
	defaultpage = 	'MacOS/d0GUI.php',
	php_bin		=	path.resolve(__dirname,"../")+"/System/bin/php-cgi";

function in_array(item,arr) {
	for(p=0;p<arr.length;p++) { if(item == arr[p]) return true; }
	return false;
}

var phpRouter = function router(request, response, next){
	if (request.method === 'get' || request.method === 'post') {
		var url = request.pathname === '/' ? '/'+defaultpage : request.pathname;
		var _arg = "";
		if(request.pathname === "/") { _arg = "--appjs"; }
		var mimetype = mime.lookup(url);
		if(in_array(path.extname(url),[".css",".php",".sh",".bash",".py"])) {
			switch(path.extname(url)) {
				case ".css": 
					mimetype = "text/css";
					exe = php_bin;
					break;
				case ".php": 
					mimetype = "text/html"; 
					exe = php_bin;
					break;
				case ".bash":
				case ".sh": 
					mimetype = "text/plain"; 
					exe = "bash";
					break;
				case ".py": 
					mimetype = "text/plain"; 
					exe = "python";
					break;
			}
			
			var qstring="";
			for(var p in request.params){
				qstring += p+"="+request.params[p]+"&";
			}
			
			// Prepairing a fake-environment for php-cgi...yes, I insist in using that :)
			env = {
				// Fake PHP-CGI
				"DOCUMENT_ROOT":MAIN,
				"HTTP_HOST":"appjs",
				"SERVER_NAME":"appjs",
				"SERVER_ADDR":"127.0.0.1",
				"REQUEST_METHOD":request.method,
				"REQUEST_URI":"http://appjs"+url,
				"SCRIPT_URL":url,
				"HTTP_HOST":"appjs",
				"PHP_SELF":url,
				"SCRIPT_NAME":url,
				"REDIRECT_STATUS":0,
				'SCRIPT_FILENAME':MAIN+url.substring(1),
				"QUERY_STRING":qstring,
				
				// Real environment.
				"D0G_VERSION":Drag0nGUI_Version,
				"NJS_VERSION":NJS_Version,
				"AJS_VERSION":AJS_Version,
				
				// We no longer need to patch the userspace - lets just tweak Apple!
				"DYLD_LIBRARY_PATH":MAIN+"/System/usr/lib",				
			}
			if(typeof env._DRGN == "undefined") env._DRGN=null;
			for(var _e in process.env) { env[_e] = process.env[_e]; }
			env.TERM="dumb";
			
			$PHP = spawn(exe, [MAIN+url.substring(1)], {'env':env});
			var allData = "";
			var code = 200;
			$PHP.stdout.on('data',function(data) {
				allData += data;
			});
			$PHP.stderr.on("data",function(data) {
				console.log("ERROR-DATA: "+allData);
				console.log("[Error]: "+data);
				/*fs.appendFile(
					MAIN+"/Logs/PHP.stderr", 
					"[Error]: "+data+"\n", 
					function(e){/* avoid force-quitting the app /}
				);*/
				code = 500;
			});
			$PHP.stdout.on('end',function() {
				/*
					We need to remove 4 lines - which are:
					1: X-Powered-By: php/5.4.10
					2: Content-type: text/html
					3: 
					4: #! ../System/bin/php
					The rest can just be stripped.			
				*/
				var allArray = allData.split("\n");
				allArray.splice(0,4);
				allData = allArray.join("\n");
				/*response.writeHead(code, {
					'Content-Length': allData.byteLength,
  					'Content-Type': mimetype
  				});*/
				response.send(code,mimetype,allData);
	console.log("Response send.");
			});
		} else {
			//alternative is to call next() and let another router handle normal files.
			fs.readFile(MAIN+url.substring(1),function(err,buffer) {
				if (err) {
					response.send(500,'text/plain',new Buffer("500: Internal Server Error\n"+err, "utf-8"));
				} else {
					response.send(200,mimetype,buffer,"utf-8");
				}
			});
		}
	} else { next(); }
}; app.router.use(phpRouter);

function runCmd(file, args, w) {
	var 
		execStr = file+" "+args.join(" "),
		executer = __dirname+"/../System/bin/executer.sh";
	w.console.log("nodejs/runCmd", "$PATH => "+process.env.PATH);
	w.console.log("nodejs/runCmd",execStr);
	process.env.THE_ARGS = execStr;
	var TheCommand = spawn(executer, []);
	TheCommand.stdout.on("data", function(chunk){ 
		try { 
			var data = JSON.parse(chunk.toString());
			w.console[data.type](data.who, data.what);
		} catch(e) { w.console.cmd(chunk.toString()); }
	});
	TheCommand.stderr.on("data", function(chunk){
		try { 
			var data = JSON.parse(chunk.toString());
			w.console[data.type](data.who, data.what);
		} catch(e) { w.console.error(file, chunk.toString()); }
	});
	return TheCommand;
}

var menubar = app.createMenu([{
		label:'&File',
		submenu:[{
			label:'About',
			action:function(){console.log("About...");}
		},{
			label:'Check for Updates',
			action:function(){console.log("Update...");}
		},{
			label:'Send feedback',
			action:function(){console.log("sending email to dev");}
		},{
			label:'Q&uit',
    		action: function(){	window.close();	process.exit(0); }
    	}]
	},{
		label:'Installer',
		submenu:[{
			label:'Install by ID',
			action:function(){console.log("installing by id...");}
		},{
			label:'Update package list',
			action:function(){console.log("updating list");}
		}]
	},{
  		label:'&Window',
  		submenu:[{
    		label:'Show/Hide console',
    		action:function(item) {
      			window.showDev();
      			window.console.log("realwindow",window);
    		}
    	}]
}]);

/*var trayMenu = app.createMenu([{
  label:'Show',
  action:function(){
    window.frame.show();
  },
},{
  label:'Minimize',
  action:function(){
    window.frame.hide();
  }
},{
  label:'Exit',
  action:function(){
    window.close();
  }
}]);

var statusIcon = app.createStatusIcon({
  icon:'./data/content/icons/32.png',
  tooltip:'Drag0n',
  menu:trayMenu
});*/

var window = app.createWindow({
  width  : 1024,
  height : 768,
  top: -1,
  left: -1,
  icons  : __dirname+'/../Interface/icons',
  resizable: true,
  autoResize: false,
  alpha:false,
  showChrome:true,
});

window.on('create', function(){
	console.log("[Window] \"create\" event called");
  	window.frame.setMenuBar(menubar);
});

window.on('ready', function(){
	console.log("[Window] \"ready\" event called");
  	window.require = require;
  	window.process = process;
  	window.module = module;
  	window.terminal = console;
  	window.runCmd = runCmd;
  	window.frame = this.frame;
  	window.__dirname = __dirname;
  	window.frame.move(window.frame.left, -window.frame.top, window.frame.width, window.frame.height);
  	this.frame.show();
  	this.frame.focus();

  	// force fullscreen
  	// this.frame.fullscreen();
  
  	// Open Developer Inspector
  	this.frame.openDevTools();
});