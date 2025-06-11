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
<div class="<?php echo $pluralVar; ?> view">
	<div class="page-header">
		<h1 class="title-page"><?php echo "{$pluralHumanName}"; ?></h1>
		<span class="slogan"></span>
	</div>
	<div class="col-md-12 little-title-section">
		<span class="little-title"><?php echo "{$singularHumanName}"; ?></span>
		<div class="actions">
		</div>
	</div>


	<div class="card view-card">
		<div class="card-body">
			<div class="col-12">
				<div class="row row-gap-3">
					<?php
					foreach ($fields as $field) {
						// Skip primary key and 'modified' field
						if ($field === $primaryKey || $field === 'modified') {
							continue;
						}

						$isKey = false;
						if (!empty($associations['belongsTo'])) {
							foreach ($associations['belongsTo'] as $alias => $details) {
								if ($field === $details['foreignKey']) {
									$isKey = true;
									echo "<div class=\"col-md-3\">\n";
									echo "\t<div class=\"info\">\n";
									echo "\t\t<label>" . Inflector::humanize(Inflector::underscore($alias)) . "</label>\n";
									echo "\t\t<span>\n";
									echo "\t\t\t<?php echo \$this->Html->link(\${$singularVar}['{$alias}']['{$details['displayField']}'], array('controller' => '{$details['controller']}', 'action' => 'view', \${$singularVar}['{$alias}']['{$details['primaryKey']}'])); ?>\n";
									echo "\t\t</span>\n";
									echo "\t</div>\n";
									echo "</div>\n";
									break;
								}
							}
						}

						if ($isKey !== true) {
							echo "<div class=\"col-md-3\">\n";
							echo "\t<div class=\"info\">\n";
							echo "\t\t<label><?php echo __('" . Inflector::humanize($field) . "'); ?></label>\n";
							echo "\t\t<span><?php echo h(\${$singularVar}['{$modelClass}']['{$field}']); ?></span>\n";
							echo "\t</div>\n";
							echo "</div>\n";
						}
					}
					?>
				</div>
			</div>
		</div>
	</div>

</div>