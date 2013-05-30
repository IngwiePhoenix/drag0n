<?php
	$packages = array(
		array(
			"name"=>"Drag0n Update",
			"version"=>"1.0",
			"category"=>"drag0n (system)"
		),
		array(
			"name"=>"NoSleep",
			"version"=>"2.0-1b",
			"category"=>"System"
		),
		array(
			"name"=>"RepoMaker",
			"version"=>"2.0",
			"category"=>"Creativity / iOS / Cydia"
		)
	);

foreach($packages as $pkg) { ?>
<div class="row list">
	<div><span class="verb3">Name:</span> <?=$pkg['name']?></div>
	<div><span class="verb3">Version:</span> <?=$pkg['version']?></div>
	<div><span class="verb3">Category:</span> <?=$pkg['category']?></div>
</div><br>
<?php } ?>
