<?php class d0ConfigCommand extends CConsoleCommand {

	public $lib_path;

	public function run($args) {
		if(empty($args)) return $this->help();
		$me = realpath(dirname(__file__).'/../../System');
		$o = [];
		switch($args[0]) {
			case '--lib-path':
				$o[] = realpath("$me/lib");
				$o[] = realpath("$me/usr/lib");
				$o[] = realpath("$me/usr/local/lib");
				echo implode(':',$o);
			break;
			case '--inc-path':
				$o[] = realpath("$me/usr/local/include");
				echo implode(':',$o);
			break;
			case '--bin-path':
				$o[] = realpath("$me/bin");
				$o[] = realpath("$me/usr/bin");
				$o[] = realpath("$me/usr/local/bin");
				$o[] = realpath("$me/usr/sbin");
				echo implode(':',$o);
			break;
			case '--core':
				echo realpath("$me/../Core");
			break;
			case '--include-d0':
				echo realpath("$me/../Core/d0.php");
			break;
			case '--platforms':
				echo realpath("$me/../Core/Platform");
			break;
			case '--installers':
				echo realpath("$me/../Core/Installer");
			break;
			case '--packages':
				echo realpath("$me/../Core/Package");
			break;
			case '--bundle':
				echo realpath("$me/..");
			break;
			case '--system':
				echo $me;
			break;
		}
	}
	
	public function help() {
		echo implode("\n",[
			"drag0n configuration 0.1",
			"Usage: d0-config [switch]",
			"",
			"Switches:",
			"    --lib-path    Output a vaild LIBRARY_PATH",
			"    --inc-path    Output a vaild INCLUDE_PATH",
			"    --bin-path    Output a vaild PATH",
			"    --core        Output the core folder",
			"    --include-d0  Show path to the includable drag0n singleton",
			"    --platforms   Show all available platforms",
			"    --packages    Show all available package modules",
			"    --installers  Show all available installer modules",
			"    --bundle      Output path to bundle",
			"    --system      Output full path to System folder"
		])."\n";
	}
	
}