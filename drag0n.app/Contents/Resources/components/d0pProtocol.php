<?php class d0pp {

	public $context;
	private $d0p;
	
	public function __construct() {
		include_once "drag0nPackage.php";
	}
	
	public function stream_open($path, $mode, $options, &$opened_path) {
		$this->setup($path);
	}
	
	public function stream_read($count) {}
	public function stream_write($data) {}
	public function stream_tell() {}
	public function stream_eof() {}
	public function stream_seek($offset, $whence) {}
	public function stream_stat() { return json_decode( json_encode( $this->d0p->extractMeta() ), true); }
	
	#dir
	public function dir_opendir($path, $options) {
		$this->setup($path);
	}
	
	
	/*
		Sets up the d0p instance. Possible values are:
		d0p:/path/to/absolute/file.d0p
		d0p:./relative/file.d0p
		d0p:http://example.com/fromUrl.d0p
		FTP and other streams work too - they are downloaded and stored, then passed to the d0p class.
	*/	
	private function setup($url) {
		$p = parse_url(parse_url($url,PHP_URL_PATH));
		if($p['schema'] && $p['host']) {
			$path = sys_get_temp_dir()."/".uniqid().".tmp.d0p";
			file_put_contents($path, file_get_contents($p));
		} else $path = $p['path'];
		$this->d0p = new drag0nPackage($path);
	}

} ?>