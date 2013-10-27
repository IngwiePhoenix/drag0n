<?php class OS_Darwin extends OS_UNIX {
	
	public static function check4installed() {
		$syspro1 = shell_exec('system_profiler SPApplicationsDataType');
		#$syspro2 = shell_exec('system_profiler SPDeveloperToolsDataType');
		#$syspro3 = shell_exec('system_profiler SPSoftwareDataType');
		$obj = Spyc::YAMLLoad($syspro1);
		return $obj->Applications;
	}
	
	public static function workingDirectory() { return '/Library/Application Support/drag0n'; }
	
	public static function setPStore($key, $value) {
		shell_exec( 'nvram '.escapeshellarg($key.'='.$value) );
		// Counter-check
		$o = trim( shell_exec('nvram '.escapeshellarg($key)) );
		list($rkey,$rval) = explode("\t",$o);
		$rkey = trim($rkey);
		$rval = trim($val);
		if($rkey == $key && $rval == $val)
			return true;
		else return false;
	}
	public static function getPStore($key) {
		$o = trim( shell_exec('nvram '.escapeshellarg($key)) );
		list($rkey,$rval) = explode("\t",$o);
		$rkey = trim($rkey);
		$rval = trim($rval);
		if($rkey == $key)
			return $rval;
		else return -1;
	}
	
}