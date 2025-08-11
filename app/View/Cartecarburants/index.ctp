<div class="cartecarburants index"></div>
<div class="page-header">
	<h1 class="title-page">Cartecarburants</h1>
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
																								<th>user_id</th>
																				<th>site_id</th>
																				<th>prestataire</th>
																				<th>identifiant</th>
																				<th>carte_labelle</th>
																				<th>utilisateur</th>
																				<th>nv_plafond</th>
																				<th>type_mt</th>
																				<th>status</th>
																				<th>created</th>
																						<th class="actions">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($cartecarburants as $cartecarburant): ?>
	<tr>
		<td>
			<?php echo $this->Html->link($cartecarburant['User']['nom'], array('controller' => 'users', 'action' => 'view', $cartecarburant['User']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($cartecarburant['Site']['site'], array('controller' => 'sites', 'action' => 'view', $cartecarburant['Site']['id'])); ?>
		</td>
		<td><?php echo $cartecarburant['Cartecarburant']['prestataire']; ?></td>
		<td><?php echo $cartecarburant['Cartecarburant']['identifiant']; ?></td>
		<td><?php echo $cartecarburant['Cartecarburant']['carte_labelle']; ?></td>
		<td><?php echo $cartecarburant['Cartecarburant']['utilisateur']; ?></td>
		<td><?php echo $cartecarburant['Cartecarburant']['nv_plafond']; ?></td>
		<td><?php echo $cartecarburant['Cartecarburant']['type_mt']; ?></td>
		<td><?php echo $cartecarburant['Cartecarburant']['status']; ?></td>
		<td><?php echo $cartecarburant['Cartecarburant']['created']; ?></td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $cartecarburant['Cartecarburant']['id'])); ?> / 
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $cartecarburant['Cartecarburant']['id'])); ?> /
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $cartecarburant['Cartecarburant']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $cartecarburant['Cartecarburant']['id']))); ?>
		</td>
	</tr>
<?php endforeach; ?>
		</tbody>
	</table>
</div>

</div>