<?php interface OperatingSystem {
	
	// Return the path with the OS' configuration files.
	public static function configurationFilePath();
	
	// Search for pre-installed applications. Output is an array.
	// And yes, this operation may consume some time. However, check for d0()->quiet.
	public static function check4installed();
	
	// Determine a working directory.
	// That should be used once we're creating an instance to an installer and it wants to store information and blah.
	public static function workingDirectory();
	
	// Store/get a value permanently, even across reboots.
	// Used to install boot-time packages...very important, make sure your checks are awful secure.
	// drag0n will store quite some info there actually. o-o
	public static function setPStore($key, $value);
	public static function getPStore($key);
	
}