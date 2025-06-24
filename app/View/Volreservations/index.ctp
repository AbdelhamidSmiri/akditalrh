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
				<th>user_id</th>
				<th>site_id</th>
				<th>depart</th>
				<th>destination</th>
				<th>date_aller</th>
				<th>date_retour</th>
				<th>num_odm</th>
				<th>ordre_mission</th>
				<th>cin</th>
				<th>passport</th>
				<th>message</th>
				<th>created</th>
				<th>etat</th>
				<th>documents</th>
				<th>reponse</th>
				<th>date_reponse</th>
				<th>num_vol</th>
				<th>file_aller</th>
				<th>file_retour</th>
				<th>transfer</th>
				<th>nom_transfer</th>
				<th>date_transfer</th>
				<th>tel_transfer</th>
				<th>description_transfer</th>
				<th class="actions">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($volreservations as $volreservation): ?>
				<tr>
					<td>
						<?php echo $this->Html->link($volreservation['User']['nom'], array('controller' => 'users', 'action' => 'view', $volreservation['User']['id'])); ?>
					</td>
					<td>
						<?php echo $this->Html->link($volreservation['Site']['id'], array('controller' => 'sites', 'action' => 'view', $volreservation['Site']['id'])); ?>
					</td>
					<td><?php echo $volreservation['Volreservation']['depart']; ?></td>
					<td><?php echo $volreservation['Volreservation']['destination']; ?></td>
					<td><?php echo $volreservation['Volreservation']['date_aller']; ?></td>
					<td><?php echo $volreservation['Volreservation']['date_retour']; ?></td>
					<td><?php echo $volreservation['Volreservation']['num_odm']; ?></td>
					<td><?php echo $volreservation['Volreservation']['ordre_mission']; ?></td>
					<td><?php echo $volreservation['Volreservation']['cin']; ?></td>
					<td><?php echo $volreservation['Volreservation']['passport']; ?></td>
					<td><?php echo $volreservation['Volreservation']['message']; ?></td>
					<td><?php echo $volreservation['Volreservation']['created']; ?></td>
					<td><?php echo $volreservation['Volreservation']['etat']; ?></td>
					<td><?php echo $volreservation['Volreservation']['documents']; ?></td>
					<td><?php echo $volreservation['Volreservation']['reponse']; ?></td>
					<td><?php echo $volreservation['Volreservation']['date_reponse']; ?></td>
					<td><?php echo $volreservation['Volreservation']['num_vol']; ?></td>
					<td><?php echo $volreservation['Volreservation']['file_aller']; ?></td>
					<td><?php echo $volreservation['Volreservation']['file_retour']; ?></td>
					<td><?php echo $volreservation['Volreservation']['transfer']; ?></td>
					<td><?php echo $volreservation['Volreservation']['nom_transfer']; ?></td>
					<td><?php echo $volreservation['Volreservation']['date_transfer']; ?></td>
					<td><?php echo $volreservation['Volreservation']['tel_transfer']; ?></td>
					<td><?php echo $volreservation['Volreservation']['description_transfer']; ?></td>
					<td class="actions">
						<?php echo $this->Html->link(__('Voir'), array('action' => 'view', $volreservation['Volreservation']['id'])); ?>
						/
						<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $volreservation['Volreservation']['id'])); ?>
						/
						<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $volreservation['Volreservation']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $volreservation['Volreservation']['id']))); ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

</div>