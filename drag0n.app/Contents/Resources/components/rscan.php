<?php function rscan($dir, $onlyfiles = false, $fullpath = false) {
	if (isset($dir) && is_readable($dir)) {
		$dlist = array();
		$dir = realpath($dir);
		if ($onlyfiles) {
			$objects = new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator($dir)
			);
		} else {
			$objects = new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator($dir), 
				RecursiveIteratorIterator::SELF_FIRST
			);
		}
		
		foreach($objects as $entry => $object){	
			if (!$fullpath) {
				$entry = str_replace($dir."/", '', $entry);
			}
			
			$dlist[] = $entry;
		}
		
		return $dlist;
	}
} ?>