<!DOCTYPE html>
<html lang="fr">
<head>
<?php echo $this->Html->charset(); ?>
<title><?php echo h($pageTitle); ?></title>
<link rel="icon" type="image/png" href="<?php echo $this->webroot; ?>img/favicon.png">

<?php
if (Configure::read('debug') == 0):
	echo sprintf('<meta http-equiv="Refresh" content="%s;url=%s" />', $pause, $url);
endif;
?>
<style><!--
P { text-align:center; font:bold 1.1em sans-serif }
A { color:#444; text-decoration:none }
A:HOVER { text-decoration: underline; color:#44E }
--></style>
</head>
<body>
<p>
	<?php echo $this->Html->link($message, $url); ?>
</p>
</body>
</html>
