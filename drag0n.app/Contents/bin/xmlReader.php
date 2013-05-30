<?php
	$opt = getopt("","xml:");
	echo json_encode(
		simplexml_load_file($opt['xml'])
	);
?>