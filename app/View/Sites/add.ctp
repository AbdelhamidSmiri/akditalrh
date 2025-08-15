<div class="sites form">
    <?php echo $this->Form->create('Site'); ?>
    <div class="row">
        <div class="col"></div>
        <div class="col-8">
            <div class="row">
                <div class='col-12'>
                    <?php
                    echo $this->Form->input('ville_id', [
                        'label' => 'Ville',
                        'placeholder' => ''
                    ]);
                    ?>
                    <div class="message-error ville-error">
                        Sélectionnez une ville.
                    </div>
                </div>
                <div class='col-12'>
                    <?php
                    echo $this->Form->input('site', [
                        'label' => 'Site',
                        'placeholder' => ''
                    ]);
                    ?>
                    <div class="message-error site-error">
                        Le nom du site est requis.
                    </div>
                </div>
                <div class='col-12'>
                    <?php
                    echo $this->Form->input('adresse', [
                        'label' => 'Adresse',
                        'placeholder' => ''
                    ]);
                    ?>
                    <div class="message-error adresse-error">
                        L'adresse complète est obligatoire.
                    </div>
                </div>
                <div class='col-12'>
                    <?php
                    echo $this->Form->input('telephone', [
                        'label' => 'Téléphone',
                        'placeholder' => ''
                    ]);
                    ?>
                    <div class="message-error telephone-error">
                        Un numéro de téléphone valide est requis.
                    </div>
                </div>
                <div class='col-12'>
                    <?php
                    echo $this->Form->input('mail', [
                        'label' => 'Email',
                        'placeholder' => ''
                    ]);
                    ?>
                    <div class="message-error mail-error">
                        Veuillez entrer une adresse email valide.
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
        let isValid = true;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const phoneRegex = /^[0-9]{10}$/;

        // Ville validation
        const ville = document.querySelector('[name="data[Site][ville_id]"]');
        if (!ville.value) {
            ville.style.border = '1px solid #b80000';
            document.querySelector('.ville-error').style.display = 'block';
            isValid = false;
        } else {
            ville.style.border = '';
            document.querySelector('.ville-error').style.display = 'none';
        }

        // Site name validation
        const site = document.querySelector('[name="data[Site][site]"]');
        if (!site.value.trim()) {
            site.style.border = '1px solid #b80000';
            document.querySelector('.site-error').style.display = 'block';
            isValid = false;
        } else {
            site.style.border = '';
            document.querySelector('.site-error').style.display = 'none';
        }

        // Address validation
        const adresse = document.querySelector('[name="data[Site][adresse]"]');
        if (!adresse.value.trim()) {
            adresse.style.border = '1px solid #b80000';
            document.querySelector('.adresse-error').style.display = 'block';
            isValid = false;
        } else {
            adresse.style.border = '';
            document.querySelector('.adresse-error').style.display = 'none';
        }

        // Phone validation
        const telephone = document.querySelector('[name="data[Site][telephone]"]');
        if (!telephone.value.trim() || !phoneRegex.test(telephone.value.replace(/\s/g, ''))) {
            telephone.style.border = '1px solid #b80000';
            document.querySelector('.telephone-error').style.display = 'block';
            isValid = false;
        } else {
            telephone.style.border = '';
            document.querySelector('.telephone-error').style.display = 'none';
        }

        // Email validation
        const mail = document.querySelector('[name="data[Site][mail]"]');
        if (!mail.value.trim() || !emailRegex.test(mail.value)) {
            mail.style.border = '1px solid #b80000';
            document.querySelector('.mail-error').style.display = 'block';
            isValid = false;
        } else {
            mail.style.border = '';
            document.querySelector('.mail-error').style.display = 'none';
        }

        if (!isValid) {
            e.preventDefault();
            const firstError = document.querySelector('.message-error[style*="display: block"]');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });
});
</script>