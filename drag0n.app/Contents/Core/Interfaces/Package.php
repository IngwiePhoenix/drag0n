<?php interface Package {

	// Of course, we need to generate an object:
	// $output may be null, if we just want to list contents, get meta, or alike.
	public function __construct($archiveName, $output=null);
	
	// List the contents
	/* array(
		"/absolute/path"=>array(type, size, ...),
		"relative/path"=>array(type, size, ...)
	) */
	// Note, d0p files will extract relative to the CWD, even if you supplied an apsolute path!
	public function list();
	
	// Extract single file
	// $innerFile == path inside archive
	public function extractOne($innerFile);
	
	// ...and all
	// Extract to $this->output, if it's a folder to where we can extract
	public function extract();
		
	// Add files/dirs (dirs must be added completely/recrusively)
	// $objects = array("path/to/file", "path/to/file", ...)
	public function add(array $objects); 
	
	// Remove a file/dir by archive path
	public function delete($object);
	
	// Of course...we want to save any changes, like metadata
	public function save();
	
	// Set meta
	public function setMeta($key, $value);
	
	// Return the value of supplied key, or an object with all meta data. If asObject is false, return array.
	public function getMeta($key=null, $asObject=true);
	
	// return some information, like the version and such.
	// Use __invoke to return Version.
	public static function version();
	public function __invoke();

}