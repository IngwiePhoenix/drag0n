<?php

/*
	This file is a nice example for PHP's hidden singletons!
	syntax: 
	d0()->installFile("/path/to/d0p");
	d0("/var/www/repo/test.d0p")->download("http://drag0ninstaller.tk/pkg/d0.test-package.d0p");
	d0("/var")->installFile("/var/install.d0p");
	the d0 function will define the destination path. this is going to be required on a slew of methods.
*/
if(file_exists(dirname(__FILE__)."/PEAR/")) {
	set_include_path(
		get_include_path()
		.PATH_SEPARATOR
		.realpath(__DIR__)
		.PATH_SEPARATOR
		.realpath(__DIR__)."/PEAR/"
	);
}
include_once "PEAR/PEAR.php";
include_once "Tar.php";
include_once "plistParser.php";

function d0($dest="/test") { return Drag0nInstaller::instance($dest); }
class Drag0nInstaller {
	
	// singleton syntax
	private static $instance;
	static function instance($dest) { if(!self::$instance) self::$instance = new Drag0nInstaller($dest); return self::$instance; }
	private function __construct($dest=null) { 
		$this->dest = $dest;
		$this->tmpdir = sys_get_temp_dir()."/"; 
	}
		
	// working with
	private $root="/test/d0";
	private $nodejs="node";
	private $shell="bash";
	private $dest;
	private $tmpdir;
	
	// constants
	const EXISTED=10;
	const IS_NEW=11;
	
	// a copy of unix' install programm.
	public function install($file,$destfile) {
		if(empty($file) || empty($destfile)) die("One of the arguments is missing!");
		if($destfile != "./"){
			$file = realpath($file);
			if(substr($destfile,-1) == "/") $destfile=substr($destfile,0,-1);
			echo "[Install] Installing { $file } to { $destfile }\n";
			if(file_exists($destfile)) {
				rename($destfile,$destfile."-orig");
				if(!is_dir($file))
					copy($file,$destfile);
				else
					@mkdir($file);
				$rt = self::EXISTED;
			} else {
				if(is_dir($file))
					mkdir($destfile);
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
		$rt = $meta->DATA->RUNTIME;
		$this->handle($rt,"preinstall");
		foreach($files as $f) {
			if($f['real'] != "./") {
				$inst = $this->install($f['temp'],$this->dest."/".$f['real']);
				if($inst == self::EXISTED) { 
					$backups[] = $f['real']; 
				}
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
				$id=$data['meta']->INFO->id;
				copy($data['metaFile'],$this->getMetaFile($id));
				$this->setBackupFiles($inst,$id);
				$this->setFiles($data['files'],$id);
				$this->setIcon($data['meta']->INFO->icon,$id);
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
		$file = $this->tempdir."/".uniqid().".d0p";
		file_put_contents($file,file_get_contents($url));
		if(is_binary($file) && mime_content_type($file)=="application/x-gzip")
			return $file;
		else return false;
	}
	
	// d0p specific functions
	public function extract($file,$del=false) {
		$tname = $this->tmpdir.basename($file);
		if(!file_exists($tname."-files")) mkdir($tname."-files");
		copy($file,$tname);
		if($del) unlink($file);
		$main = new Archive_Tar($tname,"gz");
		$main->extract($tname."-files");
		$tars = $main->listContent();
		$exts = scandir($tname."-files");
		$complete = false;
		foreach($tars as $key=>$file) {
			$fn=$file['filename'];
			if( file_exists($tname."-files/".$fn)) {
				$complete=true;
			}
		}
		if($complete) {
			$complete2=false;
			$datas = array();
			if(file_exists($tname."-files/data.d0d")) $data = new Archive_Tar($tname."-files/data.d0d"); else die("File not existing: ".$tname."-files/data.d0d");
			if(!file_exists($tname."-files/data")) mkdir($tname."-files/data");
			$data->extract($tname."-files/data");
			$ntars = $data->listContent();
			foreach($ntars as $file) {
				$fn=$file['filename'];
				if( file_exists($tname."-files/data/".$fn)) {
					$complete2=true;
					$datas[]=array(
						'temp'=>realpath($tname."-files/data/".$fn),
						'real'=>$fn
					);
				}
			}

			if(file_exists($tname."-files/meta.d0i") && is_readable($tname."-files/meta.d0i")) {
				$meta = new plistParser();
				$meta = $meta->parseFile(realpath($tname."-files/meta.d0i"))->toDrag0n();
			} else die("Cant read metafile!");
			
			if($complete2) return array( 'files'=>$datas, 'meta'=>$meta, 'metaFile'=>$tname."-files/meta.d0i" );
			else		   return false;
		} else return false;
	}
	public function create($from,$to) {}
	
	public function installed() {
		$pkgs = unserialize( $this->getPkgListFile() );
		if($pkgs != false) return $pkgs;
		else return false;
	}
	public function isInstalled($id) {
		$pkgs = $this->installed();
		if($pkgs != false && array_key_exists($id,$pkgs))
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
		$pkgs = $this->getPkgListFile();
		$pkgs_a = unserialize($pkgs);
		if(!$pkgs_a) $pkgs_a=array();
		if(array_key_exists($id,$pkgs_a)) { 
			unset($pkg_a[$id]);
			file_put_contents($pkgs,$pkgs_a);
			return true;
		} else return false;
	}
	public function addIDToList($id,$info) {
		$pkgs = $this->getPkgListFile();
		$pkgs_a = unserialize($pkgs);
		if(!$pkgs_a) $pkgs_a=array();
		$pkgs_a[$id]=array(
			'name'=>$info->name,
			'version'=>$this->extractVersion($info->version),
			'note'=>$info->note
		);
		file_put_contents($pkgs,serialize($pkgs_a));
	}
	public function addSource($source) {
		$url = parse_url($source);
		if(empty($url['scheme'])) $url['scheme']="http";
		$source=$url['scheme']."://".$url['host'].$url['path'];
		$src = $this->get($source."/info.plist",true);
		$pkg = $this->get($source."/pkg.plist",true);
		if($src && $pkg) {
			$id = $this->web2id($src['data']['web']);
			file_put_contents(
				$this->getSourceDir($id)."/info.plist",
				$src['response']
			);
			file_put_contents(
				$this->getSourceDir($id)."/pkg.plist",
				$pkg['response']
			);
			$this->setIcon($pkg['data']['icon'],$id);
			return true;
		} else return false;
	}
	public function removeSource($id) {
		$this->rmdir_files($this->getSourceDir($id));
		#@unlink($this->getIcon($id));
	}
	public function getSource($id) { $parser = new plistParser(); return $parser->parseFile($this->getSourceDir($id)."/info.plist")->toArray(); }
	public function getSourceDir($id=null) {
		if($id!=null)
			$path = $this->root."/sources/".$id;
		else
			$path = $this->root."/sources";
		if(!file_exists($path)) mkdir($path,0777,true);
		return $path;
	}
	public function getPkgListFile() {
		$path = $this->root."/pkg.seri";
		if(!file_exists($path)) touch($path);
		return $path;
	}
	public function getPkgDir($id) {
		if($id!=null)
			$path = $this->root."/pkg/".$id;
		else
			$path = $this->root."/pkg";
		if(!file_exists($path)) mkdir($path,0777,true);
		return $path;
	}
	
	public function getBackupFilesFile($id) { return $this->getPkgDir($id)."/backup.seri"; }
	public function getMetaFile($id) { return $this->getPkgDir($id)."/meta.d0i"; }
	public function getBackupFiles($id) { 
		if(file_exists($this->getBackupFilesFile($id))) 
			return unserialize(file_get_contents($this->getBackupFilesFile($id))); 
		else
			return null;
	}
	public function setBackupFiles(array $files,$id) {
		$m=$this->getPkgDir($id)."/backup.seri";
		if(!file_exists($m)) {
			touch($m);
			file_put_contents($m,serialize($files));
			return true;
		} else return false;
	}
	public function getMeta($id) {
		if(file_exists($this->getMetaFile($id))) {
			$p=new plistParser($this->getMetaFile($id));
			return $p->toDrag0n();
		} else return false;
	}
	public function getFilesFile($id) { return $this->getPkgDir($id)."/files.seri"; }
	public function getFiles($id) {
		$f=$this->getFilesFile($id);
		if(file_exists($f))
			return unserialize(file_get_contents($f));
		else return false;
	}
	public function setFiles(array $data,$id) {
		$files = array();
		foreach($data as $file) { $files[] = $file['real']; }
		if(!file_exists($this->getFilesFile($id))) {
			touch($this->getFilesFile($id));
			file_put_contents(
				$this->getFilesFile($id),
				serialize($files)
			);
			return true;
		} else return false;
	}
#	public function getFiles($id) { return unserialize(file_get_contents($this->getFilesFile($id))); }
	public function setIcon($icon,$id) {
		$file = $this->getIconDir()."/".$id;
		file_put_contents($file,$icon);
		return true;
	}
	public function getIconDir() {
		$path = $this->root."/icons";
		if(!file_exists($path)) mkdir($path);
		return $path;
	}
	
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
		touch($file);
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
			'D0_VERSION'=>"0.1",
			"D0_ROOT"=>"...",
			"D0_PKG_DIR"=>$this->getPkgDir($id)
		);
		$cwd = realpath( dirname($file) );
		$env = array_merge($env,$_ENV);
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
	
	public function web2id($url) { return parse_url($url,PHP_URL_HOST); }
	public function get($url,$d0i=false) {
		$content = file_get_contents($url);
		try{ 
			if($d0i) {
				$parser = new plistParser;
				$output = $parser->parseString($content);
				return array('data'=>$output->asArray(),'response'=>$content);
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
	public function version() {}
	
}