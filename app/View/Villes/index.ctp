<div class="villes index"></div>

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
				<th>ville</th>
				<th class="actions">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($villes as $ville): ?>
				<tr>
					<td><?php echo $ville['Ville']['ville']; ?></td>
					<td class="actions">
						<?php echo $this->Html->link(__('Voir'), array('action' => 'view', $ville['Ville']['id'])); ?> /
						<?php echo $this->Html->link(__('Modifier'), array('action' => 'edit', $ville['Ville']['id'])); ?> /
						<?php
						echo $this->Form->postLink(
							__('Supprimer'),
							['action' => 'delete', $ville['Ville']['id']],
							[
								'confirm' => __('Êtes-vous sûr de vouloir supprimer cette ville ?')
							]
						);
						?>

					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

</div>