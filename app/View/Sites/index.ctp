<div class="sites index"></div>

<div class="col-md-5">
	<div class="search-section">
		<div class="input-group mb-3">
			<span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-magnifying-glass"></i></span>
			<input type="text" class="form-control" id="search_input" placeholder="Rechercher" aria-label="Search">
			<button class="btn btn-primary-rounded" type="button"><i class="fa-solid fa-magnifying-glass"></i>
				Rechercher
			</button>
		</div>
	</div>
</div>
<div class="col-md-12 filter-section"></div>
<div class="content-table">
	<table class="table table-akdital">
		<thead>
			<tr>
				<th>Ville</th>
				<th>Site</th>
				<th>Adresse</th>
				<th>Téléphone</th>
				<th>Mail</th>
				<th>Créé le</th>
				<th class="actions">Actions</th>
			</tr>

		</thead>
		<tbody>
			<?php foreach ($sites as $site): ?>
				<tr>
					<td><?php echo $site['Ville']['ville']; ?></td>
					<td><?php echo $site['Site']['site']; ?></td>
					<td><?php echo $site['Site']['adresse']; ?></td>
					<td><?php echo $site['Site']['telephone']; ?></td>
					<td><?php echo $site['Site']['mail']; ?></td>
					<td><?php echo $site['Site']['created']; ?></td>
					<td class="actions">
						<?php echo $this->Html->link(__('Voir'), array('action' => 'view', $site['Site']['id'])); ?> /
						<?php echo $this->Html->link(__('Modifier'), array('action' => 'edit', $site['Site']['id'])); ?> /
						<?php
						echo $this->Form->postLink(
							__('Supprimer'),
							['action' => 'delete', $site['Site']['id']],
							[
								'confirm' => __('Êtes-vous sûr de vouloir supprimer ce site ?')
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