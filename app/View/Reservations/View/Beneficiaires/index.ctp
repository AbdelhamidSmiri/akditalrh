<div class="beneficiaires index"></div>
<div class="page-header">
	<h1 class="title-page">Beneficiaires</h1>
	<span class="slogan"></span>
</div>
<div class="col-md-5">
	<div class="search-section">
		<div class="input-group mb-3">
			<span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-magnifying-glass"></i></span>
			<input type="text" class="form-control" id="search_input" placeholder="Rechercher" aria-label="Search">
			<button class="btn btn-primary-rounded" type="button"><i class="fa-solid fa-magnifying-glass"></i>
				Rechercher</button>
		</div>
	</div>
</div>
<div class="col-md-12 filter-section"></div>
<div class="content-table">
	<table class="table table-akdital">
		<thead>
			<tr>
				<th>Site</th>
				<th>Affecter_par</th>
				<th>appartement_id</th>
				<th>nom</th>
				<th>mail</th>
				<th>Téléphone</th>
				<th>status</th>
				<th>etat</th>
				<th>date_debut</th>
				<th>date_fin</th>
				<th>note</th>
				<th>Date d'ajout</th>
				<th class="actions">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($beneficiaires as $beneficiaire): ?>
				<tr>
					<td>
						<?php echo $beneficiaire['Site']['site']; ?>
					</td>
					<td>
						<?php echo $beneficiaire['User']['nom']; ?>
					</td>
					<td>
						<?php echo $beneficiaire['Appartement']['nom']; ?>
					</td>
					<td><?php echo $beneficiaire['Beneficiaire']['nom']; ?></td>
					<td><?php echo $beneficiaire['Beneficiaire']['mail']; ?></td>
					<td><?php echo $beneficiaire['Beneficiaire']['telephone']; ?></td>
					<td><?php echo $beneficiaire['Beneficiaire']['status']; ?></td>
					<td><?php echo $beneficiaire['Beneficiaire']['etat']; ?></td>
					<td><?php echo $beneficiaire['Beneficiaire']['date_debut']; ?></td>
					<td><?php echo $beneficiaire['Beneficiaire']['date_fin']; ?></td>
					<td><?php echo $beneficiaire['Beneficiaire']['note']; ?></td>
					<td><?php echo $beneficiaire['Beneficiaire']['created']; ?></td>
					<td class="actions">
						<?php echo $this->Html->link(__('View'), array('action' => 'view', $beneficiaire['Beneficiaire']['id'])); ?>
						/
						<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $beneficiaire['Beneficiaire']['id'])); ?>
						/
						<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $beneficiaire['Beneficiaire']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $beneficiaire['Beneficiaire']['id']))); ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

</div>