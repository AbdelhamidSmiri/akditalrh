<div class="tagjawazs view">
	<div class="page-header">
		<h1 class="title-page">Tagjawazs</h1>
		<span class="slogan"></span>
	</div>
	<div class="col-md-12 little-title-section">
		<span class="little-title">Tagjawaz</span>
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
			<?php echo $this->Html->link($tagjawaz['User']['nom'], array('controller' => 'users', 'action' => 'view', $tagjawaz['User']['id'])); ?>
		</span>
	</div>
</div>
<div class="col-md-3">
	<div class="info">
		<label>Site</label>
		<span>
			<?php echo $this->Html->link($tagjawaz['Site']['site'], array('controller' => 'sites', 'action' => 'view', $tagjawaz['Site']['id'])); ?>
		</span>
	</div>
</div>
<div class="col-md-3">
	<div class="info">
		<label><?php echo __('Ref Jawaz'); ?></label>
		<span><?php echo h($tagjawaz['Tagjawaz']['ref_jawaz']); ?></span>
	</div>
</div>
				</div>
			</div>
		</div>
	</div>

</div>