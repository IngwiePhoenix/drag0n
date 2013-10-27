<?php class d0Command extends CConsoleCommand {
	
	public function actionIndex() {
		d0(d0::QUIET);
		var_dump(d0(d0::CONFIG_FILE)->return);
		$messages = [
			"drag0n Installer CLI 0.1",
			"",
			"Usage: d0 [command] [sub-command] <arg, arg, arg, ...>",
			"",
			"Available commands with possible sub-commands (in brackets) and parameter (in greater-/smaller signs):",
			"    - install   [ file | url | id ] <installable>",
			"    - uninstall <id>",
			"    - resource  [ add | remove | show ] <ID or URL>",
			"    - pkg       [ search | download ] <id>",
			"    - version",
			"    - more"
		];
		echo implode("\n",$messages)."\n";
    }

}