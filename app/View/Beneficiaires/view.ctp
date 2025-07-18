<div class="beneficiaires view">
	<div class="page-header">
		<h1 class="title-page">Beneficiaires</h1>
		<span class="slogan"></span>
	</div>
	<div class="col-md-12 little-title-section">
		<span class="little-title">Beneficiaire</span>
		<div class="actions">
		</div>
	</div>


	<div class="card view-card">
		<div class="card-body">
			<div class="col-12">
				<div class="row row-gap-3">
					<div class="col-md-3">
	<div class="info">
		<label>Site</label>
		<span>
			<?php echo $this->Html->link($beneficiaire['Site']['nom'], array('controller' => 'sites', 'action' => 'view', $beneficiaire['Site']['id'])); ?>
		</span>
	</div>
</div>
<div class="col-md-3">
	<div class="info">
		<label>User</label>
		<span>
			<?php echo $this->Html->link($beneficiaire['User']['nom'], array('controller' => 'users', 'action' => 'view', $beneficiaire['User']['id'])); ?>
		</span>
	</div>
</div>
<div class="col-md-3">
	<div class="info">
		<label>Appartement</label>
		<span>
			<?php echo $this->Html->link($beneficiaire['Appartement']['id'], array('controller' => 'appartements', 'action' => 'view', $beneficiaire['Appartement']['id'])); ?>
		</span>
	</div>
</div>
<div class="col-md-3">
	<div class="info">
		<label><?php echo __('Nom'); ?></label>
		<span><?php echo h($beneficiaire['Beneficiaire']['nom']); ?></span>
	</div>
</div>
<div class="col-md-3">
	<div class="info">
		<label><?php echo __('Mail'); ?></label>
		<span><?php echo h($beneficiaire['Beneficiaire']['mail']); ?></span>
	</div>
</div>
<div class="col-md-3">
	<div class="info">
		<label><?php echo __('Telephone'); ?></label>
		<span><?php echo h($beneficiaire['Beneficiaire']['telephone']); ?></span>
	</div>
</div>
<div class="col-md-3">
	<div class="info">
		<label><?php echo __('Status'); ?></label>
		<span><?php echo h($beneficiaire['Beneficiaire']['status']); ?></span>
	</div>
</div>
<div class="col-md-3">
	<div class="info">
		<label><?php echo __('Etat'); ?></label>
		<span><?php echo h($beneficiaire['Beneficiaire']['etat']); ?></span>
	</div>
</div>
<div class="col-md-3">
	<div class="info">
		<label><?php echo __('Date Debut'); ?></label>
		<span><?php echo h($beneficiaire['Beneficiaire']['date_debut']); ?></span>
	</div>
</div>
<div class="col-md-3">
	<div class="info">
		<label><?php echo __('Date Fin'); ?></label>
		<span><?php echo h($beneficiaire['Beneficiaire']['date_fin']); ?></span>
	</div>
</div>
<div class="col-md-3">
	<div class="info">
		<label><?php echo __('Note'); ?></label>
		<span><?php echo h($beneficiaire['Beneficiaire']['note']); ?></span>
	</div>
</div>
<div class="col-md-3">
	<div class="info">
		<label><?php echo __('Created'); ?></label>
		<span><?php echo h($beneficiaire['Beneficiaire']['created']); ?></span>
	</div>
</div>
				</div>
			</div>
		</div>
	</div>

</div>