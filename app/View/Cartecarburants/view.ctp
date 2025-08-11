<div class="cartecarburants view">
	<div class="page-header">
		<h1 class="title-page">Cartecarburants</h1>
		<span class="slogan"></span>
	</div>
	<div class="col-md-12 little-title-section">
		<span class="little-title">Cartecarburant</span>
		<div class="actions">
		</div>
	</div>


	<div class="card view-card">
		<div class="card-body">
			<div class="col-12">
				<div class="row row-gap-3">
					<div class="col-md-3">
	<div class="info">
		<label>User</label>
		<span>
			<?php echo $this->Html->link($cartecarburant['User']['nom'], array('controller' => 'users', 'action' => 'view', $cartecarburant['User']['id'])); ?>
		</span>
	</div>
</div>
<div class="col-md-3">
	<div class="info">
		<label>Site</label>
		<span>
			<?php echo $this->Html->link($cartecarburant['Site']['site'], array('controller' => 'sites', 'action' => 'view', $cartecarburant['Site']['id'])); ?>
		</span>
	</div>
</div>
<div class="col-md-3">
	<div class="info">
		<label><?php echo __('Prestataire'); ?></label>
		<span><?php echo h($cartecarburant['Cartecarburant']['prestataire']); ?></span>
	</div>
</div>
<div class="col-md-3">
	<div class="info">
		<label><?php echo __('Identifiant'); ?></label>
		<span><?php echo h($cartecarburant['Cartecarburant']['identifiant']); ?></span>
	</div>
</div>
<div class="col-md-3">
	<div class="info">
		<label><?php echo __('Carte Labelle'); ?></label>
		<span><?php echo h($cartecarburant['Cartecarburant']['carte_labelle']); ?></span>
	</div>
</div>
<div class="col-md-3">
	<div class="info">
		<label><?php echo __('Utilisateur'); ?></label>
		<span><?php echo h($cartecarburant['Cartecarburant']['utilisateur']); ?></span>
	</div>
</div>
<div class="col-md-3">
	<div class="info">
		<label><?php echo __('Nv Plafond'); ?></label>
		<span><?php echo h($cartecarburant['Cartecarburant']['nv_plafond']); ?></span>
	</div>
</div>
<div class="col-md-3">
	<div class="info">
		<label><?php echo __('Type Mt'); ?></label>
		<span><?php echo h($cartecarburant['Cartecarburant']['type_mt']); ?></span>
	</div>
</div>
<div class="col-md-3">
	<div class="info">
		<label><?php echo __('Status'); ?></label>
		<span><?php echo h($cartecarburant['Cartecarburant']['status']); ?></span>
	</div>
</div>
<div class="col-md-3">
	<div class="info">
		<label><?php echo __('Created'); ?></label>
		<span><?php echo h($cartecarburant['Cartecarburant']['created']); ?></span>
	</div>
</div>
				</div>
			</div>
		</div>
	</div>

</div>