<div class="volreservations index"></div>

<div class="col-md-5">
	<div class="search-section">
		<div class="input-group mb-3">
			<span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-magnifying-glass"></i></span>
			<input type="text" class="form-control" id="search_input" placeholder="Rechercher" aria-label="Search">
			<button class="btn btn-primary-rounded search-btn" type="button"><i class="fa-solid fa-magnifying-glass"></i>
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
				<th>Itinéraire</th>
				<th>Date de départ</th>
				<th>Date retour</th>
				<th>Message</th>
				<th>Date de soumission</th>
				<th>État</th>
				<th>Transfert</th>
				<th class="actions">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($volreservations as $volreservation): ?>
				<tr>
					<td>
						<?php echo $volreservation['Site']['site']; ?>
					</td>
					<td>
						<div>
							<span><?php echo h($volreservation['Volreservation']['depart']); ?></span>
							<i class="fa-solid fa-arrow-right"></i>
							<span><?php echo h($volreservation['Volreservation']['destination']); ?></span>
						</div>
					</td>
					<td><?php echo $volreservation['Volreservation']['date_aller']; ?></td>
					<td><?php echo $volreservation['Volreservation']['date_retour']; ?></td>
					<td><?php echo $volreservation['Volreservation']['message']; ?></td>
					<td><?php echo $volreservation['Volreservation']['created']; ?></td>
					<td><?php

						switch ($volreservation['Volreservation']['etat']) {

							case 'En cours':
								echo '<span class="status-btn in-progress"><i class="fas fa-clock"></i> En cours</span>';
								break;
							case 'Validé':
								echo '<span class="status-btn confirmed"><i class="fas fa-check-circle"></i> Validé</span>';
								break;
							case 'refusée':
								echo '<span class="status-btn refused"><i class="fas fa-times-circle"></i> Refusée</span>';
								break;
							case 'passe':
								echo '<span class="status-btn passe"><i class="fas fa-calendar-times"></i> Passé</span>';
								break;
							default:
								echo '<span class="status-btn passe"><i class="fas fa-question-circle"></i> ' . htmlspecialchars($volreservation['Volreservation']['etat']) . '</span>';
						}
						?></td>
					<td>
						<span class="badge badge-transfer">
							<?php echo ($volreservation['Volreservation']['transfer'] == "1") ? 'Oui' : 'Non';
						?>
						</span>
					</td>
					<td class="actions">
						<?php echo $this->Html->link(__('Voir détails'), array('action' => 'view', $volreservation['Volreservation']['id'])); ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

</div>
