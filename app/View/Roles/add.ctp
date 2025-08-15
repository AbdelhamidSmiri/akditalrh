<div class="roles form">
    <?php echo $this->Form->create('Role'); ?>

    <div class="row">
        <div class="col"></div>
        <div class="col-8">

            <div class="row">
                <div class="col-12">
                    <?php
                        echo $this->Form->input('role', array(
                            'label' => 'Rôle',
                            'placeholder' => ''
                        ));
                    ?>
                    <div class="message-error role-error">
                        Veuillez saisir un nom de rôle.
                    </div>
                </div>
                <div class="col-12">
                    <?php
                        echo $this->Form->input('plafond_hotel', array(
                            'label' => 'Plafond Hôtel',
                            'placeholder' => ''
                        ));
                    ?>
                    <div class="message-error plafond-error">
                        Veuillez saisir un montant valide.
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

        // Validate role name
        const role = document.querySelector('[name="data[Role][role]"]');
        if (!role.value.trim()) {
            role.style.border = '1px solid #b80000';
            document.querySelector('.role-error').style.display = 'block';
            isValid = false;
        } else {
            role.style.border = '';
            document.querySelector('.role-error').style.display = 'none';
        }

        // Validate plafond
        const plafond = document.querySelector('[name="data[Role][plafond_hotel]"]');
        if (!plafond.value.trim() || isNaN(plafond.value)) {
            plafond.style.border = '1px solid #b80000';
            document.querySelector('.plafond-error').style.display = 'block';
            isValid = false;
        } else {
            plafond.style.border = '';
            document.querySelector('.plafond-error').style.display = 'none';
        }

        if (!isValid) {
            e.preventDefault();
            // Scroll to first error
            const firstError = document.querySelector('.message-error[style*="display: block"]');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });
});
</script>