<h2>Tableau de bord des Appartements</h2>

<div class="row">
	<div class="col-md-4 alert alert-info">Total Appartements : <strong><?= $global['total_appartements'] ?></strong>
	</div>
	<div class="col-md-4 alert alert-success">Appartements Pleins : <strong><?= $global['total_ocupes'] ?></strong>
	</div>
	<div class="col-md-4 alert alert-warning">Places Disponibles : <strong><?= $global['total_places_dispo'] ?></strong>
	</div>
</div>

<hr>

<h3>📊 Statistiques par Ville et Sexe</h3>
<table class="table table-bordered">
	<thead>
		<tr>
			<th>Ville</th>
			<th>Sexe</th>
			<th>Total Appartements</th>
			<th>Appartements Pleins</th>
			<th>Places Disponibles</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($stats as $ville => $bySexe): ?>
			<?php foreach ($bySexe as $sexe => $info): ?>
				<tr>
					<td><?= h($ville) ?></td>
					<td><?= h($sexe) ?></td>
					<td><?= $info['total'] ?></td>
					<td><?= $info['ocupes'] ?></td>
					<td><?= $info['places_dispo'] ?></td>
				</tr>
			<?php endforeach; ?>
		<?php endforeach; ?>
	</tbody>
</table>

<hr>

<h3>📋 Détails par Appartement</h3>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Nom</th>
			<th>Ville</th>
			<th>Sexe</th>
			<th>Capacité</th>
			<th>Occupants</th>
			<th>Places Dispo</th>
			<th>#</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($appart_info as $a): ?>
			<tr>
				<td><?= h($a['nom']) ?></td>
				<td><?= h($a['ville']) ?></td>
				<td><?= h($a['sexe']) ?></td>
				<td><?= $a['capacite'] ?></td>
				<td><?= $a['ocupants'] ?></td>
				<td><?= $a['places_dispo'] ?></td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $a['id'])); ?>
					/
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $a['id'])); ?>
					/
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $a['id']), array('confirm' => __('Are you sure you want to delete # %s?', $a['id']))); ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>