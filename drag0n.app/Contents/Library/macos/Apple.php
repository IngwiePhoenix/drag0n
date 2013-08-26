<?php class Apple {

	// Userland
	public $userName;
	public $userHome;
	
	// System
	public $tmpDir;
	public $workDir;

	// Framework loader
	public $paths=array(
		'/System/Library/Frameworks/',
		'/Library/Frameworks/',
	);

	public function init($appName="drag0n Installer", $path=array()) {
			
		// prepair the user-vars
		if(isset($_ENV['HOME'])) {
			$this->paths[] = $_ENV['HOME']."/Library/Frameworks/";
			$this->userName = ($_ENV['USER']!="root"
			                  ?$_ENV['USER']
			                  :array_pop(
			                  		explode("/",$_ENV['HOME'])
			                  ));
			$this->userHome = $_ENV['HOME'];
		}
		
		// System
		$this->tmpDir = sys_get_temp_dir();
		if(!is_null($appName)) {
			$this->workDir="/Library/Application Support/".$appName;
			@mkdir($this->workDir);
		}
		
		if(is_string($path)) $path = explode( PATH_SEPARATOR, $path );
		$phpPaths = explode( PATH_SEPARATOR, get_include_path() );
		$this->paths = array_merge( $this->paths, $phpPaths, $path );
	}
	
	public function addPath($path) {
		foreach($this->recrusiveSearch($path) as $app) {
			$f = $app."/Contents/Frameworks";
			if(file_exists($f)) $this->paths[] = $f;
		}
		return $this;
	}

	public function framework($name) {
		if(strpos($name, ".framework") == false) $name.=".framework";
		$fp = null;
		if(!file_exists($name)) {
			// User has not given an absolute path.
			$paths = $this->paths;
			// walk the paths to search for the framework.
			foreach($paths as $p) {
				$fr = realpath("$p/$name");
				if($fr) { $fp = $fr; }
			}
			if(empty($fp)) trigger_error("Couldn't find framework $name in paths (".implode(", ", $paths).")", E_USER_ERROR); # No framework found.
 		} else $fp = $name;
		$mainFile = "$fp/Resources/main.php";
		if(file_exists("$fp/Info.json")) {
			$info = json_decode(file_get_contents("$fp/Info.json"));
			$mainFile = $info['mainFile'];
		}
		include($mainFile);
	}
	
	public function checkName($name,$e) {
		if(
			$e == null 
			|| substr($name,-strlen($e)) == $e
		) return true; else return false;
	}		

	public function recrusiveSearch($folder, $ext=".app", $caller=array()) {
		if(empty($caller)) $caller = array($this, "checkName");
		$dir = scandir($folder);
		$dir = array_diff($dir, array(".",".."));
		$folders = array();
		foreach($dir as $f) {
			if(!is_dir($folder."/".$f)) continue;
			if(call_user_func_array($caller, array($f,$ext))) { $folders[] = $folder."/".$f; }
			else { $folders = array_merge($folders, $this->recrusiveSearch($folder."/".$f)); }
		}
		return $folders;
	}

} ?>