<div class="appartements view">
	<div class="page-header">
		<h1 class="title-page">Appartements</h1>
		<span class="slogan"></span>
	</div>
	<div class="col-md-12 little-title-section">
		<span class="little-title">Appartement</span>
		<div class="actions">
		</div>
	</div>


	<div class="card view-card">
		<div class="card-body">
			<div class="col-12">
				<div class="row row-gap-3">
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Nom'); ?></label>
							<span><?php echo h($appartement['Appartement']['nom']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('sexe'); ?></label>
							<span><?php echo h($appartement['Appartement']['sexe']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Capacite'); ?></label>
							<span><?php echo h($appartement['Appartement']['capacite']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Ville'); ?></label>
							<span><?php echo h($appartement['Appartement']['ville']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Adresse'); ?></label>
							<span><?php echo h($appartement['Appartement']['adresse']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Images'); ?></label>
							<span><?php echo h($appartement['Appartement']['images']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Created'); ?></label>
							<span><?php echo h($appartement['Appartement']['created']); ?></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="col-md-12 filter-section">
	La liste des bénéficiaires affectés à cet appartement en cours
</div>
<div class="content-table">
	<table class="table table-akdital">
		<thead>
			<tr>
				<th>Site</th>
				<th>Affecté par</th>
				<th>nom</th>
				<th>mail</th>
				<th>telephone</th>
				<th>status</th>
				<th>etat</th>
				<th>date_debut</th>
				<th>date_fin</th>
				<th>note</th>
				<th>Date</th>
				<th class="actions">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$beneficiaires = $appartement["Beneficiaire"];
			foreach ($beneficiaires as $beneficiaire):
				if ($beneficiaire['etat'] == 'Valider' || $beneficiaire['etat'] == 'En cours'):
					?>
					<tr>
						<td><?php echo $sites[$beneficiaire['site_id']]; ?></td>
						<td><?php echo $users[$beneficiaire['user_id']]; ?></td>
						<td><?php echo $beneficiaire['mail']; ?></td>
						<td><?php echo $beneficiaire['telephone']; ?></td>
						<td><?php echo $beneficiaire['status']; ?></td>
						<td><?php echo $beneficiaire['etat']; ?></td>
						<td><?php echo $beneficiaire['date_debut']; ?></td>
						<td><?php echo $beneficiaire['date_fin']; ?></td>
						<td><?php echo $beneficiaire['note']; ?></td>
						<td><?php echo $beneficiaire['created']; ?></td>
						<td><?php echo $beneficiaire['nom']; ?></td>
						<td class="actions">
							<?php echo $this->Html->link(__('View'), array('action' => 'view', $beneficiaire['id'])); ?>
							/
							<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $beneficiaire['id'])); ?>
							/
							<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $beneficiaire['id']), array('confirm' => __('Are you sure you want to delete # %s?', $beneficiaire['id']))); ?>
						</td>
					</tr>
					<?php
				endif;
			endforeach; ?>
		</tbody>
	</table>
</div>


<div class="col-md-12 filter-section">
	Historique des  affectectations à cet appartement
</div>
<div class="content-table">
	<table class="table table-akdital">
		<thead>
			<tr>
				<th>Site</th>
				<th>Affecté par</th>
				<th>nom</th>
				<th>mail</th>
				<th>telephone</th>
				<th>status</th>
				<th>etat</th>
				<th>date_debut</th>
				<th>date_fin</th>
				<th>note</th>
				<th>Date</th>
				<th class="actions">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$beneficiaires = $appartement["Beneficiaire"];
			foreach ($beneficiaires as $beneficiaire):
				if ($beneficiaire['etat'] != 'Valider' && $beneficiaire['etat'] != 'En cours'):
					?>
					<tr>
						<td><?php echo $sites[$beneficiaire['site_id']]; ?></td>
						<td><?php echo $users[$beneficiaire['user_id']]; ?></td>
						<td><?php echo $beneficiaire['mail']; ?></td>
						<td><?php echo $beneficiaire['telephone']; ?></td>
						<td><?php echo $beneficiaire['status']; ?></td>
						<td><?php echo $beneficiaire['etat']; ?></td>
						<td><?php echo $beneficiaire['date_debut']; ?></td>
						<td><?php echo $beneficiaire['date_fin']; ?></td>
						<td><?php echo $beneficiaire['note']; ?></td>
						<td><?php echo $beneficiaire['created']; ?></td>
						<td><?php echo $beneficiaire['nom']; ?></td>
						<td class="actions">
							<?php echo $this->Html->link(__('View'), array('action' => 'view', $beneficiaire['id'])); ?>
							/
							<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $beneficiaire['id'])); ?>
							/
							<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $beneficiaire['id']), array('confirm' => __('Are you sure you want to delete # %s?', $beneficiaire['id']))); ?>
						</td>
					</tr>
					<?php
				endif;
			endforeach; ?>
		</tbody>
	</table>
</div>