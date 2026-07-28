<div class="parc requetes_collaborateurs"></div>

<div class="col-md-5">
	<div class="search-section">
		<div class="input-group mb-3">
			<span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-magnifying-glass"></i></span>
			<input type="text" class="form-control" id="search_input" placeholder="Rechercher" aria-label="Search">
			<button class="btn btn-primary-rounded" type="button">
				<i class="fa-solid fa-magnifying-glass"></i> Rechercher
			</button>
		</div>
	</div>
</div>

<div class="content-table">
	<table class="table table-akdital">
		<thead>
			<tr>
				<th>#</th>
				<th>Demandeur</th>
				<th>Marque</th>
				<th>Modèle</th>
				<th>Durée (mois)</th>
				<th>Catégorie</th>
				<th>Livraison</th>
				<th>Statut</th>
				<th>Créée le</th>
				<th class="actions">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php if (empty($demandes)): ?>
				<?php echo $this->element('empty_table', array('colspan' => 10)); ?>
			<?php else: ?>
				<?php foreach ($demandes as $row): ?>
					<?php
					$d = $row['Demandevoiture'];
					$demandeur = trim(($row['User']['nom'] ?? '') . ' ' . ($row['User']['prenom'] ?? ''));
					$status = isset($d['status_demande']) ? (string)$d['status_demande'] : '';
					switch ($status) {
						case '0':
							$statusHtml = '<span class="status-btn in-progress"><i class="fas fa-clock"></i> En cours</span>';
							break;
						case '1':
							$statusHtml = '<span class="status-btn confirmed"><i class="fa-solid fa-circle-check"></i> Validée RH</span>';
							break;
						case '2':
							$statusHtml = '<span class="status-btn checked"><i class="fa-solid fa-circle-check"></i> Validée</span>';
							break;
						default:
							$statusHtml = '<span class="status-btn passe"><i class="fas fa-question-circle"></i> ' . h($status !== '' ? $status : '—') . '</span>';
					}
					?>
					<tr>
						<td><?php echo h($d['id']); ?></td>
						<td><?php echo h($demandeur !== '' ? $demandeur : '—'); ?></td>
						<td><?php echo h($d['marque']); ?></td>
						<td><?php echo h($d['modele']); ?></td>
						<td><?php echo h($d['duree_mois']); ?></td>
						<td><?php echo h($d['categorie_vr']); ?></td>
						<td><?php echo h($d['livraison']); ?></td>
						<td><?php echo $statusHtml; ?></td>
						<td><?php echo h($d['created']); ?></td>
						<td class="actions">
							<?php echo $this->Html->link(
								__('Voir'),
								array('controller' => 'parc', 'action' => 'view_requete', $d['id'])
							); ?>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
	</table>
</div>
