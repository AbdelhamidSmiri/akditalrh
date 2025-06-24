<div class="hotels index"></div>

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
				<th>nom</th>
				<th>etoile</th>
				<th>region</th>
				<th>ville</th>
				<th>adresse</th>
				<th>images</th>
				<th>mail</th>
				<th>telephone</th>
				<th>nom_responsable</th>
				<th>created</th>
				<th>reglement</th>
				<th class="actions">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($hotels as $hotel): ?>
				<tr>
					<td><?php echo $hotel['Hotel']['nom']; ?></td>
					<td><?php echo $hotel['Hotel']['etoile']; ?></td>
					<td><?php echo $hotel['Hotel']['region']; ?></td>
					<td><?php echo $hotel['Hotel']['ville']; ?></td>
					<td><?php echo $hotel['Hotel']['adresse']; ?></td>
					<td><?php echo $hotel['Hotel']['images']; ?></td>
					<td><?php echo $hotel['Hotel']['mail']; ?></td>
					<td><?php echo $hotel['Hotel']['telephone']; ?></td>
					<td><?php echo $hotel['Hotel']['nom_responsable']; ?></td>
					<td><?php echo $hotel['Hotel']['created']; ?></td>
					<td><?php echo $hotel['Hotel']['reglement']; ?></td>
					<td class="actions">
						<?php echo $this->Html->link(__('View'), array('action' => 'view', $hotel['Hotel']['id'])); ?> /
						<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $hotel['Hotel']['id'])); ?> /
						<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $hotel['Hotel']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $hotel['Hotel']['id']))); ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

</div>