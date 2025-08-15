<div class="villes form">
    <?php echo $this->Form->create('Ville'); ?>
    
    <div class="row">
        <div class="col"></div>
        <div class="col-8">
            <div class="row">
                <div class='col-12'>
                    <?php
                    echo $this->Form->input('ville', [
                        'label' => 'Nom de la ville',
                        'placeholder' => ''
                    ]);
                    ?>
                    <div class="message-error ville-error">
                        Le nom de la ville est obligatoire
                    </div>
                </div>
                
                <div class='submit-section'>
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
        const villeInput = document.querySelector('[name="data[Ville][ville]"]');
        const errorElement = document.querySelector('.ville-error');
        let isValid = true;

        // Ville validation (minimum 2 characters)
        if (!villeInput.value.trim() || villeInput.value.trim().length < 2) {
            villeInput.style.border = '1px solid #b80000';
            errorElement.style.display = 'block';
            isValid = false;
        } else {
            villeInput.style.border = '';
            errorElement.style.display = 'none';
        }

        if (!isValid) {
            e.preventDefault();
            errorElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
});
</script>