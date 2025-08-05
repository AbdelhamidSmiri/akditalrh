<div class="users view">

	<div class="col-md-12 little-title-section">
		<span class="little-title">User</span>
		<div class="actions">
		</div>
	</div>


	<div class="card view-card">
		<div class="card-body">
			<div class="col-12">
				<div class="row row-gap-3">
					<div class="col-md-3">
						<div class="info">
							<label>Role</label>
							<span>
								<?php echo $this->Html->link($user['Role']['role'], array('controller' => 'roles', 'action' => 'view', $user['Role']['id'])); ?>
							</span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Username'); ?></label>
							<span><?php echo h($user['User']['username']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Password'); ?></label>
							<span><?php echo h($user['User']['password']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Etat'); ?></label>
							<span><?php echo h($user['User']['etat']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Nom'); ?></label>
							<span><?php echo h($user['User']['nom']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Prenom'); ?></label>
							<span><?php echo h($user['User']['prenom']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Created'); ?></label>
							<span><?php echo h($user['User']['created']); ?></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>