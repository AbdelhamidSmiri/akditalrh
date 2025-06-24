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
				<th>Utilisateur</th>
				<th>Site</th>
				<th>depart</th>
				<th>destination</th>
				<th>Date aller</th>
				<th>Date retour</th>
				<th>message</th>
				<th>Date demande</th>
				<th>Prix Vol</th>
				<th>Prix Transfert</th>
				<th class="actions">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($volreservations as $volreservation): ?>
				<tr>
					<td>
						<?php echo $volreservation['User']['nom'] ?>
					</td>
					<td>
						<?php echo $volreservation['Site']['nom']; ?>
					</td>
					<td><?php echo $volreservation['Volreservation']['depart']; ?></td>
					<td><?php echo $volreservation['Volreservation']['destination']; ?></td>
					<td><?php echo $volreservation['Volreservation']['date_aller']; ?></td>
					<td><?php echo $volreservation['Volreservation']['date_retour']; ?></td>
					<td><?php echo $volreservation['Volreservation']['message']; ?></td>
					<td><?php echo $volreservation['Volreservation']['created']; ?></td>
					<td><?php echo $volreservation['Volreservation']['prix_vol']; ?> DH</td>
					<td><?php echo $volreservation['Volreservation']['prix_transfert']; ?> DH</td>
					<td class="actions">
						<?php echo $this->Html->link(__('Voir'), array('action' => 'view', $volreservation['Volreservation']['id'])); ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

</div>