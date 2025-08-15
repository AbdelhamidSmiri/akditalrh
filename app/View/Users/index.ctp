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
				<th>Rôle</th>
				<th>Nom d'utilisateur</th>
				<th>État</th>
				<th>Nom</th>
				<th>Prénom</th>
				<th>Date de création</th>
				<th class="actions">Actions</th>

			</tr>
		</thead>
		<tbody>
			<?php foreach ($users as $user): ?>
				<tr>
					<td>
<?php echo h($user['Role']['role']); ?>
					</td>
					<td><?php echo $user['User']['username']; ?></td>
					<td><?php echo $user['User']['etat']; ?></td>
					<td><?php echo $user['User']['nom']; ?></td>
					<td><?php echo $user['User']['prenom']; ?></td>
					<td><?php echo $user['User']['created']; ?></td>
					<td class="actions">
						<?php echo $this->Html->link(__('Voir'), array('action' => 'view', $user['User']['id'])); ?> /
						<?php echo $this->Html->link(__('Modifier'), array('action' => 'edit', $user['User']['id'])); ?> /
						<?php echo $this->Form->postLink(__('Supprimer'), array('action' => 'delete', $user['User']['id']), array('confirm' => __('Êtes-vous sûr de vouloir supprimer cet utilisateur ?', $user['User']['id']))); ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

</div>