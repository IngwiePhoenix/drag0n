<?php
require_once 'Console/ProgressBar.php';

print "This will display a very simple bar:\n";
$bar = new Console_ProgressBar('%bar%', '=', ' ', 76, 3);
for ($i = 0; $i <= 3; $i++) {
    $bar->update($i);
    sleep(1);
}
print "\n";
