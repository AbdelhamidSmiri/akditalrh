<div class="voitures view">
	<div class="page-header">
		<h1 class="title-page">Voitures</h1>
		<span class="slogan"></span>
	</div>
	<div class="col-md-12 little-title-section">
		<span class="little-title">Voiture</span>
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
			<?php echo $this->Html->link($voiture['User']['nom'], array('controller' => 'users', 'action' => 'view', $voiture['User']['id'])); ?>
		</span>
	</div>
</div>
<div class="col-md-3">
	<div class="info">
		<label><?php echo __('Ste Location'); ?></label>
		<span><?php echo h($voiture['Voiture']['ste_location']); ?></span>
	</div>
</div>
<div class="col-md-3">
	<div class="info">
		<label><?php echo __('Immatriculation'); ?></label>
		<span><?php echo h($voiture['Voiture']['immatriculation']); ?></span>
	</div>
</div>
<div class="col-md-3">
	<div class="info">
		<label><?php echo __('Marque'); ?></label>
		<span><?php echo h($voiture['Voiture']['marque']); ?></span>
	</div>
</div>
<div class="col-md-3">
	<div class="info">
		<label><?php echo __('Modele'); ?></label>
		<span><?php echo h($voiture['Voiture']['modele']); ?></span>
	</div>
</div>
<div class="col-md-3">
	<div class="info">
		<label><?php echo __('Valeur Locative Ht'); ?></label>
		<span><?php echo h($voiture['Voiture']['valeur_locative_ht']); ?></span>
	</div>
</div>
<div class="col-md-3">
	<div class="info">
		<label><?php echo __('Duree Mois'); ?></label>
		<span><?php echo h($voiture['Voiture']['duree_mois']); ?></span>
	</div>
</div>
<div class="col-md-3">
	<div class="info">
		<label><?php echo __('Km'); ?></label>
		<span><?php echo h($voiture['Voiture']['km']); ?></span>
	</div>
</div>
<div class="col-md-3">
	<div class="info">
		<label><?php echo __('Cout Km Suplm Ttc'); ?></label>
		<span><?php echo h($voiture['Voiture']['cout_km_suplm_ttc']); ?></span>
	</div>
</div>
<div class="col-md-3">
	<div class="info">
		<label><?php echo __('Nbr Pneus'); ?></label>
		<span><?php echo h($voiture['Voiture']['nbr_pneus']); ?></span>
	</div>
</div>
<div class="col-md-3">
	<div class="info">
		<label><?php echo __('Service Link'); ?></label>
		<span><?php echo h($voiture['Voiture']['service_link']); ?></span>
	</div>
</div>
<div class="col-md-3">
	<div class="info">
		<label><?php echo __('Categorie Vr'); ?></label>
		<span><?php echo h($voiture['Voiture']['categorie_vr']); ?></span>
	</div>
</div>
<div class="col-md-3">
	<div class="info">
		<label><?php echo __('Livraison'); ?></label>
		<span><?php echo h($voiture['Voiture']['livraison']); ?></span>
	</div>
</div>
<div class="col-md-3">
	<div class="info">
		<label><?php echo __('Fin Contrat'); ?></label>
		<span><?php echo h($voiture['Voiture']['fin_contrat']); ?></span>
	</div>
</div>
<div class="col-md-3">
	<div class="info">
		<label><?php echo __('Km Actuel'); ?></label>
		<span><?php echo h($voiture['Voiture']['km_actuel']); ?></span>
	</div>
</div>
<div class="col-md-3">
	<div class="info">
		<label><?php echo __('Created'); ?></label>
		<span><?php echo h($voiture['Voiture']['created']); ?></span>
	</div>
</div>
				</div>
			</div>
		</div>
	</div>

</div>