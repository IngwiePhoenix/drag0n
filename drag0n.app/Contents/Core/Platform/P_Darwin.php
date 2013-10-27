<?php class P_Darwin implements Platform {

	public static function install($in, $out, $override=false) {
		
		// What to do, if our destination file exists?
		if(file_exists($out)) {
			if(file_exists("$out.bak") && !$override) {
				self::rename("$out.bak", "$out.bak.".time());
				$return = self::F_BACKUP;
			} elseif(!file_exists("$out.bak") && !$override) {
				self::rename("$out", "$out.bak");
				$return = self::F_BACKUP;
			}
		} else {
			$return = self::F_ORIG;
			self::touch($out); // Touch it first.
			self::copy($in, $out); // Now copy the file
		}
		return $return;
	}

	public static function copy($src, $dest) { return copy($src, $dest); }
	public static function rename($src, $dest) { return rename($src, $dest); }
	public static function delete($file) { return delete($file); }
	public static function touch($file) { return touch($file); }
	
	public function sanityCheck() { return true; }	

	public static function version() {
		echo "Built-in Platform representtion for Mac OS X";
	}
	public function __invoke() { return self::version(); }

}