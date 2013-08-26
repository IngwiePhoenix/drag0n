<?php class drag0nPackage {

	private $_archive;
	private $_dest;
	private $_fileInfos=array();
	private $_files=array();
	private $loaded=false;
	public $info=array();
	
	public function __construct($archive, $dest=null) {
		ini_set('memory_limit', '1024M');
		$this->_archive = $archive;
		if($dest) {
			$this->_dest = $dest;
			if(!file_exists($this->_dest)) { mkdir($this->_dest, 0777, true); }
			if(!is_writable($this->_dest)) { trigger_error("Can not write to destination.", E_WARNING); }
		}
	}
	
	public function setInfo($name, $value=null) {
		if(is_array($name)) $this->info = array_merge($this->info, $name);
		if(is_string($name)) $this->info[$name]=$value;
		return $this;
	}
	
	public function addFile($file) {
		if($file == "." || $file == "..") return;
		if(file_exists($file)) {
			$this->_fileInfos[$file]=array(
				"contents"=>file_get_contents($file),
				"type"=>filetype($file)
			);
		}
	}
	
	public function removeFile($f) {
		if(isset($this->_fileInfos[$file])) {
			unset($this->_fileInfos[$file]);
		}
	}
	
	public function addFiles(array $files) { foreach($files as $f) $this->addFile($f); }
	public function removeFiles(array $files) { foreach($files as $f) $this->removeFile($f); }
	
	public function files() {
		if(empty($this->_files)) {
			foreach($this->_fileInfos as $f=>$_) 
				$this->_files[] = $f;
		}
		return $this->_files;
	}
	
	public function pack() {
	
		// Verification checks
		if(empty($this->_archive)) { trigger_error("Archive parameter is empty! Can not pack into the nowhere."); return false; }
		if($this->_dest) { trigger_error("We're in unpacking mode, since a destination is given. Use unpack instead."); return false; }
		try { touch($this->_archive); } 
		catch(Exception $e) { 
			print_r($e); 
			trigger_error("Preperation for packing failed."); 
			return false; 
		}
		
		$infos = $this->info;
		$infos['files']=$this->_fileInfos;
		$output = null;
		if(($output = serialize($infos))!=false) {
			unset($infos); 
			return file_put_contents($this->_archive, gzdeflate($output, 9));
		} else { 
			unset($infos); 
			return false;
		}
	}
	
	public function unpack($delete=false) {
		
		// Validation cheks:
		if(!$this->_dest) { trigger_error("Can't decompress when no destination is given."); return false; }

		$infos = unserialize( gzinflate( file_get_contents( $this->_archive ) ) );
		if($infos == false) { trigger_error("You possibly caught an invaild d0p file. I wasn't able to parse it."); return false; }
		foreach($infos['files'] as $fileName => $file) {
			$this->_fileInfos[$fileName]=$file;
			if($file['type']=="file") {
				$filePath = $this->_dest."/".$fileName;
				$dirPath = dirname($filePath);
			} else {
				$dirPath = $this->_dest."/".$fileName;
			}
			if(!file_exists($dirPath) && is_writable($this->_dest)) { mkdir($dirPath,0777,true); }
			if($file['type']=="file") { file_put_contents($filePath, $file['contents'], LOCK_EX); chmod($filePath, 0777); }
		}
		$this->loaded=true;
		unset($infos['files']);
		$this->info = $infos['meta'];
		unset($infos['meta']);
		if($delete) unlink($this->_archive);
		return $this->info;
	}
	
	public function unpackFile($fileName) {
		if(!$this->loaded) $this->load();
		if(!file_exists($this->_archive)) { trigger_error("Can not unpack from empty/non-existing file."); return false; }
		if(!$this->_dest) { trigger_error("Can not extract without a destination."); return false; }
		if(isset($this->_fileInfos[$fileName]) && $this->_fileInfos[$fileName]['type']=="file") {
			return ( 
				file_put_contents($this->_dest."/".$fileName, $this->fileInfos[$fileName]['contents']) 
				&& chmod($this->_dest."/".$fileName, 0777)
			);
		}
	}
	
	public function load() {
		if(!file_exists($this->_archive)) { trigger_error("Can not load from empty/non-existing file."); return false; }
		$infos = unserialize( gzinflate( file_get_contents( $this->_archive ) ) );
		$this->info = $infos['meta'];
		$this->fileInfos = $infos['files'];
		unset($infos);
		$this->loaded = true;
		return true;
	}
	
	public function extractMeta() {
		if(!empty($this->info)) return $this->info;
		if(!$this->loaded) $this->load();
		return $this->info;
	}
	
} ?>