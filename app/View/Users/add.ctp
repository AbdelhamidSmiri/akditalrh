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
                    <div class="message-error role-error">
                        Sélectionnez un rôle pour l'utilisateur.
                    </div>
                </div>
                <div class="col-12">
                    <?php
                    echo $this->Form->input('username', [
                        'label' => "Nom d'utilisateur",
                        'placeholder' => ''
                    ]);
                    ?>
                    <div class="message-error username-error">
                        Le nom d'utilisateur est requis.
                    </div>
                </div>
                <div class="col-12">
                    <?php
                    echo $this->Form->input('password', [
                        'label' => 'Mot de passe',
                        'placeholder' => ''
                    ]);
                    ?>
                    <div class="message-error password-error">
                        Un mot de passe est obligatoire.
                    </div>
                </div>
                <div class="col-12">
                    <?php
                    echo $this->Form->input('nom', [
                        'label' => 'Nom',
                        'placeholder' => ''
                    ]);
                    ?>
                    <div class="message-error nom-error">
                        Le nom de l'utilisateur est requis.
                    </div>
                </div>
                <div class="col-12">
                    <?php
                    echo $this->Form->input('prenom', [
                        'label' => 'Prénom',
                        'placeholder' => ''
                    ]);
                    ?>
                    <div class="message-error prenom-error">
                        Le prénom de l'utilisateur est requis.
                    </div>
                </div>

                <div class="submit-section">
                    <button type="submit" class="btn btn-submit">
                        <i class="fa-solid fa-paper-plane"></i> Enregistrer
                    </button>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
        <div class="col"></div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelector('form').addEventListener('submit', function(e) {
            let isValid = true;

            // Validate role_id
            const role = document.querySelector('[name="data[User][role_id]"]');
            if (!role.value) {
                role.style.border = '1px solid #b80000';
                document.querySelector('.role-error').style.display = 'block';
                isValid = false;
            } else {
                role.style.border = '';
                document.querySelector('.role-error').style.display = 'none';
            }

            // Validate username
            const username = document.querySelector('[name="data[User][username]"]');
            if (!username.value.trim()) {
                username.style.border = '1px solid #b80000';
                document.querySelector('.username-error').style.display = 'block';
                isValid = false;
            } else {
                username.style.border = '';
                document.querySelector('.username-error').style.display = 'none';
            }

            // Validate password
            const password = document.querySelector('[name="data[User][password]"]');
            if (!password.value.trim()) {
                password.style.border = '1px solid #b80000';
                document.querySelector('.password-error').style.display = 'block';
                isValid = false;
            } else {
                password.style.border = '';
                document.querySelector('.password-error').style.display = 'none';
            }

            // Validate nom
            const nom = document.querySelector('[name="data[User][nom]"]');
            if (!nom.value.trim()) {
                nom.style.border = '1px solid #b80000';
                document.querySelector('.nom-error').style.display = 'block';
                isValid = false;
            } else {
                nom.style.border = '';
                document.querySelector('.nom-error').style.display = 'none';
            }

            // Validate prenom
            const prenom = document.querySelector('[name="data[User][prenom]"]');
            if (!prenom.value.trim()) {
                prenom.style.border = '1px solid #b80000';
                document.querySelector('.prenom-error').style.display = 'block';
                isValid = false;
            } else {
                prenom.style.border = '';
                document.querySelector('.prenom-error').style.display = 'none';
            }

            if (!isValid) {
                e.preventDefault();
                // Scroll to first error
                const firstError = document.querySelector('.message-error[style*="display: block"]');
                if (firstError) {
                    firstError.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            }
        });
    });
</script>