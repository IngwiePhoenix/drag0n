<?php interface Installer {
	
	// ------ CONSTANTS ------
	# File
	const F_ORIG=0x01;
	const F_BACKUP=0x02;
	
	# Script
	const S_SUCCESS=0x11;
	const S_FAILURE=0x12;
	
	# Action
	const A_REBOOT=0xf1;
	const A_RELOGIN=0xf2;
	const A_NOTHING=0xf3;

	# Types
	const T_PKG=0xa1;
	const T_RES=0xa2;
	
	// Constructor.
	// Set up destination and information paths.
	// Note, this is optimal. It's only here as this file is the base for anything else.
	#public function __construct($installPath="/", $infoPath);
		
	// Download given URL content and return an array of [content=>str, http_code=>int]
	public function download($url);
	
	// Downloads multiple things at once - numeric array. Requires pthreads to be installed.
	// $paralell defines how many downloads we may do at once
	public function th_download(array $urls, $paralell=1);
	
	// Install-phase. Use given file and work it out
	// Returns either true or false
	public function install($file);
	
	// Uninstall. Same as above. However, you should uninstall with $this->getInfo($id)!
	public function uninstall($id);
	
	// Update. Triggers update scripts and updates the files from a package and the responsible info-files
	public function update($id);
	
	// If really needed, reinstall the package. Should perform some pre-checks and then trigger install-like behaviors.
	public function reinstall($id);
	
	// handle pre and post scripts. These should be given as filename.
	// Returns an array: [ outcome=>S_SUCCESS or S_FAILURE, action=>A_* ]
	public function script($scriptFile);
	
	/* Get the whole information of a package, by it's ID.
	   This COULD consist of an array like so:
	   [
	   		"pkg"=>[ id, name, etc ]
	   		"backups"=>[ name of backupped files ]
	   		"files"=>[ files that are installed ]
	   		"scripts"=>[ "preinstall"=>..., "postinstall".... ]
	   ]
	   Script files may be delivered as clear-text, will be written to disk and executed that way.
	   Please note, if you're implementing this interface, you may structure your array as you wish. This is the d0 example.
	*/
	public function getInfo($id);
	
	// Get all packages installed, should be read off info file
	public function getInstalled();
	
	// a function like is_null - check if a package is installed. Should be used for dependendency tracking
	public function isInstalled($id);
	
	// validate a resource.
	// $in can be a url OR an idendifier from the DIDR resource database.
	public function validateResource($in);
	
	// Validate package. If you have hashes, you are ADVISED to use this.
	public function validateFile($file, $hash);
	
	// Get information from a remote DB. By default, this is the DIDR database. Other classes implementing that, may change this...just maybe.
	// The DIDR database responds to an ID=Type pair.
	// Type can be one of the T_ constants.
	public function retrieve($id,$type);
	
	// return some information, like the version and such.
	// Use __invoke to return Version.
	public static function version();
	public function __invoke();
	
} ?>