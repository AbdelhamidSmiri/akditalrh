<div class="hotels view">

	<div class="col-md-12 little-title-section">
		<span class="little-title"><?php echo $chambre['Hotel']['nom'] ?></span>
		<div class="actions">
		</div>
	</div>

	<div class="card view-card">
		<div class="card-body">
			<div class="col-12">
				<div class="row row-gap-3">
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Etoile'); ?></label>
							<span><?php echo h($chambre['Hotel']['etoile']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Region'); ?></label>
							<span><?php echo h($chambre['Hotel']['region']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Ville'); ?></label>
							<span><?php echo h($chambre['Hotel']['ville']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Adresse'); ?></label>
							<span><?php echo h($chambre['Hotel']['adresse']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Images'); ?></label>
							<span><?php echo h($chambre['Hotel']['images']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Mail'); ?></label>
							<span><?php echo h($chambre['Hotel']['mail']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Telephone'); ?></label>
							<span><?php echo h($chambre['Hotel']['telephone']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Nom Responsable'); ?></label>
							<span><?php echo h($chambre['Hotel']['nom_responsable']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Created'); ?></label>
							<span><?php echo h($chambre['Hotel']['created']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Reglement'); ?></label>
							<span><?php echo h($chambre['Hotel']['reglement']); ?></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>
<?php foreach($chambres as $chambre): ?>
<?php $hotelprices = $chambre["Hotelprice"]; ?>
<?php echo $this->Html->link(__('Ajouter un prix'), array("controller" => "chambres", 'action' => 'edit', $chambre['Chambre']['id']), array('class' => 'btn btn-primary')); ?>
	<div class="content-table">
		<table class="table table-akdital">
			<thead>
				<tr>
					<th>date debut</th>
					<th>date fin</th>
					<th>prix</th>
					<th>Etat</th>
					<th class="actions">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($hotelprices as $hotelprice): ?>
					<tr>
						<td><?php echo $hotelprice['date_debut']; ?></td>
						<td><?php echo $hotelprice['date_fin']; ?></td>
						<td><?php echo $hotelprice['prix']; ?></td>
						<td>
							<?= (date('Y-m-d') >= $hotelprice['date_debut'] && date('Y-m-d') <= $hotelprice['date_fin']) ? '<span style="color:green">prix actif</span>' : '<span style="color:red">prix inactif</span>'; ?>
						</td>

						<td class="actions">
							<?php echo $this->Html->link(__('Edit'), array("controller" => "hotelprices", 'action' => 'edit', $hotelprice['id'])); ?>
							/
							<?php echo $this->Form->postLink(__('Delete'), array("controller" => "hotelprices",'action' => 'delete', $hotelprice['id']), array('confirm' => __('Are you sure you want to delete # %s?', $hotelprice['id']))); ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
<?php endforeach; ?>
	