<?php class d0Command extends CConsoleCommand {

	# Vars
    public $verbose=false;
    public $dry=false;
	public $yes=false;
    public $use;
    public $with;
    
    public function init() {
    	parent::init();
    	
    	# Set up drag0n
    	if($this->verbose) d0(d0::QUIET);
    			      else d0();
    }
    
	public function actionIndex() {
		echo implode("\n", [
			"drag0n Installer CLI 0.1",
			"",
			"Usage: d0 [options] [command] [sub-command] <arg, arg, arg, ...>",
			"",
			"Available commands with possible sub-commands (in brackets) and parameter (in greater-/smaller signs):",
			"    install   [ file | url | id ] <installable>",
			"                  - Install a package from file, url, or from an ID registered in the DIDR",
			"    uninstall <id>",
			"                  - Uninstall specified package by ID.",
			"    resource  [ add | remove | list ] <ID or URL>",
			"                  - Add, remove or show a resource. Adding a resource can be a URL or ID - removal only uses ID",
			"    pkg       [ search | download [--out=DIR]] <id>",
			"                  - Search or download a package by id. Downloads are saved to current location unless --out is specified",
			"    url       <url>",
			"                  - Do what the URL tells. Vaild URL's must start with d0:",
			"    version       - Version",
			"    more          - Display the man page",
			"",
			"Options:",
			"    --verbose     - Be verbose",
			"    --yes         - Always reply with yes to questions",
			"    --dry         - Dry run, don't do anything",
			"    --use         - Use a different Installer than drag0n (ex. --use=apt or --use=npm)",
			"    --with        - Use a different Package module than the default d0p (ex --with=deb or --with=tar.gz)",
			"",
			"Example: d0 install url http://www.example.com/mypack.d0p",
			"Supported protocols for URL's are: http, https, ftp, sftp(ssh)",
			"You can supply a file as an URL too, like: zip://path/to/file.d0p.zip",
			"Supported protocols: zip, phar, ftp, ssh"
		])."\n";
    }
    
    public function actionVersion() {
    	echo "drag0n/0.1-deskshell-alpha; php/".phpversion()."; deskshell/0.9\n";
    }
    
}