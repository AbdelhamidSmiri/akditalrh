<div class="sites index"></div>
<div class="page-header">
	<h1 class="title-page">Sites</h1>
	<span class="slogan"></span>
</div>
<div class="col-md-5">
	<div class="search-section">
		<div class="input-group mb-3">
			<span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-magnifying-glass"></i></span>
			<input type="text" class="form-control" id="search_input" placeholder="Rechercher" aria-label="Search">
			<button class="btn btn-primary-rounded" type="button"><i class="fa-solid fa-magnifying-glass"></i> Rechercher</button>
		</div>
	</div>
</div>
<div class="col-md-12 filter-section"></div>
<div class="content-table">
	<table class="table table-akdital">
		<thead>
			<tr>
																								<th>site</th>
																				<th>adresse</th>
																				<th>ville</th>
																				<th>telephone</th>
																				<th>mail</th>
																				<th>created</th>
													<th class="actions">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($sites as $site): ?>
	<tr>
		<td><?php echo $site['Site']['site']; ?></td>
		<td><?php echo $site['Site']['adresse']; ?></td>
		<td><?php echo $site['Site']['ville']; ?></td>
		<td><?php echo $site['Site']['telephone']; ?></td>
		<td><?php echo $site['Site']['mail']; ?></td>
		<td><?php echo $site['Site']['created']; ?></td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $site['Site']['id'])); ?> / 
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $site['Site']['id'])); ?> /
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $site['Site']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $site['Site']['id']))); ?>
		</td>
	</tr>
<?php endforeach; ?>
		</tbody>
	</table>
</div>

</div>