<?php
ini_set("memory_limit","1024M");
$dod2="/Users/Ingwie/Music/Drakengard 2";
$data=[];
foreach(glob("$dod2/*") as $f) {
	echo "> Adding $f\n";
	$d=[];
	array_merge($d,pathinfo($f));
	$d['DATA']=file_get_contents($f);
	$data[$f]=$d;
}
$ostr = serialize(["meta"=>"data"]);
$str = base64_encode(serialize($data));
$gzstr = gzencode($str);
file_put_contents("./test.d0p", $ostr."\n".$gzstr);
file_put_contents("./real.d0p", $ostr."\n".$str);
system("du -h ./*.d0p");
unset($str);
unset($gzstr);

$file="./real.d0p";
$linecount1 = 0;
$handle = fopen($file, "r");
while(!feof($handle)){
  $line = fgets($handle);
  $linecount1++;
}
fclose($handle);

$file="./test.d0p";
$linecount2 = 0;
$handle = fopen($file, "r");
while(!feof($handle)){
  $line = fgets($handle);
  $linecount2++;
}
fclose($handle);

echo "Real file: $linecount1 | Test file: $linecount2";