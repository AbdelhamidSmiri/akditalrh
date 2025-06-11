<?php

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       Cake.Console.Templates.default.views
 * @since         CakePHP(tm) v 1.2.0.5234
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
?>
<div class="<?php echo $pluralVar; ?> form">
	<?php echo "<?php echo \$this->Form->create('{$modelClass}'); ?>\n"; ?>
	<div class="page-header">
		<h1 class="title-page"><?php echo "{$singularHumanName}"; ?></h1>
		<span class="slogan"></span>
	</div>
	<div class="row">
		<div class="col"></div>
		<div class="col-8">

			<div class="row">
				<?php

				foreach ($fields as $field) {
					if (strpos($action, 'add') !== false && $field === $primaryKey) {
						continue;
					} elseif (!in_array($field, array('created', 'modified', 'updated'))) {
						echo "\t<div class='col-12'>\n";
						echo "\t<?php\n";
						echo "\t\techo \$this->Form->input('{$field}', array('placeholder'=>''));\n";
						echo "\t?>\n";
						echo "\t</div>\n";
					}
				}
				if (!empty($associations['hasAndBelongsToMany'])) {
					foreach ($associations['hasAndBelongsToMany'] as $assocName => $assocData) {
						echo "\t\techo \$this->Form->input('{$assocName}');\n";
					}
				}
				echo <<<EOD
				\t<div class='submit-section'>
\t<button type="submit" class="btn btn-submit">
\t\t<i class="fa-solid fa-paper-plane"></i> Envoyer
\t</button>
\t</div>
EOD;

				echo "<?php echo \$this->Form->end(); ?>\n";

				?>
			</div>
		</div>
		<div class="col"></div>


	</div>
</div>