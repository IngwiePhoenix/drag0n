#!/usr/bin/php
<?php

function say($type, $message) { echo json_encode(array("type"=>$type, "msg"=>$message, "who"=>"Install script"))."\n"; }

$me = realpath("../");

say("log","Working from directory: $me");

if(!file_exists("/usr/bin/d0")) {
	$script = array(
		"#!/bin/bash",
		"$me/Application/yiic d0installer $*"
	);
	$script = implode("\n", $script);
	if(is_writable("/usr/bin"))
		file_put_contents("/usr/bin/d0", $script);
	else
		say("error","Unable to write script file into /usr/bin.");
} else say("success", "d0 script seems to be already installed to /usr/bin/d0.");

if(!file_exists("/usr/bin/d0p")) {
	$script = array(
		"#!/bin/bash",
		"$me/Application/yiic d0package $*"
	);
	$script = implode("\n", $script);
	if(is_writable("/usr/bin"))
		file_put_contents("/usr/bin/d0p", $script);
	else
		say("error","Unable to write script file into /usr/bin.");
} else say("success", "d0p script seems to be already installed to /usr/bin/d0.");