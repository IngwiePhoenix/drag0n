<?php
require_once 'Console/ProgressBar.php';

print "Showing elapsed time, and using ANSI codes:\n";
$bar = new Console_ProgressBar('- %fraction% [%bar%] %percent% Elapsed Time: %elapsed%', 
	'=>', '-', 70, 10, 
	array('ansi_terminal' => true, 'ansi_clear' => true));
for ($i = 0; $i <= 10; $i++) {
	$bar->update($i);
	usleep(30000);
}
print "\n";
