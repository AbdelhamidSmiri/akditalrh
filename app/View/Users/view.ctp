<div class="users view">

	<div class="col-md-12 little-title-section">
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
        <div class="col-md-3">
            <div class="info">
                <label>Double authentification (MFA)</label>
                <span>
                    <?php if (!empty($user['User']['totp_enabled'])): ?>
                        <span style="color:#1b7a3d;font-weight:600;">Activée</span>
                    <?php else: ?>
                        <span style="color:#b80000;font-weight:600;">Désactivée</span>
                    <?php endif; ?>
                </span>
            </div>
        </div>
    </div>

    <?php if ((int)$user['User']['id'] === (int)AuthComponent::user('id')): ?>
        <div class="mt-4">
            <?php if (!empty($mfaRequired) && empty($user['User']['totp_enabled'])): ?>
                <p style="color:#b80000;margin-bottom:10px;">La MFA est <strong>obligatoire</strong> pour votre rôle.</p>
            <?php endif; ?>
            <?php if (empty($user['User']['totp_enabled'])): ?>
                <?php echo $this->Html->link(
                    '<i class="fa-solid fa-shield-halved"></i> Activer la MFA',
                    array('action' => 'mfa_setup'),
                    array('class' => 'btn btn-submit', 'escape' => false)
                ); ?>
            <?php elseif (empty($mfaRequired)): ?>
                <?php echo $this->Html->link(
                    'Désactiver la MFA',
                    array('action' => 'mfa_disable'),
                    array('class' => 'btn btn-outline-danger')
                ); ?>
            <?php else: ?>
                <p style="color:#6c757d;margin:0;">La MFA est obligatoire pour votre rôle (désactivation impossible).</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

		</div>
	</div>

</div>