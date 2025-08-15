<div class="users form">
	<?php echo $this->Form->create('User'); ?>

	<div class="row">
    <div class="col"></div>
    <div class="col-8">

        <div class="row">
            <div class="col-12">
                <?php
                echo $this->Form->input('role_id', [
                    'label' => 'Rôle',
                    'placeholder' => ''
                ]);
                ?>
            </div>
            <div class="col-12">
                <?php
                echo $this->Form->input('username', [
                    'label' => "Nom d'utilisateur",
                    'placeholder' => ''
                ]);
                ?>
            </div>
            <div class="col-12">
                <?php
                echo $this->Form->input('password', [
                    'label' => 'Mot de passe',
                    'placeholder' => ''
                ]);
                ?>
            </div>
            <div class="col-12">
                <?php
                echo $this->Form->input('nom', [
                    'label' => 'Nom',
                    'placeholder' => ''
                ]);
                ?>
            </div>
            <div class="col-12">
                <?php
                echo $this->Form->input('prenom', [
                    'label' => 'Prénom',
                    'placeholder' => ''
                ]);
                ?>
            </div>

            <div class="submit-section">
                <button type="submit" class="btn btn-submit">
                    <i class="fa-solid fa-paper-plane"></i> Envoyer
                </button>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
    <div class="col"></div>
</div>

</div>