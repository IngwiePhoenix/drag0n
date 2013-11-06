<?php class Terminal {

	const ERASE_WOLE_LINE="\033[0K";
	const CR="\r";
	
	public static function out($message, $onCurrentLine=false) {
		$s = ($onCurrentLine ? self::CR.self::ERASE_WHOLE_LINE : null);
		self::outputToTerminal($s.$message);
	}
	
	public static function stdout($message) {
		self::outputToTerminal($message);
	}
	
	public static function err($message) {
		self::outputToTerminal("\033[0;31m".$message."\033[00m\n");
	}

	public static function debug($message) {
		self::outputToTerminal("\033[0;35m".$message."\033[00m\n");
	}

	public static function log($message) {
		self::outputToTerminal("\033[0;32m".$message."\033[00m\n");
	}
	
	private static function outputToTerminal($message) {
		$fh = fopen('php://fd/1','a'); #Append to fd1 == stdout. ://stdout somehow doesnt work...
		fputs($fh, $message);
		return fclose($fh);
	}
	
}