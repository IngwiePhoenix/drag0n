<?php 

if( // This shall not work if we do not have multi-threading, ssh2 and gpg.
	!extension_loaded("ssh2") 
	&& !extension_loaded("gnupg") 
	&& !extension_loaded("pthreads")
) die("! Dependencies not met !");
include_once "SpycObject.php";

function d0($dest=null) { return Drag0nInstaller::instance($dest); }
class drag0n implements Installer {
	
	// Vars
	public $infoPath;
	public $root;
	public $tmpDir;
	public $dirs=array();
	
	// Instances
	private $Apple;
	
	// Constructor.
	private function __construct($installPath="/", $infoPath) {		
		// Set up the Mac OS X environment
		$this->Apple = new Apple;
		$this->infoPath = $this->Apple->workingDir;

		$this->root = $installPath;
		
		foreach(array(
			"installed"	=>"/Installed.d0i",
			"resDir"	=>"/Resources",
			"resList"	=>"/Resources.d0i",
			"pkgDir"	=>"/Packages",
			"pkgList"	=>"/Packages.d0i",
			"icons"		=>"/icons"
		) as $n=>$d) { $this->dirs[$n]=$root.$d; }
		
		// Creating files if not existant
		foreach(array("installed", "resList", "pkgList") as $vn) @touch($this->dirs[$vn]);
		foreach(array("resDir","pkgDir","icons") as $vn) @mkdir($this->dirs[$vn]);
	}
	
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

	// Return a version string
	public static function version() { return "0.2b"; }
	
	// customs
	// d0 perma-instance.
	// Used in the GUI.
	public static $_;
	public static function instance() {
		if(!self::$_) self::$_=new self();
		return self::$_;
	}
	
} ?>