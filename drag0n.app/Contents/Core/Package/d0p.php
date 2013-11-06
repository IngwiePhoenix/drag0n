<?php class d0p implements Package {

	# Vars
	public $archiveName;
	public $output=null;
	public $meta=[];

	// Of course, we need to generate an object:
	// $output may be null, if we just want to list contents, get meta, or alike.
	public function init($archiveName, $output=null) {
		$this->archiveName=$archiveName;
		$this->output=$output;
	}
	
	// List the contents
	/* array(
		"/absolute/path"=>array(type, size, ...),
		"relative/path"=>array(type, size, ...)
	) */
	// Note, d0p files will extract relative to the CWD, even if you supplied an apsolute path!
	public function listFiles() { return true; }
	
	// Extract single file
	// $innerFile == path inside archive
	public function extractOne($innerFile) { return true; }
	
	// ...and all
	// Extract to $this->output, if it's a folder to where we can extract
	public function extract() { return true; }
		
	// Add files/dirs (dirs must be added completely/recrusively)
	// $objects = array("path/to/file", "path/to/file", ...)
	public function add(array $objects) { return true; }
	
	// Remove a file/dir by archive path
	public function delete($object) { return true; }
	
	// Of course...we want to save any changes, like metadata
	public function save() { return true; }
	
	// Set meta
	public function setMeta($key, $value) { 
		$this->meta[$key]=$value;
		return $this;
	}
	
	// Return the value of supplied key, or an object with all meta data. If asObject is false, return array.
	public function getMeta($key=null, $asObject=true) {
		if(is_null($key)) {
			if($asObject) return (object)$this->meta;
			else return $this->meta;
		} else {
			return $this->meta[$key];
		}
	}
	
	// return some information, like the version and such.
	// Use __invoke to return Version.
	public static function version() { return "d0p/0.1-alpha"; }
	public function __invoke() { return $this->version(); }

}