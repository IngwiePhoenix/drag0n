<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>
<script>
	function sendMail(to) {
		return exec("open mailto:"+to);
	}
</script>
<?php $u=parse_url("http://furaffinity.net/view/10000000"); print_r(array_splice(explode("/",$u['path']), 1)); ?>
<h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>
<a href="#" onclick="sendMail('ingwie2000@gmail.com');">send mail</a>
<p>Congratulations! You have successfully created your Yii application.</p>

<p>You may change the content of this page by modifying the following two files:</p>
<ul>
	<li>View file: <code><?php echo __FILE__; ?></code></li>
	<li>Layout file: <code><?php echo $this->getLayoutFile('main'); ?></code></li>
</ul>

<p>For more details on how to further develop this application, please read
the <a href="http://www.yiiframework.com/doc/">documentation</a>.
Feel free to ask in the <a href="http://www.yiiframework.com/forum/">forum</a>,
should you have any questions.</p>
