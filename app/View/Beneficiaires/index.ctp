 <style>
	.reset-filters-button:focus,
.reset-filters-button:active {
    color: #000000 !important;  /* keep text black */
    outline: none !important;   /* remove default focus outline if you want */
    background: none !important; /* no background color change */
}

 	.filters-container {
 		display: flex;
 		align-items: flex-end;
 		gap: 10px;
 		flex-wrap: nowrap;
 	}

 	.filter-group:nth-child(2) {
 		margin-right: 18px;
 	}

 	#statusFilter,
 	#siteFilter {
 		width: 227px !important;
 		height: 45px !important;
 	}

 	#arrivalDateFilter,
 	#departureDateFilter {
 		width: 121px !important;
 		height: 45px !important;
 	}

 	.filter-group {
 		display: flex;
 		flex-direction: column;
 		min-width: fit-content;
 	}

 	.filter-group-label {
 		font-size: 14px;
 		color: #000000;
 		margin-bottom: 8px;
 		font-weight: 400;
 		margin-left: 9px;
 	}

 	.filter-dropdown {
 		border: 1px solid #ccccccab;
 		border-radius: 8px;
 		padding: 0 28px 0 12px;
 		font-size: 13px;
 		background-color: #ffffff;
 		color: #333333;
 		cursor: pointer;
 		appearance: none;
 		background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%23666' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");
 		background-position: right 8px center;
 		background-repeat: no-repeat;
 		background-size: 16px 16px;
 	}

 	.filter-dropdown:focus {
 		outline: none;
 		border-color: #4285f4;
 		box-shadow: 0 0 0 1px #4285f4;
 	}

 	.filter-dropdown.date-field {
 		background-color: #F2F2F2;
 		color: #000000;
 		border: none;
 		border-radius: 40px;
 	}

 	.reset-filters-button {
 		background: none;
 		border: none;
 		color: #000000;
 		font-size: 13px;
 		display: flex;
 		align-items: center;
 		gap: 6px;
 		height: 36px;
 		padding: 0;
 		cursor: pointer;
 		white-space: nowrap;
 	}

 	.reset-filters-button:hover {
 		color: #333333;
 	}

 	.reset-icon {
 		width: 14px;
 		height: 14px;
 	}

 	@media (max-width: 768px) {
 		.filters-container {
 			flex-wrap: wrap;
 			gap: 16px;
 		}
 	}
 </style>
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
 <div class="filters-container mb-4">
 	<div class="filter-group">
 		<label class="filter-group-label">Statut</label>
 		<select class="filter-dropdown" id="statusFilter">
 			<option value="">Tous</option>
 			<option value="active">Actif</option>
 			<option value="inactive">Inactif</option>
 			<option value="pending">En attente</option>
 			<option value="suspended">Suspendu</option>
 		</select>
 	</div>

 	<div class="filter-group">
 		<label class="filter-group-label">Site d'affectation</label>
 		<select class="filter-dropdown" id="siteFilter">
 			<option value="">Choisir un site</option>
 			<option value="site1">Site Principal</option>
 			<option value="site2">Site Secondaire</option>
 			<option value="site3">Site Annexe</option>
 			<option value="site4">Site Régional</option>
 		</select>
 	</div>

 	<div class="filter-group">
 		<label class="filter-group-label">Date d'arrivée</label>
 		<select class="filter-dropdown date-field" id="arrivalDateFilter">
 			<option value="">jj/mm/aaaa</option>
 			<option value="2024-01-01">01/01/2024</option>
 			<option value="2024-01-15">15/01/2024</option>
 			<option value="2024-02-01">01/02/2024</option>
 			<option value="2024-02-15">15/02/2024</option>
 			<option value="2024-03-01">01/03/2024</option>
 		</select>
 	</div>

 	<div class="filter-group">
 		<label class="filter-group-label">Date de départ</label>
 		<select class="filter-dropdown date-field" id="departureDateFilter">
 			<option value="">jj/mm/aaaa</option>
 			<option value="2024-06-01">01/06/2024</option>
 			<option value="2024-06-15">15/06/2024</option>
 			<option value="2024-07-01">01/07/2024</option>
 			<option value="2024-07-15">15/07/2024</option>
 			<option value="2024-08-01">01/08/2024</option>
 		</select>
 	</div>

 	<button class="reset-filters-button" type="button" id="resetButton">
 		Réinitialiser les filtres
<i class="fa-light fa-arrow-rotate-left"></i>
 	</button>
 </div>
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
 							<span><?php echo $beneficiaire['Beneficiaire']['date_fin']; ?></span>
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
 <script>
 	document.getElementById('resetButton').addEventListener('click', function() {
 		document.getElementById('statusFilter').selectedIndex = 0;
 		document.getElementById('siteFilter').selectedIndex = 0;
 		document.getElementById('arrivalDateFilter').selectedIndex = 0;
 		document.getElementById('departureDateFilter').selectedIndex = 0;
 		console.log('Tous les filtres ont été réinitialisés');
 	});
 </script>