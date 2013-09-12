<?php
require_once 'Console/ProgressBar.php';

print "A more complicated bar, showing current status in numbers as well:\n";
$bar = new Console_ProgressBar('%bar%', '=', ' ', 76, 3);
// let's assume we have a bar object we want to reuse
$bar->reset('* %fraction% [%bar%] %percent%', '=>', '-', 76, 7);
for ($i = 0; $i <= 7; $i++) {
    $bar->update($i);
    sleep(1);
}
print "\n";
