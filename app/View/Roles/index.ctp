<div class="roles index"></div>

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
				<th>role</th>
				<th>plafond_hotel</th>
				<th class="actions">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($roles as $role): ?>
				<tr>
					<td><?php echo $role['Role']['role']; ?></td>
					<td><?php echo $role['Role']['plafond_hotel']; ?></td>
					<td class="actions">
						<?php echo $this->Html->link(__('Voir'), array('action' => 'view', $role['Role']['id'])); ?> /
						<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $role['Role']['id'])); ?> /
						<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $role['Role']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $role['Role']['id']))); ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

</div>