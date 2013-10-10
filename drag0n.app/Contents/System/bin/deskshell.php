<?php 

// Debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

// includes
include Contents."/System/lib/php/phpws/phpws/websocket.client.php";
include Contents."/System/lib/php/phpws/phpws/websocket.server.php";
include Contents."/System/lib/php/WebServer.php";

class Process extends Thread {
	public $proc;
	public $pipes=array();
	public $spec=array();
	public $programm;
	public $args;
	private $env;
	
	public function __construct($programm, array $args, array $env) { 
		$this->programm = $programm;
		$this->args = $args;
		$this->env=$env;
	}
	public function run() {
		$this->spec = array(
   			0 => array("pipe", "r"),
   			1 => array("pipe", "w"),
   			2 => array("pipe", "w")
		);

		$argStr = array(escapeshellcmd($this->programm));
		foreach($this->args as $arg) $argStr[] .= escapeshellarg($arg);
		$argStr = implode(" ", $argStr);

		$this->proc = proc_open(
			$argStr, 
			$this->spec, 
			$this->pipes, 
			dirname(__file__)
		);
	}
	
	public function stop() {
		foreach($this->pipes as $p) fclose($this->pipes[$p]);
		return proc_close($this->proc);
	}
}
class Server extends Thread {
	public $hasToDestruct=false;
	public function __construct($settings) {
		$this->htdocs = $settings['htdocs'];
		$this->defaultLocation = $settings['defaultLocation'];
		$this->port = $settings['port'];
		$this->glob = $settings['glob'];
		$this->stdout = $settings['stdout'];
	}
	public function run() {
		// Set up the HTTP server
		$server = new WebServer($this->port, "127.0.0.1");
		echo "[PHP-HTTP] Server listening on http://127.0.0.1:{$this->port}\n";
		echo "[PHP-HTTP] Server serving from {$this->htdocs}\n";

		// Deactivate headers!
		$server->setHeaders=false;
		
		// Copy the current GLOBALS, like $desk.
		$server->htdocs = $this->htdocs;
		$server->defaultLocation = $this->defaultLocation;
		$server->out = $this->stdout;
		
		$_ENV = $this->glob['_ENV'];
		$_SERVER = $this->glob['_SERVER'];
		if(!isset($_SERVER['HTTP_COOKIE'])) $_SERVER['HTTP_COOKIE']="deskshell=true;d0=true";

		// Set up request handler
		$server->handleRequests("Request", "send");
	}
}
class Request {
	public function send($WebServer) {
		// Decide which file to serve.
		$uri = $_SERVER['SCRIPT_URL'];
		$htdocs = $WebServer->htdocs;
		$defLoc = $WebServer->defaultLocation;
		$path = ( $uri=="/" ? $htdocs.$uri.$defLoc : $htdocs.$uri);
		$file = realpath($path);
		$realFile=null;
		$code=200;
		$qarr = explode("&", $_SERVER['QUERY_STRING']);
		$qst = implode(" ", $qarr); 
		fwrite($WebServer->out, "[PHP-HTTP] GET $uri | $path\n");
		fwrite($WebServer->out, "[PHP-HTTP] QUERY $qst\n");
		if($file) {
			fwrite($WebServer->out, "[PHP-HTTP] Trying to load $path\n");
			$realFile=$file;
		} else {
			fwrite($WebServer->out, "[PHP-HTTP] Loading ".Contents."/errdoc/404.html\n");
			$realFile=Contents."/errdoc/404.html";
			$code=404;
		}
		$mt = mime_content_type($realFile);
		fwrite($WebServer->out, "[PHP-HTTP] Mime-Type: $mt\n");
		list($type, $stype)=explode("/",$mt);
		if($type == "text") {
			$out = shell_exec(implode(" ",[
				escapeshellcmd(PHP_BINARY."-cgi"), 
				"-q",
				escapeshellarg($realFile),
				$qst
			]));
			echo $out;
		} else {
			echo file_get_contents($realFile);
		}
		return $code;
	}
}
class Browser extends Thread {
	public $crport;
	public $htport;
	public $message;
	public $desk;
	private $glob=array();
	
	public function __construct($crport, $htport, $desk, array $glob) {
		$this->htport = $htport;
		$this->crport = $crport;
		$this->desk = $desk;
		$this->glob=$glob;
	}
	public function run() {
		$_ENV = $this->glob['_ENV'];
		$_SERVER = $this->glob['_SERVER'];
		$desk = $this->desk;
		$chrome = realpath(dirname(__file__)."/../../MacOS/Chromium");
		$args = [
			($desk->mode=="app"
				?"--app"
				:"--".$desk->mode
			)."=http://localhost:".$this->htport."/",
			"--remote-debugging-port=".$this->crport,
			"--user-data-dir=".dirname(__file__)."/../chrome_profile",
			"--disable-translate",
			"--app-window-size=1024,800"
		]; 
		
		// Fire off the process
		$chr = new Process($chrome, $args, $_ENV);
		if(!$chr->start()) die("Can not start chromium!\n");

		// We need a web-socket. So we gonna see if we can have one
		$appUrl = "http://localhost:".$this->htport."/";
		$ws=null; $try=0;
		echo "[Chromium] Remote Debugging Host: http://localhost:".$this->crport."\n";
		while(true) {
			$c = curl_init("http://localhost:".$this->crport."/json");
			curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
			$fgc = curl_exec($c);
			unset($c);
			if($fgc != false) {
				// Try to json_decode
				$json = json_decode($fgc, true);
				if(is_array($json)) {
					foreach($json as $k=>$o) {
						if($o['url']==$appUrl) {
							$ws=$o['webSocketDebuggerUrl'];
							break 2; // OUT OF THE LOOP.
						}
					}
				}
			} else sleep(1);
		}
		echo "[Chromium] Remote Debugging WebSocket: $ws \n";
		// Phew...fire off a new websocket...now.
		$ws=new WebSocket($ws);
		if($ws->open()) echo "[WebSocket Client] Opened successfuly.\n";
		
		// Sending standart message
		$ws->send('{"id":'.mt_rand(1,100).',"method":"Console.enable"}');

		// Event loop - in php :)
		$inLoop=false;
		while(true) {
			if(!$inLoop) {
				echo "[WebSocket Client] Entered event loop.\n";
				$inLoop=true;
			}

			// Get latest message...or wait for one.
			$msg = $ws->readMessage();

			// Events
			$o = json_decode($msg->getData());
			if(isset($o->method)) {
				echo "[WebSocket Client] Raising event with method: ".$o->method."\n";
				if( // {"method":"Inspector.detached","params":{"reason":"target_closed"}}
					$o->method == "Inspector.detached" 
					&& $o->params->reason=="target_closed"
				) { 
					echo "[WebSocket Client] Browser/WebSocket was closed.\n";
					$this->server->pop(); # Tell the server to shut down. To do this...we cause a SegFault. Looking for better solution.
					exit(0); 
					break;
				}
				
				if($o->method == "Console.messageAdded") {
					echo "[Browser console] ".$o->params->message->text."\n";
				}
			}
			
			// If there is a message to be send, send it.
			if(!is_null($this->message)) {
				$this->ws->send($this->message);
				$this->message=null;
			}
		}
	}
}

class Event {
	public $callbacks = array();
	public static function on($event, $callback) {
		$this->callbacks[$event] = $callback;
	}
	public static function raise($event, $params) {
		echo "[Event] Raising...\n";
		if(isset($this->callbacks[$event])) {
			return $this->callbacks[$event]($params);
		}
	}
}

class Deskshell {
	public static function main($desk, $appDir) {
	
		// Weird launching issues tracking
		if(empty($_SERVER)) die("It's empty in _SERVER\n");
		if(empty($_ENV)) die("It's empty in _SERVER\n");
	
		$htdocs = realpath($appDir."/".$desk->htdocs);
		$htport = freeport();
		$crport = freeport($htport);
		
		// Start server THEN browser.
		// The server needs a few more settings (obviously) as the browser.
		// Since the server will more or less completely de-atach itself once upon the Request class is being used, we pass additional parameters.
		// The important ones are htdocs, defaultLocation, Port and stdout. the Globals are only pushed over in case we need environment variables etc.
		// The stream to STDOUT is used to attach log messages to the current process.
		echo "[Deskshell PHP] Starting Webserver...\n";
		$server = new Server([
			"htdocs"=>$htdocs,
			"defaultLocation"=>$desk->defaultLocation,
			"port"=>$htport,
			"glob"=>$GLOBALS,
			"stdout"=>fopen('php://stdout', 'w')
		]);
		$server->start();

		echo "[Deskshell PHP] Starting Browser...\n";
		$browser = new Browser($crport, $htport, $desk, $GLOBALS);
		$browser->start();
		
		// Crossing the threads
		$server->browser = $browser;
		$browser->server = $server;
		
		// Wait untill the two other threads are up.
		// This is subject to change, since threads could set an isRunning property...
		// ...but lazyness wins.
		sleep(2);
		
		// Include the main script
		echo "[Deskshell PHP] Running main script at: ".realpath($appDir."/".$desk->main)."\n";
		#include_once $appDir."/".$desk->main;
		exitApp(0);
	}
}

// Find a free port to use
function freeport($no=null) {
	// Create a fake socket
	$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
	$p=0;
	while($maybePort = mt_rand(1024,9999)) {
		$p = @socket_bind($sock, "127.0.0.1", $maybePort);
		if($no != $maybePort && $p) break;
	}
	socket_close($sock);
	return $maybePort;
}

function exitApp($code) {
	// Somehow get all the threads, and then somehow kill them all.
	exit($code);
}