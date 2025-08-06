<div style="max-width: 600px; margin: auto; font-family: Arial, sans-serif;">
    <h2>Validation de la réservation</h2>
    <p>Merci de confirmer la demande en remplissant les champs ci-dessous :</p>

    <?php echo $this->Form->create('Reservation', array('url' => array('action' => 'accepte', $id))); ?>

    <div class="form-group">
        <?php echo $this->Form->input('confirmation', array(
            'label' => 'Numéro de confirmation',
            'class' => 'form-control',
            'required' => true
        )); ?>
    </div>

    <div class="form-group">
        <?php echo $this->Form->input('reponse', array(
            'label' => 'Commentaire (optionnel)',
            'class' => 'form-control',
            'type' => 'textarea',
            'rows' => 4
        )); ?>
    </div>

    <div class="form-group">
        <?php echo $this->Form->button('✅ Valider la demande', array(
            'type' => 'submit',
            'class' => 'btn btn-success'
        )); ?>
    </div>

    <?php echo $this->Form->end(); ?>
</div>
