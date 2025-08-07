<div class="search-section w-100">
	<div class="d-flex align-items-center justify-content-between flex-wrap w-100">

		<!-- Limit input group width -->
		<div class="input-group mb-3" style="max-width: 40%;">
			<span class="input-group-text" id="basic-addon1">
				<i class="fa-solid fa-magnifying-glass"></i>
			</span>
			<input type="text" class="form-control" id="search_input" placeholder="Rechercher" aria-label="Search">
			<button class="btn btn-primary-rounded" type="button">
				<i class="fa-solid fa-magnifying-glass"></i> Rechercher
			</button>
		</div>

		<!-- Add button -->
		<div class="mb-3">
			<?php
			echo $this->Html->link(
				'<i class="fa-solid fa-paper-plane"></i> ' . __('Ajouter une affectation'),
				['controller' => 'beneficiaires', 'action' => 'add'],
				['class' => 'btn redirect-btn-rounded add-index-button', 'escape' => false]
			);
			?>
		</div>

	</div>
</div>




<div class="col-md-12 filter-section"></div>
<div class="content-table">
	<table class="table table-akdital">
		<thead>
			<tr>
				<th>Site</th>
				<th>Affecté par</th>
				<th>Appartement</th>
				<th>Nom</th>
				<th>Email</th>
				<th>Téléphone</th>
				<th>Statut</th>
				<th>État</th>
				
				<th>Date</th>
				<th>Note</th>
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
					<td>
						<?php
						switch ($beneficiaire['Beneficiaire']['etat']) {

							case 'En cours':
								echo '<span class="status-btn in-progress"><i class="fas fa-clock"></i> En cours</span>';
								break;
							case 'Checkin':
								echo '<span class="status-btn checked"><i class="fa-regular fa-right-to-bracket"></i>Arrivée</span>';
								break;
							case 'Valider':
								echo '<span class="status-btn confirmed"><i class="fa-solid fa-circle-check"></i> Confirmée</span>';
								break;
							case 'Checkout':
								echo '<span class="status-btn refused"><i class="fas fa-sign-out-alt"></i>Départ</span>';
								break;

							default:
								echo '<span class="status-btn passe"><i class="fas fa-question-circle"></i> ' . htmlspecialchars($beneficiaire['etat']) . '</span>';
						}
						?>
					</td>
				
					<td>
						<div>
							<span><?php echo $beneficiaire['Beneficiaire']['date_debut']; ?></span>
							<i class="fa-solid fa-arrow-right"></i>
							<span><?php echo $beneficiaire['Beneficiaire']['date_fin'];?></span>
						</div>
					</td>
					<td><?php echo $beneficiaire['Beneficiaire']['note']; ?></td>
					<td><?php echo $beneficiaire['Beneficiaire']['created']; ?></td>
					<td class="actions">
						<?php echo $this->Html->link(__('Voir'), array('action' => 'view', $beneficiaire['Beneficiaire']['id'])); ?>
						/
						<?php echo $this->Html->link(__('Modifier'), array('action' => 'edit', $beneficiaire['Beneficiaire']['id'])); ?>
						/
						<?php echo $this->Form->postLink(__('Supprimer'), array('action' => 'delete', $beneficiaire['Beneficiaire']['id']), array('confirm' => __('Êtes-vous sûr de vouloir supprimer cet élément ?', $beneficiaire['Beneficiaire']['id']))); ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

</div>