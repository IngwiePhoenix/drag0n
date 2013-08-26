// The information:
var Drag0nGUI_Version = "0.2b";
console.log(process.env);
// The GUI code
var
	cwd 		=	process.cwd()+"/Library/node_modules",
	spawn 		= 	require('child_process').spawn,
	app 		= 	module.exports = require('appjs'),
	path 		= 	require('path'),
	fs 			= 	require('fs'),
	mime 		= 	require('mime'),
	MAIN 		= 	path.resolve(__dirname,'../')+"/",
	defaultpage = 	'MacOS/d0GUI.php';

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
					exe = "php";
					break;
				case ".php": 
					mimetype = "text/html"; 
					exe = "php";
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
			
			env = process.env;
			if(typeof env._DRGN == "undefined") env._DRGN=null;
			env = {
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
				'QUERY_STRING':qstring,
				"D0G_VERSION":Drag0nGUI_Version,
			}

			$PHP = spawn(exe,[MAIN+url.substring(1), _arg],{'env':env});
			var allData = "";
			var code = 200;
			$PHP.stdout.on('data',function(data) {
				allData += data;
			});
			$PHP.stderr.on("data",function(data) {
				console.log("[Error]: "+data);
				allData += data;
				code = 500;
			});
			$PHP.stdout.on('end',function() {
				response.send(code,mimetype,allData);
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
		executer = process.cwd()+"/System/bin/executer.sh";
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
  resizable: false,
  autoResize: false,
  alpha:true,
  showChrome:false,
});

window.on('create', function(){
	console.log("[Window] \"create\" event called");
  	this.frame.center();
  	window.require = require;
  	window.process = process;
  	window.module = module;
  	window.terminal = console;
  	window.runCmd = runCmd;
  	window.frame = this.frame;
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
  	window.frame.move(window.frame.left, -window.frame.top, window.frame.width, window.frame.height);
  	this.frame.show();
  	this.frame.focus();

  	// force fullscreen
  	this.frame.fullscreen();
  	var interval = setInterval(function(){ 
  		//window.console.log(window.frame.state);
  		//window.frame.fullscreen(); window.frame.restore();
  		//window.frame.restore();
  		//window.frame.fullscreen();
  	},100);
  
  	// Open Developer Inspector
  	this.frame.openDevTools();
});