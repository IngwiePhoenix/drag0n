<?php interface Platform {

	// Classes from this type may always be called statically.
	
	const F_ORIG=0xf1;
	const F_BACKUP=0xf2;

	// Do the actual copying process.
	// Should return self::F_ORIG or self::F_BACKUP
	public static function install($in, $out, $override=false);

	// normaly copy a file.
	// For bundles, add the file to the bundle, or archive, whatever.
	public static function copy($src, $dest);
	
	// Rename a file
	public static function rename($src, $dest);
	
	// Delete a file
	public static function delete($file);
	
	// Create an empty file
	public static function touch($file);
	
	// Perform sanity checks, to make sure we're all okay.
	public function sanityCheck();
	
	// return some information, like the version and such.
	// Use __invoke to return Version.
	public static function version();
	public function __invoke();

}