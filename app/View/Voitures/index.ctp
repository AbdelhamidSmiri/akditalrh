<div class="voitures index"></div>
<div class="page-header">
	<h1 class="title-page">Voitures</h1>
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
																								<th>user_id</th>
																				<th>ste_location</th>
																				<th>immatriculation</th>
																				<th>marque</th>
																				<th>modele</th>
																				<th>valeur_locative_ht</th>
																				<th>duree_mois</th>
																				<th>km</th>
																				<th>cout_km_suplm_ttc</th>
																				<th>nbr_pneus</th>
																				<th>service_link</th>
																				<th>categorie_vr</th>
																				<th>livraison</th>
																				<th>fin_contrat</th>
																				<th>km_actuel</th>
																				<th>created</th>
																						<th class="actions">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($voitures as $voiture): ?>
	<tr>
		<td>
			<?php echo $this->Html->link($voiture['User']['nom'], array('controller' => 'users', 'action' => 'view', $voiture['User']['id'])); ?>
		</td>
		<td><?php echo $voiture['Voiture']['ste_location']; ?></td>
		<td><?php echo $voiture['Voiture']['immatriculation']; ?></td>
		<td><?php echo $voiture['Voiture']['marque']; ?></td>
		<td><?php echo $voiture['Voiture']['modele']; ?></td>
		<td><?php echo $voiture['Voiture']['valeur_locative_ht']; ?></td>
		<td><?php echo $voiture['Voiture']['duree_mois']; ?></td>
		<td><?php echo $voiture['Voiture']['km']; ?></td>
		<td><?php echo $voiture['Voiture']['cout_km_suplm_ttc']; ?></td>
		<td><?php echo $voiture['Voiture']['nbr_pneus']; ?></td>
		<td><?php echo $voiture['Voiture']['service_link']; ?></td>
		<td><?php echo $voiture['Voiture']['categorie_vr']; ?></td>
		<td><?php echo $voiture['Voiture']['livraison']; ?></td>
		<td><?php echo $voiture['Voiture']['fin_contrat']; ?></td>
		<td><?php echo $voiture['Voiture']['km_actuel']; ?></td>
		<td><?php echo $voiture['Voiture']['created']; ?></td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $voiture['Voiture']['id'])); ?> / 
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $voiture['Voiture']['id'])); ?> /
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $voiture['Voiture']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $voiture['Voiture']['id']))); ?>
		</td>
	</tr>
<?php endforeach; ?>
		</tbody>
	</table>
</div>

</div>