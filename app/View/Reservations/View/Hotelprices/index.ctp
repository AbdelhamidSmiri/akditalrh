<div class="hotelprices index"></div>
<div class="page-header">
	<h1 class="title-page">Hotelprices</h1>
	<span class="slogan"></span>
</div>
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
				<th>hotel_id</th>
				<th>date_debut</th>
				<th>date_fin</th>
				<th>prix</th>
				<th>created</th>
				<th class="actions">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($hotelprices as $hotelprice): ?>
				<tr>
					<td>
						<?php echo $this->Html->link($hotelprice['Hotel']['nom'], array('controller' => 'hotels', 'action' => 'view', $hotelprice['Hotel']['id'])); ?>
					</td>
					<td><?php echo $hotelprice['Hotelprice']['date_debut']; ?></td>
					<td><?php echo $hotelprice['Hotelprice']['date_fin']; ?></td>
					<td><?php echo $hotelprice['Hotelprice']['prix']; ?></td>
					<td><?php echo $hotelprice['Hotelprice']['created']; ?></td>
					<td class="actions">
						<?php echo $this->Html->link(__('View'), array('action' => 'view', $hotelprice['Hotelprice']['id'])); ?>
						/
						<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $hotelprice['Hotelprice']['id'])); ?>
						/
						<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $hotelprice['Hotelprice']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $hotelprice['Hotelprice']['id']))); ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

</div>