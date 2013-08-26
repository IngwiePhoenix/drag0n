<?php 
	$shadow 		= "lime";
	$row 			= ".row";
	$verb 			= ".verb";
	$appDesc 		= "#appDesc";
	$infosplit 		= "#infosplit";
	$dscsplit 		= "#dscsplit";
	$isplitblock 	= ".splitblock";
	$psplitblock 	= "#splitblock";
	$psplitblockS	= "$psplitblock #splitS";
	$psplitblockB	= "$psplitblock #splitB";
?>
<?=WS(".centerpic")
	->right(auto)
	->left(auto)
	->display(block)
	->margin->left(auto)
	->margin->right(auto)
	#->zIndex(1)
	->text->align(center)
->end?>

<?=WS($row)
	->background->rgba(0,0,0, 0.7)
	->padding(5, 2, 5, 2)
->end?>

<?=WS($row.".large")
	->width("100%")
	->float(left)
	->position(relative)
	->display(block)
->end?>

<?=WS($row.".small")
	->width("70%")
	->position(relative)
	->display(block)
->end?>

<?=WS($row.".large")
	->color(white)
	->text->align(center)
->end?>

<?=WS($row.".button")
	->width(auto)
	->position(relative)
	->display(inlineBlock)
	->padding(10, 40, 10, 40)
	->borderRadius(20)
->end?>

<?=WS($verb)
	->width(55)
	->display(inlineBlock)
->end?>

<?=WS($verb."3")
	->width(300)
->end?>

<?=WS($appDesc)
	->position(relative)
	->margin->left(30)
	->margin->bottom(150)
->end?>

<?=WS($appdesc." #icon")
	->height->max(130)
	->width->max(130)
	->position(absolute)
->end?>

<?=WS($appdesc." #desc #entry")
	->padding->top(6)
	->padding->bottom(6)
	->background->rgba(0,0,0,0.0)
->end?>

<?=WS($appdesc." #desc")
	->width(600)
	->position(absolute)
	->left(60)
->end?>

<?=WS("$appDesc $row")
	->margin->left(140)
->end?>

<?=WS(".appIcon")
	->height->max(130)
	->width->max(130)
	->display(block)
->end?>

<?=WS($infosplit)
	->width(200)
	->position(absolute)
->end?>

<?=WS($dscsplit)
	->position(absolute)
	->width(auto)
	->left(250)
	->right(20)
->end?>

<?=WS($dscsplit." ul")
	->padding->left(30)
->end?>

<?=WS($dscsplit." h1")
	->margin->top(30)
->end?>

<?=WS($dscsplit." p")
	->margin->left(20)
->end?>

<?=WS(a)
	->text->decoration(none)
->end?>

<?=WS(".bigText")
	->width("99%")
	->height(200)
->end?>

<?=WS(".smallText")
	->width("96.5%")
	->height(210)
->end?>

<?=WS(".miniText")
	->width("96.5%")
	->height(70)
->end?>

<?=WS("#clearup")
	->clear(both)
	->padding->top(20)
->end?>

<?=WS("#clearup2")
	->clear(both)
	->padding->top(2)
	->padding->bottom(2)
->end?>

<?=WS("$psplitblock", ".splitblock")
	->width("100%")
	->position(relative)
->end?>

<?=WS("$psplitblock #split")
	->float(left)
	->width("49%")
	->margin->left(3)
	->margin->right(3)
	->position(relative)
->end?>

<?=WS($psplitblockS)
	->float(left)
	->width("18%")
	->border->style(solid)
	->border->right(1, solid, $shadow)
	->border->left(0, solid, transparent)
	->border->top(0)
	->border->bottom(0)
->end?>

<?=WS($psplitblockS."S")
	->float(left)
	->width("18%")
->end?>

<?=WS($psplitblockS." h4")
	->margin->top(5)
->end?>

<?=WS($psplitblockB)
	->float(left)
	->width("80%")
	->padding->left(10)
	->padding->top(5)
	->padding->right(10)
->end?>

<?=WS($psplitblockB." hr")
	->border->color($shadow)
->end?>

<?=WS(fieldset)
	->padding->left(5)
	->padding->right(5)
->end?>

<?=WS(code)
	->background(transparent)
->end?>