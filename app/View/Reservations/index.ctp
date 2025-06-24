<div class="reservations index"></div>

<div class="col-md-5">
	<div class="search-section">
		<div class="input-group mb-3">
			<span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-magnifying-glass"></i></span>
			<input type="text" class="form-control" id="search_input" placeholder="Rechercher" aria-label="Search">
			<button class="btn btn-primary-rounded search-btn" type="button"><i class="fa-solid fa-magnifying-glass"></i> Rechercher</button>
		</div>
	</div>
</div>
<div class="col-md-12 filter-section"></div>
<div class="content-table">
	<table class="table table-akdital">
		<thead>
			<tr>
				<th>user_id</th>
				<th>hotel_id</th>
				<th>site_id</th>
				<th>checkin</th>
				<th>checkout</th>
				<th>num_odm</th>
				<th>ordre_mission</th>
				<th>cin</th>
				<th>message</th>
				<th>created</th>
				<th>confirmation</th>
				<th>etat</th>
				<th>reponse</th>
				<th>date_reponse</th>
				<th class="actions">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($reservations as $reservation): ?>
				<tr>
					<td>
						<?php echo $this->Html->link($reservation['User']['id'], array('controller' => 'users', 'action' => 'view', $reservation['User']['id'])); ?>
					</td>
					<td>
						<?php echo $this->Html->link($reservation['Hotel']['nom'], array('controller' => 'hotels', 'action' => 'view', $reservation['Hotel']['id'])); ?>
					</td>
					<td>
						<?php echo $this->Html->link($reservation['Site']['id'], array('controller' => 'sites', 'action' => 'view', $reservation['Site']['id'])); ?>
					</td>
					<td><?php echo $reservation['Reservation']['checkin']; ?></td>
					<td><?php echo $reservation['Reservation']['checkout']; ?></td>
					<td><?php echo $reservation['Reservation']['num_odm']; ?></td>
					<td><?php echo $reservation['Reservation']['ordre_mission']; ?></td>
					<td><?php echo $reservation['Reservation']['cin']; ?></td>
					<td><?php echo $reservation['Reservation']['message']; ?></td>
					<td><?php echo $reservation['Reservation']['created']; ?></td>
					<td><?php echo $reservation['Reservation']['confirmation']; ?></td>
					<td><?php echo $reservation['Reservation']['etat']; ?></td>
					<td><?php echo $reservation['Reservation']['reponse']; ?></td>
					<td><?php echo $reservation['Reservation']['date_reponse']; ?></td>
					<td class="actions">
						<?php echo $this->Html->link(__('Voir'), array('action' => 'view', $reservation['Reservation']['id'])); ?> /
						<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $reservation['Reservation']['id'])); ?> /
						<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $reservation['Reservation']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $reservation['Reservation']['id']))); ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

</div>