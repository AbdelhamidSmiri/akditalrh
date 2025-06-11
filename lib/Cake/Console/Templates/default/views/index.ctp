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
<div class="<?php echo $pluralVar; ?> index"></div>
<div class="page-header">
	<h1 class="title-page"><?php echo "{$pluralHumanName}"; ?></h1>
	<span class="slogan"></span>
</div>
<div class="col-md-5">
	<div class="search-section">
		<div class="input-group mb-3">
			<span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-magnifying-glass"></i></span>
			<input type="text" class="form-control" id="search_input" placeholder="Rechercher" aria-label="Search">
			<button class="btn btn-primary-rounded" type="button"><i class="fa-solid fa-magnifying-glass"></i> Rechercher</button>
		</div>
	</div>
</div>
<div class="col-md-12 filter-section"></div>
<div class="content-table">
	<table class="table table-akdital">
		<thead>
			<tr>
				<?php foreach ($fields as $field): ?>
					<?php if ($field !== $primaryKey && $field !== 'modified'): ?>
						<th><?php echo "{$field}"; ?></th>
					<?php endif; ?>
				<?php endforeach; ?>
				<th class="actions"><?php echo "Actions"; ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			echo "<?php foreach (\${$pluralVar} as \${$singularVar}): ?>\n";
			echo "\t<tr>\n";
			foreach ($fields as $field) {
				if ($field === $primaryKey || $field === 'modified') {
					continue;
				}
				$isKey = false;
				if (!empty($associations['belongsTo'])) {
					foreach ($associations['belongsTo'] as $alias => $details) {
						if ($field === $details['foreignKey']) {
							$isKey = true;
							echo "\t\t<td>\n\t\t\t<?php echo \$this->Html->link(\${$singularVar}['{$alias}']['{$details['displayField']}'], array('controller' => '{$details['controller']}', 'action' => 'view', \${$singularVar}['{$alias}']['{$details['primaryKey']}'])); ?>\n\t\t</td>\n";
							break;
						}
					}
				}
				if ($isKey !== true) {
					echo "\t\t<td><?php echo \${$singularVar}['{$modelClass}']['{$field}']; ?></td>\n";
				}
			}

			echo "\t\t<td class=\"actions\">\n";
			echo "\t\t\t<?php echo \$this->Html->link(__('View'), array('action' => 'view', \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?> / \n";
			echo "\t\t\t<?php echo \$this->Html->link(__('Edit'), array('action' => 'edit', \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?> /\n";
			echo "\t\t\t<?php echo \$this->Form->postLink(__('Delete'), array('action' => 'delete', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('confirm' => __('Are you sure you want to delete # %s?', \${$singularVar}['{$modelClass}']['{$primaryKey}']))); ?>\n";
			echo "\t\t</td>\n";
			echo "\t</tr>\n";

			echo "<?php endforeach; ?>\n";
			?>
		</tbody>
	</table>
</div>

</div>