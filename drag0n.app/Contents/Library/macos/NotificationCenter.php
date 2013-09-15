<?php class NotificationCenter {

	// Vars...
	public $gID;
	public $bin;

	// Create the instance. Note the prepaired arguments
	public function __construct($gID="terminal-notify", $binary=null) {
		$this->bin = ( is_null($binary) ? dirname(__FILE__)."/terminal-notifier.app/Contents/MacOS/terminal-notifier" : $binary );
		$this->gID=$gid;
	}

	public function message($message, $title="Terminal", $subtitle=null, $url=null, $cmd=null, $id=null) {
		// Build array first.
		system(join(" ", [
			$this->bin,
			"-message",
				escapeshellarg($message),
			"-title", 
				escapeshellarg($title),
			"-group", 
				escapeshellarg($this->gID),
			( !is_null($url) 
				? "-open ".escapeshellarg($url) 
				: "" 
			), ( !is_null($cmd) 
				? "-execute ".escapeshellarg($cmd) 
				: "" 
			), ( !is_null($id) 
				? "-activate ".escapeshellarg($id) 
				: "" 
			)
		]));
	}

} ?>