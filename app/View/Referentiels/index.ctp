<div class="referentiels index"></div>
<div class="page-header">
	<h1 class="title-page">Referentiels</h1>
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
																								<th>modele</th>
																				<th>duree</th>
																				<th>kilometrage</th>
																				<th>loyer_ht_ald</th>
																				<th>loyer_ttc_ald</th>
																				<th>km_supp_ht_ald</th>
																				<th>nb_pneus_ald</th>
																				<th>cat_remplacement_ald</th>
																				<th>loyer_ht_arval</th>
																				<th>loyer_ttc_arval</th>
																				<th>km_supp_ht_arval</th>
																				<th>nb_pneus_arval</th>
																				<th>cat_remplacement_arval</th>
																				<th>moyenne_offres_ttc</th>
																				<th>valeur_actuelle_moy_ttc</th>
																				<th>augmentation_pourcent</th>
																				<th>categorie</th>
													<th class="actions">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($referentiels as $referentiel): ?>
	<tr>
		<td><?php echo $referentiel['Referentiel']['modele']; ?></td>
		<td><?php echo $referentiel['Referentiel']['duree']; ?></td>
		<td><?php echo $referentiel['Referentiel']['kilometrage']; ?></td>
		<td><?php echo $referentiel['Referentiel']['loyer_ht_ald']; ?></td>
		<td><?php echo $referentiel['Referentiel']['loyer_ttc_ald']; ?></td>
		<td><?php echo $referentiel['Referentiel']['km_supp_ht_ald']; ?></td>
		<td><?php echo $referentiel['Referentiel']['nb_pneus_ald']; ?></td>
		<td><?php echo $referentiel['Referentiel']['cat_remplacement_ald']; ?></td>
		<td><?php echo $referentiel['Referentiel']['loyer_ht_arval']; ?></td>
		<td><?php echo $referentiel['Referentiel']['loyer_ttc_arval']; ?></td>
		<td><?php echo $referentiel['Referentiel']['km_supp_ht_arval']; ?></td>
		<td><?php echo $referentiel['Referentiel']['nb_pneus_arval']; ?></td>
		<td><?php echo $referentiel['Referentiel']['cat_remplacement_arval']; ?></td>
		<td><?php echo $referentiel['Referentiel']['moyenne_offres_ttc']; ?></td>
		<td><?php echo $referentiel['Referentiel']['valeur_actuelle_moy_ttc']; ?></td>
		<td><?php echo $referentiel['Referentiel']['augmentation_pourcent']; ?></td>
		<td><?php echo $referentiel['Referentiel']['categorie']; ?></td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $referentiel['Referentiel']['id'])); ?> / 
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $referentiel['Referentiel']['id'])); ?> /
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $referentiel['Referentiel']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $referentiel['Referentiel']['id']))); ?>
		</td>
	</tr>
<?php endforeach; ?>
		</tbody>
	</table>
</div>

</div>