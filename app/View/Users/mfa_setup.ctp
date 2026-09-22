<div class="users form">
    <div class="row">
        <div class="col"></div>
        <div class="col-8">
            <div class="card view-card">
                <div class="card-body">
                    <p>Scannez ce QR code avec <strong>Google Authenticator</strong>, <strong>Microsoft Authenticator</strong> ou une app TOTP compatible.</p>

                    <div class="text-center my-4">
                        <img src="<?php echo h($qrUrl); ?>" alt="QR code MFA" width="220" height="220">
                    </div>

                    <p><strong>Clé secrète (saisie manuelle) :</strong><br>
                        <code style="font-size:1.05rem;letter-spacing:1px;"><?php echo h($secret); ?></code>
                    </p>

                    <p class="text-muted">Ensuite, saisissez un code à 6 chiffres généré par l’application pour confirmer l’activation.</p>

                    <?php echo $this->Form->create('User', array('url' => array('action' => 'mfa_enable'))); ?>
                    <?php echo $this->Form->input('code', array(
                        'label' => 'Code de confirmation',
                        'placeholder' => '',
                        'autocomplete' => 'one-time-code',
                        'maxlength' => 6,
                    )); ?>
                    <div class="submit-section">
                        <button type="submit" class="btn btn-submit">
                            <i class="fa-solid fa-shield-halved"></i> Activer la MFA
                        </button>
                        <?php echo $this->Html->link('Annuler', array('action' => 'view'), array('class' => 'btn btn-link')); ?>
                    </div>
                    <?php echo $this->Form->end(); ?>
                </div>
            </div>
        </div>
        <div class="col"></div>
    </div>
</div>
