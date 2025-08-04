<div class="users index"></div>

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
				<th>Catégorie</th>
				<th>Type</th>
				<th>Détail</th>
				<th>Dates</th>
				<th>Status</th>
				<th class="actions">Action</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($data as $item): ?>
				<tr>
					<td><?php echo h($item['category']); ?></td>
					<td><?php echo h($item['type']); ?></td>
					<td><?php echo h($item['detail']); ?></td>
					<td><?php echo h($item['dates']); ?></td>
					<td><?php echo h($item['status']); ?></td>
					<td class="actions">
						<?php echo $this->Html->link('<i class="fa-solid fa-eye"></i>', ['controller' => $item['controller'], 'action' => 'view', $item['id']], ['escape' => false, 'title' => 'Voir']); ?>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

</div>