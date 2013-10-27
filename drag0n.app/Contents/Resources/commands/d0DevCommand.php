<?php class d0DevCommand extends CConsoleCommand {

	public function actionIndex() {
		echo implode("\n",[
			"drag0n Developer toolkit 0.1",
			"",
			"Syntax: d0.dev [command] <parameter>",
			"",
			"Commands:",
			"    env           Set up a build environment, including binaries, libraries, headers, and more.",
			"    get           Get the developer tools that drag0n provides (Clang, make, autoconf, automake, ...)",
			"    update        Update the internal developer tools.",
			"    extract       Extract the whole build environment of drag0n into your default system paths.",
			"                  CAUTION: THIS WILL ERASE EXISTING FILES. WE RECOMMEND USING THE env-COMMAND ONLY! Only use this if you kow what's up.",
			"    resource      Manage your resources. To use this command, you may call it as: d0.dev resource [ create | prepair | update ]",
			"                  If you didn't specify a folder containing a drag0n resource, then we may use the current working directory",
		])."\n";
	}
}
