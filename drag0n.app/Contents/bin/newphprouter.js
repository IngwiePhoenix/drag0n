/* New router, in use soon.
app.router.use(require('appjs-cgi').router({
	/* cgi environment variables, passed to cgi, add and customise as you want. */
	env:{
		'SERVER_SOFTWARE':"AppJS/0.20"
		,'SERVER_PROTOCOL':"HTTP/1.1"
		,'GATEWAY_INTERFACE':"CGI/1.1"
		,'SERVER_NAME':"appjs"
		,'SERVER_PORT':80
		,'DOCUMENT_ROOT':html
	},directoryIndex:'index.php'         //if you navigate to a directory this file will be tried.
	,ext:'.php'                          //if request matches extension then cgi router will be triggered
	,bin:'/opt/local/bin/php'          //path to cgi executable
	,debug:true                          //if true outputs request & cgi call to console.
	,sterr:function(err){}               //function to handle any errors from cgi script, e.g. write to a server log.
	,envFn:function(env) {               //triggered for each request before script execution.
		//view env vars for this request and modify them...	
		return env;
	}
})); */