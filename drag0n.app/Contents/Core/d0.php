<?php class d0 {

	/*
		This is the class that the d0() function will always reffer to. Really, always. It's the core, after all.
	*/
	
	// Constants
	const CONFIG=0x1;
	const CONFIG_FILE=0x2;
	const DEV=0x3;
	const SYSTEM=0x4;
	const HOME=0x5;
	const QUIET=0x6;
	
	// First, some default configuration
	public $installer="drag0n";
	public $package="d0p";
	public $platform;
	public $operatingsystem;
	public $return; # Covers the configuration's result.
	public $quiet=false;  # Shuts up the output. Used in d0()->log
	public $configuration=array();
		
	// The hidden class instances
	private $c_installer;
	private $c_platform;
	private $c_operatingsystem;
	private $c_package;
	
	// Singleton syntax
	private static $instance=null;
	public static function instance() {
		if(!self::$instance) self::$instance = new self(func_get_args());
		// Here comes the ultra-mix syntax
		call_user_func_array([self::$instance, 'init'], func_get_args());
		$ref =& self::$instance;
		return $ref;
	}
	
	// Due to singleton syntax, let's make this un-instantiable.
	private function __construct($args=array()) {
		// If we were instantiated with the QUIET flag, we go quiet.
		$_argv=( isset($args[0]) ? $args[0] : $args);
		$_argc=count($_argv);
		if($_argc===1 && $_argv===self::QUIET) $this->quiet=true;

		$this->log("Setting up drag0n's core");
		// Configure our usual platform first.
		$this->setOS(php_uname('s'));
		$this->setPlatform(php_uname('s'));
		$this->setPackage($this->package);
		
		$this->log('Loading configuration');
		$this->config();
	}
	
	// magic methods
	public function __set($key, $value) {
		$fnc = 'set'.ucfirst($key);
		if(method_exists($this, $func)) {
			call_user_func([$this,$func], $value);
		} else {
			$this->$key=$value;
		}
	}
	public function __get($key) {
		$func = 'get'.ucfirst($key);
		if(method_exists($this, $func)) {
			return call_user_func([$this,$func]);
		} elseif(isset($this->$key)) {
			return $this->$key;
		} else trigger_error("__get($key | $func) failed - key does not exist.");
	}
	public static function __callStatic($func, $args) { trigger_error("You are not ment to call this class statically. use d0() instead."); }
	public function __call($func, $args) {
		// Funkabunking our calls.
		if(method_exists($this->c_installer, $func)) return call_user_func_array([$this->c_installer, $func], $args);
		if(method_exists($this->c_platform, $func)) return call_user_func_array([$this->c_platform, $func], $args);
		if(method_exists($this->c_operatingsystem, $func)) return call_user_func_array([$this->c_operatingsystem, $func], $args);
		if(method_exists($this->c_package, $func)) return call_user_func_array([$this->c_package, $func], $args);
		trigger_error("Fatal error: Trying to call undefined method $func in the drag0nCore scope", E_USER_ERROR);
	}
	public function __clone() { die("You can not clone this!\n"); }
	
	// Ultra-magic method
	public function init(/*mixed..like really mixed.*/) {
		$args = func_get_args();
		$argc = count($args);

		// Case 1: the user supplied a configuration array.
		// I.e.: d0(['with'=>'minecraft', 'jar'=>'path/to/minecraft.jar'])
		if($argc === 1 && is_array($args[0])) {
			$conf = $args[0];
			if(isset($conf['with'])) {
				// Change platform. (with: minecraft)
				$this->platform = $conf;
			} elseif(isset($conf['use'])) {
				// Change Installer (use: APT)
				$this->installer = $conf;
			} elseif(isset($conf['target'])) {
				// Change Installer (use: APT)
				$this->package = $conf;
			} else {
				// Configure other things
				foreach($conf as $name=>$val) {
					// If the value is an array, we're configuring stuff in the existing configuration.
					if(is_array($cv)) {
						foreach($cv as $ckey => $cval) {
							$this->config($ckey, $cval, $name);
						}
					} else $this->$name=$val;
				}
			}			
		}
		
		// Case 2: The user supplied a key and value
		// I.e.: d0('with', 'APT')
		if($argc === 2 && is_string($args[0]) && is_string($args[1])) {
			list($key, $val) = $args;
			$this->return = $this->__run([$key=>$val]);
		}
		
		// Case 3: User just supplied one single string. Return the configuration value - now this can come from even the GUI config file.
		// I.e.: d0('developer')
		if($argc === 1 && is_string($args[0])) {
			$this->return = $this->config($args[0]);
		}

		// Case 4: User supplied an integer. It must be one of our constants - go and check.
		// I.e.: d0(d0::CONFIG_FILE)
		if($argc === 1 && is_int($args[0])) {
			switch($args[0]) {
				case self::CONFIG:
					$this->return = $this->config();
				break;
				case self::CONFIG_FILE:
					$this->return = $this->configFile;
				break;
				case self::DEV:
					$this->return = $this->developer;
				break;
				case self::SYSTEM:
					$this->return = realpath(dirname(__file__).'/../System');
				break;
				case self::HOME:
					$this->return = realpath(dirname(__file__).'/..');
				break;
				case self::QUIET:
					$this->return = $this->quiet = true;
				break;
			}
		}
	}
	
	// Installer/platform setup
	private function setup($key, $type, $conf) {
		$key = (is_array($conf) ? $conf[$key] : $conf);
		$uc_type = $type;
		$type = strtolower($type);
		$type2 = 'c_'.strtolower($type);

		$prefix=null;
		switch($uc_type) {
			case 'OperatingSystem': $prefix="OS_"; break;
			case 'Platform': $prefix="P_"; break;
		}
		$key = $prefix.$key;
		$implements = class_implements($key);
		if(in_array($uc_type, $implements)) {
			$this->log("Creating new instance with $key");
			$this->$type = $key;
			$this->$type2 = new $key($conf);
		} else trigger_error("Unsupported < $type > interface: $key");
	}
	private function setInstaller($conf) { $this->setup('use', 'Installer', $conf); }
	private function setPlatform($conf) { $this->setup('with', 'Platform', $conf); }
	private function setPackage($conf) { $this->setup('target', 'Package', $conf); }
	private function setOS($conf) { $this->setup('on', 'OperatingSystem', $conf); }
	private function getConfigFile() {
		$home = $_ENV['HOME'];
		$user = "$home/.d0rc";
		if(file_exists($user)) { 
			$this->return = $user;
		} else { 
			$this->return = null;
		}
		return $this->return;
	}
	
	public function log($message) {
		if($this->quiet == false) {
			$str='[drag0n/core] '.$message;
			if(isset($_ENV['TERM']) && $_ENV['TERM']!='dumb') Terminal::log($str);
			else Terminal::stdout($str."\n");
		}
	}
	
	private function config($key=null, $value=null, $category='Generall') {
		$os = $this->operatingsystem;
		$cfp = $os::configurationFilePath();
		$home = $_ENV['HOME'];
		$conf = [];

		// Global config file
		$file = realpath( dirname(__file__)."/../System/var/d0/d0rc" );
		$this->log("Loading global settings from the drag0n internals: $file");
		if(file_exists($file)) { $global = Spyc::YAMLLoad($file); }
		// System-wide
		$file = realpath("$cfp/d0rc");
		if(file_exists($file)) { $conf = Spyc::YAMLLoad($file); }
		// User specific
		$file = realpath("$home/.d0rc");
		if(file_exists($file)) { $conf = Spyc::YAMLLoad($file); }
		
		// merge
		$conf = array_merge($global, $conf);
		
		if(!is_null($category)) $conf2 &= $conf[$category];
		
		if($key==null && $value==null) {
			$this->return = $conf2;
		} elseif($key != null && $value==null) {
			$this->return = $conf2[$key];
		} elseif($key != null && $value != null) {
			$conf2[$key]=$value;
		}
		
		$this->configuration=$conf;
		
		// Write
		$this->log("Saving configuration to: $home/.d0rc");
		$res = file_put_contents(
			"$home/.d0rc",
			Spyc::YAMLDump($conf)
		);
		if(is_null($res)) trigger_error("Was not able to write configuration file to $home/.d0rc");
		
		return $this->return;
	}
	
	public function version() { echo "drag0n PHP-API 0.1\n"; }

} function d0(/* mixed $everything */) { return call_user_func_array(['d0','instance'], func_get_args()); }

# This is a musthave if we aren't running off Yii, but the sore core.
spl_autoload_register(function($class){
	$me=dirname(__file__);
	$file="$class.php";
	$dirs=[
		'Platform',
		'Installer',
		'OS',
		'Package',
		'Interfaces',
		'.',
		'Utils/Spyc'
	];
	foreach($dirs as $f) {
		$cf="$me/$f/$file";
		if(file_exists($cf)) include_once $cf;
	}
});