<?php interface Package {

	// Of course, we need to generate an object:
	public function __construct($file, $output);
	
	// List the contents
	public function list();
	
	// Extract single file
	public function extractOne($innerFile);
	
	// ...and all
	public function extract();
		
	// Add files/dirs (dirs must be added completely)
	public function add(array $objects); 
	
	// Remove a file/dir
	public function delete($object);
	
	// Of course...we want to save.
	public function save();
	
	// Set meta
	public function setMeta(array $meta);
	public function getMeta($key=null, $asObject=true);

} ?>