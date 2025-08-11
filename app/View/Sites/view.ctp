<div class="sites view">

	<div class="card view-card">
		<div class="card-body">
			<div class="col-12">
				<div class="row row-gap-3">
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Ville'); ?></label>
							<span><?php echo h($site['Site']['ville_id']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Site'); ?></label>
							<span><?php echo h($site['Site']['site']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Adresse'); ?></label>
							<span><?php echo h($site['Site']['adresse']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Téléphone'); ?></label>
							<span><?php echo h($site['Site']['telephone']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Email'); ?></label>
							<span><?php echo h($site['Site']['mail']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Créé le'); ?></label>
							<span><?php echo h($site['Site']['created']); ?></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>
