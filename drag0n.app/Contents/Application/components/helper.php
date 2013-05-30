<?php 

function tfile() {
	$url = "/tmp/".uniqid();
	$path = APPJS_ROOT.$url;
	touch($path);
	return array(
		'path'=>$path,
		'url'=>$url
	);
} 

?>