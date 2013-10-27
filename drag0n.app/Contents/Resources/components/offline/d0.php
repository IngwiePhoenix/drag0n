<?php

/*
	This file is a nice example for PHP's hidden singletons!
	syntax: 
	d0()->installFile("/path/to/d0p");
	d0("/var/www/repo/test.d0p")->download("http://drag0ninstaller.tk/pkg/d0.test-package.d0p");
	d0("/var")->installFile("/var/install.d0p");
	the d0 function will define the destination path. this is going to be required on a slew of methods.
*/

include_once "SpycObject.php";

function d0($dest=null) { return Drag0nInstaller::instance($dest); }
class Drag0nInstaller {

	// statics
	public static function version() { return "0.2b"; }
	
	// singleton syntax
	private static $instance;
	static function instance($dest) { if(!self::$instance) self::$instance = new Drag0nInstaller($dest); return self::$instance; }
	private function __construct($dest, $root="/") { 
		if(is_null($dest)) trigger_error("You're attempting to run d0 with an empty base directory!", E_USER_ERROR);
		$this->basedir = $dest;
		$this->root=$root;
		$this->resDir = $dest.$this->resDir;
		$this->installedList = $dest.$this->installedList;
		$this->pkgList = $dest.$this->pkgList;
		$this->resList = $dest.$this->resList;
		$this->pkgDir = $dest.$this->pkgDir;
		$this->iconsDir = $dest.$this->iconsDir;
		$this->tmpdir = sys_get_temp_dir()."/"; 
		@mkdir($dest);
		@touch($this->installedList);
		@touch($this->pkgList);
		@touch($this->resList);
		@mkdir($this->resDir);
		@mkdir($this->pkgDir);
		@mkdir($this->iconsDir);
	}
		
	// working with
	public $root="/";
	public $resDir="/Resources";
	public $installedList="/Installed.d0i";
	public $pkgList="/Packages.d0i";
	public $resList="/Resources.d0i";
	public $pkgDir="/Packages";
	public $iconsDir="/icons";
	public $nodejs="node";
	public $shell="/bin/bash";
	private $basedir;
	private $tmpdir;
	
	// Resources
	public $stderr=false;
	public $stdin=false;
	public $stdout=false;
	
	// constants
	const EXISTED=10;
	const IS_NEW=11;
	
	// The way to say.
	public function say($what, $type="cmd", $who=null) {
		if(!$this->stdout) $this->stdout = fopen("php://stdout","a");
		if(!$this->stderr) $this->stderr = fopen("php://stderr","a");
		
		$bt = debug_backtrace();
		$func = $bt[1]["function"];
		$line = $bt[1]["line"];
		$class = (isset($bt[1]["class"]) ? $bt[1]["class"]."->" : "");
		
		$who = (is_null($who)?"d0/".$class.$func:$who);
		
		switch($type) {
			case "error":
				fwrite($this->stderr, json_encode(array(
					"type"=>"error", "who"=>$who, "what"=>$what."\n"
				))); break;
			default:
				fwrite($this->stdin, json_encode(array(
					"type"=>$type, "who"=>$who, "what"=>$what."\n"
				))); break;
		}
	}
	// wrapper
	public function error($what, $who=null) { $this->say($what, "error", $who); }
	public function debug($what, $who=null) { $this->say($what, "debug", $who); }
	public function success($what, $who=null) { $this->say($what, "success", $who); }
	public function log($what, $who=null) { $this->say($what, "log", $who); }
	public function cmd($what, $who=null) { $this->say($what, "cmd", $who); }
	
	// a copy of unix' install programm.
	// Paths must be absolute - use realpath()
	public function install($file,$destfile) {
		if(empty($file) || empty($destfile)) die("One of the arguments is missing!");
		if($file != "./" || $file != "../") {
			$file = realpath($file);
			if(substr($destfile,-1) == "/") $destfile=substr($destfile,0,-1);
			$this->log("Installing { $file } to { $destfile }", "Install");
			if(file_exists($destfile)) {
				rename($destfile,$destfile."-orig_".time());
				if(!is_dir($file))
					copy($file,$destfile);
				else
					@mkdir($destfile,0777,true);
				$rt = self::EXISTED;
			} else {
				if(is_dir($file))
					mkdir($destfile,0777,true);
				else
					copy($file,$destfile);
				$rt = self::IS_NEW;			
			}
			return $rt;
		}
	}
	public function installPhase(array $data) {
		$backups = array();
		$hasbackups = false;
		extract($data);
		$rt = $meta->Data->Runtime;
		$this->handle($rt,"preinstall");
		foreach($files as $f) {
			if($f['real'] != "./" || $f['real'] != "../") {
				$inst = $this->install($f['temp'],$this->root."/".$f['real']);
				if($inst == self::EXISTED) $backups[] = $f['real']; 
			}
		}
		$this->handle($rt,"postinstall");
		return $backups;
	}
	
	public function handle($runtime,$type) {
		if(isset($runtime->$type)) {
			$rtd=$runtime->$type;
			switch(strtolower($rtd->type)) {
				case "sh":
				case "bash":
					$this->runShell($rtd->content);
					break;
				case "php":
					$this->runPHP($rtd->content);
					break;
				case "nodejs":
					$this->runNodeJS($rtd->content);
					break;
				case "appjs":
					$file = $this->tmpdir."/".uniqid();
					touch($file); file_put_contents($file,$rtd->content);
					$this->runAppJS(new Archive_Tar($file),$rtd->start);
					break;
				default: return null; break;
			}
		}
	}
	
	public function installFile($file) {
		$data = $this->extract($file);
		if(is_array($data)) {
			$inst = $this->installPhase($data);
			if(is_array($inst)) {
				$id=$data['meta']->Info->id;
				copy($data['metaFile'],$this->getMetaFile($id)); #####
				$this->setBackupFiles($inst,$id);
				$this->setFiles($data['files'],$id);
				$this->setIcon(base64_decode($data['meta']->Info->icon) ,$id);
				#$this->addToPkgList() ###
			} else die("Error while installing.");
		} else die("An error occured during extraction.");
	}
	public function installUrl($url) {
		$file = $this->download($url);
		if($file != false) $this->installFile($file);
		else die("Error while downloading the file at: ".$url);
	}
	public function installID($id) {}
	public function reinstall($id) {}
	public function uninstall($id) {}
	public function download($url) {
		$file = $this->tempdir."/".uniqid().".d0";
		file_put_contents($file,file_get_contents($url));
		if(is_binary($file) && mime_content_type($file)=="application/x-gzip")
			return $file;
		else return false;
	}
	
	// d0p specific functions
	public function extract($file,$del=false) {
		$this->log("Extracting [".$file."] ".($del?"and deleting it afterwards.":""), "d0p/extract");
		$tname = $this->tmpdir."/".basename($file);
		$tfname = $tname."-files";
		copy($file,$tname);
		if($del) unlink($file);
		$main = new drag0nPackage($tname, $tfname);
		$meta = $main->unpack();
		$tars = $main->files();
		$complete = false;
		$files = array();
		$this->log("Extraction, phase 1", "d0p/extract");
		foreach($tars as $file) {
			$this->debug("Checking: $file","d0/extract");
			if( file_exists("$tfname/$file")) {
				$complete=true;
				$files[] = array(
					"real"=>realpath($tname."-files/".$file),
					"temp"=>$file
				);
			} else $complete=false;
		}
		if($complete) {
			unset($main);
			$this->success("Extraction, phase 1: Success!", "d0p/extract");
			$this->log("Extraction, phase 2", "d0p/extract");
			$metaFile = $tname."-files/.d0i";
			$meta2 = json_decode( json_encode($meta), true ); # and bam, all AOs gone to arrays :)
			file_put_contents(
				$metaFile,
				SpycObject($meta2, false, "Dump")
			);
			$this->success("Extraction, phase 2: Success", "d0p/extract");
			return array( 'files'=>$files, 'meta'=>$meta, 'metaFile'=>$metaFile );
		} else {
			$this->error("Something must have happened during phase 1!","d0/extract");
			return false;
		}
	}
	
	public function installed() {
		$pkgs = SpycObject( $this->getPkgListFile() );
		if($pkgs != false) return $pkgs;
		else return false;
	}
	public function isInstalled($id) {
		$pkgs = $this->installed();
		if($pkgs != false && isset($pkgs[$id]))
			 return true;
		else return false;
	}
	public function updateSources() {
		$dir = scandir($this->getSourceDir());
		$excludes = array(".","..");
		$dir = array_diff($dir,$excludes);
		print_r($dir);
	}
	public function removeIDFromList($id) {
		$this->log("Removing: $id", "d0/removeID");
		$pkgs = $this->getPkgListFile();
		$pkgs_a = SpycObject($pkgs);
		if(!$pkgs_a) $pkgs_a=array();
		if(isset($pkg_a[$id])) { 
			unset($pkg_a[$id]);
			$rt= true;
		} else $rt= false;
		file_put_contents($pkgs,SpycObject($pkgs_a,false,"Dump"));
		return $rt;
	}
	public function addIDToList($id,$info) {
		$this->log("Adding: $id", "d0/addID");
		$pkgs = $this->getPkgListFile();
		$pkgs_a = SpycObject($pkgs);
		if(!$pkgs_a) $pkgs_a=array();
		$pkgs_a[$id]=$info;
		file_put_contents($pkgs,SpycObject($pkgs_a,false,"Dump"));
	}
	
	public function addSource($source) {
		$this->log("Adding: $source", "d0/addSource");
		$url = parse_url($source);
		if(empty($url['scheme'])) $url['scheme']="http";
		$source=$url['scheme']."://".$url['host'].$url['path'];
		$src = $this->get($source."/Resource.d0i",true);
		$pkg = $this->get($source."/Packages.d0i",true);
		if($src && $pkg) {
			$_src = $src['data'];
			$_pkg = $pkg['data'];
			$id = (isset($_src->id) ? $_src->id : $this->web2id($_src->resource));
			file_put_contents(
				$this->getSourceDir($id)."/Resource.d0i",
				$src['response']
			);
			file_put_contents(
				$this->getSourceDir($id)."/Packages.d0i",
				$pkg['response']
			);
			$this->setIcon($pkg['data']['icon'],$id);
			#$this->updateSources();
			#$this->unifySources();
			return true;
		} else return false;
	}
	public function removeSource($id) {
		$this->log("Removing: $id", "d0/removeSource");
		$this->rmdir_files($this->getSourceDir($id));
		unlink($this->getIcon($id));
	}
	public function unifySources() {
		$sources = $this->getSourceDir();
		$listFile = $this->resList;
		$all = array();
		foreach(array_diff(scandir($sources), array(".","..")) as $sDir) {
			$this->debug("Working with: $s","d0/unifySources");
			if(is_dir($sDir)) {
				$this->debug("Adding: $sDir", "d0/unifySources");
				$s = SpycObject($sDir."/Resource.d0i");
				unset($s->id);
				$all[$s->id] = $s;
			}
		}
		file_put_contents(
			$listFile,
			SpycObject($all, false, "Dump")
		);
	}
	
	public function getSource($id) { return SpycObject($this->getSourceDir($id)."/Resource.d0i"); }
	public function getSourceDir($id=null) { return ( is_null($id) ? $this->resDir : $this->resDir."/$id" ); }
	public function getSourceList() { return SpycObject($this->resList); }
	public function getPkgListFile() { return $this->pkgList; }
	public function getPkgDir($id=null) { return ( is_null($id) ? $this->pkgDir : ( $this->isInstalled($id) ? $this->pkgDir."/$id" : false ) ); }
	
	public function getBackupFilesFile($id) { return $this->getPkgDir($id)."/Backups.d0i"; }
	public function getMetaFile($id) { return $this->getPkgDir($id)."/Info.d0i"; }
	public function getBackupFiles($id) { 
		return ( file_exists($this->getBackupFilesFile($id))
			? SpycObject($this->getBackupFilesFile($id))
			: null
		);
	}
	public function setBackupFiles(array $files,$id) {
		$m=$this->getBackupFilesFile($id);
		return file_put_contents($m,SpycObject($files,false,"Dump"));
	}
	public function getMeta($id) {
		if(file_exists($this->getMetaFile($id))) {
			return SpycObject($this->getMetaFile($id));
		} else return null;
	}
	public function getFilesFile($id) { return $this->getPkgDir($id)."/Files.d0i"; }
	public function setFiles(array $data,$id) {
		$files = array();
		foreach($data as $file) { $files[] = $file['real']; }
		file_put_contents(
			$this->getFilesFile($id),
			SpycObject($files, false, "Dump")
		);
	}
	public function getFiles($id) { return SpycObject($this->getFilesFile($id)); }
	public function setIcon($icon,$id) {
		$tf = $this->tmpdir."/$id-".uniqid();
		file_put_contents($tf,$icon);
		$ext = array_pop(explode("/", mime_content_type($tf)));
		$file = $this->getIconDir()."/$id.$ext";
		return file_put_contents($file,$icon);
	}
	public function getIconDir() { return $this->iconDir; }
	
	// script runners
	public function runShell($cmdStrng) {
		$file = $this->tmpdir."/".uniqid();
		touch($file);
		file_put_contents($file,$cmdString);
		return $this->terminal($this->shell,$file);
	}
	public function runPHP($code) {
		// The eval'd script HAS to define RETURN_CODE since we cant use exit.
		try { 
			eval($code);
			defined("RETURN_CODE") or define("RETURN_CODE",true);
			return RETURN_CODE;
		} catch(Exception $e) { return false; }
	}
	public function runNodeJS($js) {
		$file=$this->tmpdir."/".uniqid();
		file_put_contents($file,$js);
		return $this->terminal($this->nodejs,$file);
	}
	public function runAppJS(Archive_Tar $archive,$start) {
		// now this is more complicated...
		$folder = $this->tmpdir."/".uniqid();
		mkdir($folder);
		$archive->extract($folder);
		return $this->terminal($this->nodejs,$folder."/".$start);
	}
	public function terminal($bin,$file) {
		$descriptors = array(
			0 => array("pipe","r"),
			1 => array("pipe","w"),
			2 => array('pipe','r')
		);
		$env = array(
			'D0_VERSION'=>self::version(),
			"D0_BASE"=>$this->basedir,
			"D0_ROOT"=>$this->root,
			"D0_PKG_DIR"=>$this->getPkgDir($id)
		);
		$cwd = realpath( dirname($file) );
		$env = array_merge($_ENV, $env);
		$proc = proc_open(
			$bin." ".escapeshellarg(realpath($file)),
			$descriptors, $pipes, $cwd, $env
		);
		if(is_resource($proc)) {
			foreach($pipes as $pipe) fclose($pipe);
			$rtval = proc_close($proc);
			// TODO: handle error codes, for restarts and alike.
			if($rtval) return true; else return $rtval;
		}
	}
	
	public function web2id($url) { return implode(".", array_reverse( explode(".", parse_url($url,PHP_URL_HOST)) )	); }
	public function get($url,$d0i=false) {
		/*
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_exec($curl);
		$info = curl_getinfo($curl);
		curl_close($curl);
		*/

		try{ 
			$content = file_get_contents($url);
			if($d0i) {
				return array('data'=>SpycObject($content), 'response'=>$content);
			} else return array('response'=>$content);
		} catch(Exception $e) { return false; }
	}
	public function rmdir_files($dir) {
		$dh = opendir($dir);
		if($dh) {
			while($file = readdir($dh)) {
				if(!in_array($file, array('.', '..'))) {
					if(is_file($dir.$file)) {
    					 unlink($dir.$file);
    				}
			    	else if (is_dir($dir.$file)) {
    	 				$this->rmdir_files($dir.$file);
    				}
				}
			}
			rmdir($dir);
 		}
 	}
 	public function Rcopy($source, $target) {
        if (!is_dir($source)) {//it is a file, do a normal copy
            copy($source, $target);
            return;
        }

        //it is a folder, copy its files & sub-folders
        @mkdir($target);
        $d = dir($source);
        $navFolders = array('.', '..');
        while (false !== ($fileEntry=$d->read() )) {//copy one by one
            //skip if it is navigation folder . or ..
            if (in_array($fileEntry, $navFolders) ) {
                continue;
            }

            //do copy
            $s = "$source/$fileEntry";
            $t = "$target/$fileEntry";
            $this->Rcopy($s, $t);
        }
        $d->close();
    }
    public function extractVersion($v) {return preg_replace("[\D]","",$v);}
	
} ?>