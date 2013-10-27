<?php class OS_UNIX implements OperatingSystem {
	
	public static function configurationFilePath() { return '/etc'; }
	
	public static function check4installed() { return array(); }
	# Not implemented, yet. In normal case, check for package manager and search os for .d0v
	
	public static function workingDirectory() { return '/usr/shared/d0'; }
	
	public static function setPStore($key, $value) {
		$file = self::workingDirectory().'/boot';
		@touch($file);
		if(!file_exists($file)) { return trigger_error("Unable to write boot information for persistant storage."); }
		$bo = Spyc::YAMLLoad($file);
		$bo[$key]=$value;
		file_put_contents(Spyc::YAMLDump($bo), $file);
	}
	public static function getPStore($key) {
		$file = '/usr/shared/d0/boot';
		if(!file_exists($file)) { return trigger_error("Unable to read boot information for persistant storage."); }
		$bo = Spyc::YAMLLoad($file);
		return $bo[$key];
	}
	
}