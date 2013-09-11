<?php function SpycObject($input, $convert = false, $action = "Load") {
	include_once "Spyc/Spyc.php";
	if($convert == false) {
		return SpycObject(call_user_func(array("Spyc","YAML".$action), $input), true);
	} else {
		if (is_array($input)) {
			$func = function($d) { return SpycObject($d,true); };
			return new ArrayObject(array_map($func, $input), ArrayObject::ARRAY_AS_PROPS);
		} else {
			// Return object
			return $input;
		}
	}
} ?>