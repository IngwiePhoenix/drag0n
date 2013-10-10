<?php
$apps = SpycObject(Yii::app()->Apple->getAppsString());
foreach($apps->Applications as $name => $app) { ?>
<div class="row entry"><ul style="list-style:none;">
	<li><b>Name</b>: <?=$name?></li>
	<li><b>Version</b>: <?=$app->Version?></li>
	<li><b>Location</b>: <?=$app->Location?></li>
</ul></div>
<?php } ?>