<div class="users view">

	<div class="col-md-12 little-title-section">
		<span class="little-title">Détails de l’utilisateur</span>

		<div class="actions">
		</div>
	</div>


	<div class="card view-card">
		<div class="card-body">
			<div class="col-12">
    <div class="row row-gap-3">
        <div class="col-md-3">
            <div class="info">
                <label><?php echo __('Rôle'); ?></label>
                <span><?php echo h($user['Role']['role']); ?></span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info">
                <label><?php echo __('Nom d\'utilisateur'); ?></label>
                <span><?php echo h($user['User']['username']); ?></span>
            </div>
        </div>
       
        <div class="col-md-3">
            <div class="info">
                <label><?php echo __('État'); ?></label>
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
                <label><?php echo __('Prénom'); ?></label>
                <span><?php echo h($user['User']['prenom']); ?></span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info">
                <label><?php echo __('Date de création'); ?></label>
                <span><?php echo h($user['User']['created']); ?></span>
            </div>
        </div>
    </div>
</div>

		</div>
	</div>

</div>