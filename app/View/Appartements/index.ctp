<div class="appartements index"></div>
<div class="page-header">
	<h1 class="title-page">Appartements</h1>
	<span class="slogan"></span>
</div>
<div class="col-md-5">
	<div class="search-section">
		<div class="input-group mb-3">
			<span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-magnifying-glass"></i></span>
			<input type="text" class="form-control" id="search_input" placeholder="Rechercher" aria-label="Search">
			<button class="btn btn-primary-rounded" type="button"><i class="fa-solid fa-magnifying-glass"></i>
				Rechercher</button>
		</div>
	</div>
</div>
<div class="col-md-12 filter-section"></div>
<div class="content-table">
	<table class="table table-akdital">
		<thead>
			<tr>
				<th>nom</th>
				<th>Sexe</th>
				<th>capacite</th>
				<th>ville</th>
				<th>adresse</th>
				<th>images</th>
				<th>created</th>
				<th class="actions">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($appartements as $appartement): ?>
				<tr>
					<td><?php echo $appartement['Appartement']['nom']; ?></td>
					<td><?php echo $appartement['Appartement']['capacite']; ?></td>
					<td><?php echo $appartement['Appartement']['ville']; ?></td>
					<td><?php echo $appartement['Appartement']['adresse']; ?></td>
					<td><?php echo $appartement['Appartement']['images']; ?></td>
					<td><?php echo $appartement['Appartement']['created']; ?></td>
					<td class="actions">
						<?php echo $this->Html->link(__('View'), array('action' => 'view', $appartement['Appartement']['id'])); ?>
						/
						<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $appartement['Appartement']['id'])); ?>
						/
						<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $appartement['Appartement']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $appartement['Appartement']['id']))); ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

</div>