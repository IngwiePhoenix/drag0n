var spawn = require('child_process').spawn;
module.exports.xmlReader = function(xmld) {
		var proc = spawn(path.resolve(__dirname,"../php-bin/xmlReader.php"), ["--xml":xmld]);
		var res;
		proc.stdout.on("data",function(data){
			res += data;
		});
		proc.on("exit",function(code,sig){
			return JSON.parse(res);
		});
	}
}
