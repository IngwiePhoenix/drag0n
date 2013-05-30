var path = require('path');
var exec = require('child_process').exec;
var spawn = require('child_process').spawn;
var window;
module.exports = {
	d0: path.resolve(__dirname, '../bin/d0')+" --html ",
	setWindow: function(win) { window = win },
	sout: null,
	run: function(arg,dest) {
		window.console.log("[d0.run] "+this.d0+arg);
		var self = this;
		exec(this.d0+arg, function(error, stdout, stderr) {
			if(error==null) {
				if(stdout != null || stdout != "") { self.sout = stdout; dest.html(stdout); }
				else { window.console.log("stdout is empty!"); }
			} else {
				window.console.log("error: "+error);
			}
		});
	},
	runStream: function(arg,dest) {
		var proc = spawn(d0,arg);
		proc.stdout.on("data",function(d){
			dest.html(dest.html()+data);
		});
		proc.on("exit",function(code,sig){
			window.console.log("[d0.runStream] ERROR: "+code);
		});
	}
}