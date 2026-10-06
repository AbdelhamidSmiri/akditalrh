<?php
$this->layout = 'error';

$code = (isset($error) && $error instanceof Exception) ? (int)$error->getCode() : 404;
$titles = array(
	400 => 'Requête invalide',
	401 => 'Authentification requise',
	403 => 'Accès refusé',
	404 => 'Page introuvable',
	405 => 'Méthode non autorisée',
);
$texts = array(
	401 => 'Veuillez vous connecter pour accéder à cette page.',
	403 => "Vous n'avez pas les droits nécessaires pour accéder à cette page.",
	404 => "La page demandée n'existe pas ou a été déplacée.",
);
if ($code < 400 || $code > 499) {
	$code = 400;
}
$title = isset($titles[$code]) ? $titles[$code] : 'Requête invalide';
$text = isset($texts[$code]) ? $texts[$code] : "La requête n'a pas pu être traitée.";

$this->assign('title', $title);
?>
<div class="code"><?php echo $code; ?></div>
<h1><?php echo h($title); ?></h1>
<p><?php echo h($text); ?></p>
<?php if (Configure::read('debug') > 0): ?>
	<div class="debug">
		<strong><?php echo h($message); ?></strong>
		<?php echo $this->element('exception_stack_trace'); ?>
	</div>
<?php endif; ?>
