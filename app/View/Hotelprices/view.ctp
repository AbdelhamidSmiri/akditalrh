<div class="hotelprices view">
	<div class="page-header">
		<h1 class="title-page">Hotelprices</h1>
		<span class="slogan"></span>
	</div>
	<div class="col-md-12 little-title-section">
		<span class="little-title">Hotelprice</span>
		<div class="actions">
		</div>
	</div>


	<div class="card view-card">
		<div class="card-body">
			<div class="col-12">
				<div class="row row-gap-3">
					<div class="col-md-3">
	<div class="info">
		<label>Hotel</label>
		<span>
			<?php echo $this->Html->link($hotelprice['Hotel']['nom'], array('controller' => 'hotels', 'action' => 'view', $hotelprice['Hotel']['id'])); ?>
		</span>
	</div>
</div>
<div class="col-md-3">
	<div class="info">
		<label><?php echo __('Date Debut'); ?></label>
		<span><?php echo h($hotelprice['Hotelprice']['date_debut']); ?></span>
	</div>
</div>
<div class="col-md-3">
	<div class="info">
		<label><?php echo __('Date Fin'); ?></label>
		<span><?php echo h($hotelprice['Hotelprice']['date_fin']); ?></span>
	</div>
</div>
<div class="col-md-3">
	<div class="info">
		<label><?php echo __('Prix'); ?></label>
		<span><?php echo h($hotelprice['Hotelprice']['prix']); ?></span>
	</div>
</div>
<div class="col-md-3">
	<div class="info">
		<label><?php echo __('Created'); ?></label>
		<span><?php echo h($hotelprice['Hotelprice']['created']); ?></span>
	</div>
</div>
				</div>
			</div>
		</div>
	</div>

</div>