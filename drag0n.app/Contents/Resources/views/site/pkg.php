<?php 
	include "helper.php";
	$info = $model->Info;
	$tmpFile = tfile();
	file_put_contents($tmpFile['path'], base64_decode($model->Info->icon));
	$plattforms = array(); foreach($info->plattforms as $v) { $plattforms[]=$v; } $info->plattforms = $plattforms;
?>
<div id="appDesc">
	<div id="icon"><img src="<?=$tmpFile['url']?>" class="appIcon"></div>
	<div id="desc">
		<div class="row small list" id="entry">
			<span class="verb" style="width:100px;">Name</span>
			<?=$info->name?>
		</div>
		<div class="row small list" id="entry">
			<span class="verb" style="width:100px;">Version</span>
			<?=$info->version?>
		</div>
		<div class="row small list" id="entry">
			<span class="verb" style="width:100px;">Category</span>
			<?=$info->category?>
		</div>
		<div class="row small list" id="entry">
			<span class="verb" style="width:100px;">OS</span>
			<?=implode(", ",$info->plattforms)?>
		</div>
	</div>
</div>
<div class="row button"><a class="AJAX" href="<?=CHtml::normalizeUrl(array("site/list"))?>">INSTALL (1MB)</a></div>

<div id="clearup" style="height: 70px;"></div>

<div id="infosplit" class="row small list">
	<span class="verb2">Author:</span>
	<a href="mailto:<?=$info->author->email?>"><?=$info->author->name?></a>
	<hr>
	<span class="verb2">Note:</span>
	<?=$info->note?>
	<hr>
	<span class="verb2">ID:</span>
	<?=$info->id?>
	<hr>
	<span class="verb2">Depencies:</span>
	<ul><?php foreach($info->deps as $dkey=>$dval) { ?>
		<li><?=$dkey?>: <?=$dval?></li>
	<?php } ?></ul>
	<hr>
	<span class="verb2">Collisions:</span>
	<ul><?php foreach($info->collision as $ckey=>$cval) { ?>
		<li><?=$ckey?>: <?=$cval->version?><br><?=$cval->reason?></li>
	<?php } ?></ul>
	<hr>
	<span class="verb2">Installing depencies:</span>
	<?php if($info->instDeps) echo "Yes."; else echo "No."; ?>
	<hr>
	<span class="verb2">Uninstall collisions:</span>
	<?php if($info->uninstColls) echo "Yes."; else echo "No."; ?>
	<hr>
</div>
<div id="dscsplit" class="row large">
	<h1>Description</h1>
	<p><?=$info->description->content?></p>
	<h2>Features</h2>
	<ul><?php foreach($info->description->features as $ft) {?>
		<li><?=$ft?></li>
	<?php } ?></ul>
</div>
