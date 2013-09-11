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
	public function __construct($infoPath, $installPath="/");
	
	// Do the actual copying process.
	// Should return self::F_ORIG or self::F_BACKUP
	public function icp($in, $out);
	
	// Download given URL content and return an array of [content=>str, http_code=>int]
	public function download($url);
	
	// Downloads multiple things at once - numeric array. Requires pthreads to be installed.
	public function th_download(array $urls);
	
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
	   This should consist of an array like so:
	   [
	   		"pkg"=>id, name, etc
	   		"backups"=>name of backupped files
	   		"files"=>files that are installed
	   		"scripts"=>[ "preinstall"=>..., "postinstall".... ]
	   ]
	*/
	public function getInfo($id);
	
	// validate a resource.
	// $in can be a url OR an idendifier from the D0-DB.
	public function validateResource($in);
	
	// Validate package. If you have hashes, you are ADVISED to use this.
	public function validateFile($file);
	
	// Get information from a remote DB. By default, this is the D0-DB. Other classes implementing that, may change this...just maybe.
	// The D0-DB responds to an ID=Type pair.
	// Type can be one of the T_ constants.
	public function retrieve($id,$type);
	
	
	
} ?>