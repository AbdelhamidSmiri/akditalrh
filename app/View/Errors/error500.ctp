<?php
$this->layout = 'error';
$this->assign('title', 'Erreur interne');
?>
<div class="code">500</div>
<h1>Erreur interne</h1>
<p>Une erreur inattendue est survenue. Veuillez réessayer plus tard.</p>
<?php if (Configure::read('debug') > 0): ?>
	<div class="debug">
		<strong><?php echo h($message); ?></strong>
		<?php echo $this->element('exception_stack_trace'); ?>
	</div>
<?php endif; ?>
