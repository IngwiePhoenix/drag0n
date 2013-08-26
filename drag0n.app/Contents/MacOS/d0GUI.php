<?php
	
	define("_DISPLAY_WEB",true);
	ob_start();
	include "drag0n";
	$out = ob_get_clean();
	$a = explode("\n", $out);
	unset($a[0]);
	echo implode("\n", $a);

?>