<div style="max-width: 600px; margin: auto; font-family: Arial, sans-serif;">
    <h2>Refus de la réservation</h2>
    <p>Veuillez indiquer la raison du refus :</p>

    <?php echo $this->Form->create('Reservation', array('url' => array('action' => 'reject', $id))); ?>

    <div class="form-group">
        <?php echo $this->Form->input('reponse', array(
            'label' => 'Raison du refus',
            'class' => 'form-control',
            'type' => 'textarea',
            'rows' => 4,
            'required' => true
        )); ?>
    </div>

    <div class="form-group">
        <?php echo $this->Form->button('❌ Confirmer le refus', array(
            'type' => 'submit',
            'class' => 'btn btn-danger'
        )); ?>
    </div>

    <?php echo $this->Form->end(); ?>
</div>
