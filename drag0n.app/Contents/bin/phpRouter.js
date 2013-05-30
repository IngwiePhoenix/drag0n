var fs = require('fs');
var path = require('path');

var phpExe = "php";
var defaultpage = 'index.php';
var phpRouter = function router(request, response, next){
	if (request.method === 'get') {
		console.log("request",request.pathname);
		var url = request.pathname === '/' ? '/'+defaultpage : request.pathname;
		var mimetype = mime.lookup(url);
		if (path.extname(url) == '.php') {
			mimetype = "text/html";
			var cmd = phpExe+' "'+html+url.substring(1)+'"';
			console.log("running: "+cmd);
			exec(cmd,function(error,stdout,sterr) {
				if (error || sterr) {
					response.send(500,'text/plain',new Buffer("500: Internal Server Error\n"+(error||sterr), "utf-8"));
				}
				response.send(200,mimetype,stdout);
			});
		} else {
		//alternative is to call next() and let another router handle normal files.
		console.log('serving: '+html+url.substring(1));
		fs.readFile(html+url.substring(1),function(err,buffer) {
			if (err) {
				response.send(500,'text/plain',new Buffer("500: Internal Server Error\n"+err, "utf-8"));
			} else {
				response.send(200,mimetype,buffer);
			}
		});
		}
	} else {
		next();
	}
};

module.exports = phpRouter;