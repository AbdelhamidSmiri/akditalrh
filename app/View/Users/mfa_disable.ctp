<div class="users form">
    <div class="row">
        <div class="col"></div>
        <div class="col-8">
            <div class="card view-card">
                <div class="card-body">
                    <p>Pour désactiver la double authentification, entrez un code MFA valide.</p>
                    <?php echo $this->Form->create('User'); ?>
                    <?php echo $this->Form->input('code', array(
                        'label' => 'Code MFA',
                        'placeholder' => '',
                        'autocomplete' => 'one-time-code',
                        'maxlength' => 6,
                    )); ?>
                    <div class="submit-section">
                        <button type="submit" class="btn btn-submit">
                            Désactiver la MFA
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
